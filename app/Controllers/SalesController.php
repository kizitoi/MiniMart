<?php

namespace App\Controllers;
use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\RoleModel;
use App\Libraries\UserDataHelper;


class SalesController extends BaseController
{

public function add()
{
    $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }

    $data = $this->request->getJSON(true);
    $userId = session()->get('user_id');
    $userName = session()->get('username');

    $db = \Config\Database::connect();

    $db->transStart();

    $order = $db->table('sales_orders')
        ->where('created_by_user_id', $userId)
        ->where('status', 'new')
        ->get()
        ->getRow();

    if (!$order) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'No active order. Please create one first.'
        ]);
    }

    $item = $db->table('items')->where('id', $data['item_id'])->get()->getRow();

    if (!$item) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Item not found.'
        ]);
    }

    $quantity = intval($data['quantity']);

    if ($item->quantity < $quantity) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Not enough stock available.'
        ]);
    }

    $vat = $db->table('vat_settings')->where('id', $item->vat_id)->get()->getRow();

    if (!$vat) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'VAT setting not found.'
        ]);
    }

    $vatRate = floatval($vat->vat_perc);
    $vatCode = $vat->vat_code ?? ('VAT' . $vatRate);

    $unitPrice = floatval($item->unit_price);
    $totalCost = $unitPrice * $quantity;
    $vatAmount = $totalCost * ($vatRate / 100);

    $db->table('sales')->insert([
        'sales_order_id' => $order->id,
        'category_id'    => $data['category_id'],
        'item_id'        => $data['item_id'],
        'item_name'      => $item->name,
        'user_id'        => $userId,
        'quantity'       => $quantity,
        'sellingPrice'   => $unitPrice,
        'shop_id'        => $data['shop_id'],
        'total_cost'     => $totalCost,
        'vat_code'       => $vatCode,
        'vat_rate'       => $vatRate,
        'vat_amnt'       => $vatAmount,
        'vatable_amnt'   => $totalCost
    ]);

    // Calculate remaining stock after sale
    $remainingQuantity = $item->quantity - $quantity;

    // Reduce item quantity in stock
    $db->table('items')
        ->where('id', $data['item_id'])
        ->set('quantity', 'quantity - ' . $quantity, false)
        ->update();

    // Check if reorder level is reached or exceeded
    if ($remainingQuantity <= $item->reorder_level_quantity) {
        // Send reorder level email
        $this->sendReorderLevelEmail([
            'item_id' => $item->id,
            'item_name' => $item->name,
            'current_quantity' => $remainingQuantity,
            'reorder_level' => $item->reorder_level_quantity
        ]);

        // Send reorder level SMS
        $this->sendReorderLevelSMS([
            'item_id' => $item->id,
            'item_name' => $item->name,
            'current_quantity' => $remainingQuantity,
            'reorder_level' => $item->reorder_level_quantity
        ]);


        $this->sendSalesEmail([
            'item_id' => $item->id,
            'item_name' => $item->name,
            'current_quantity' => $remainingQuantity,
            'reorder_level' => $item->reorder_level_quantity
        ]);

        $this->sendSalesSMS([
            'item_id' => $item->id,
            'item_name' => $item->name,
            'current_quantity' => $remainingQuantity,
            'reorder_level' => $item->reorder_level_quantity
        ]);

    }

    $db->transComplete();

    if ($db->transStatus() === false) {
        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Failed to add item to order.'
        ]);
    }

    return $this->response->setJSON(['status' => 'success']);
}



private function sendReorderLevelEmail($data)
{
    $db = \Config\Database::connect();

    $builder = $db->table('user_notifications');
    $builder->select('users.email');
    $builder->join('users', 'users.user_id = user_notifications.user_id');
    $builder->where('user_notifications.low_stock', 1);
    $query = $builder->get();

    $emails = array_column($query->getResultArray(), 'email');

    $subject = "Reorder Level Alert for " . $data['item_name'];

    $message = "<p><strong>Item:</strong> {$data['item_name']} (ID: {$data['item_id']})</p>";
    $message .= "<p><strong>Current Stock:</strong> {$data['current_quantity']}</p>";
    $message .= "<p><strong>Reorder Level:</strong> {$data['reorder_level']}</p>";
    $message .= "<p>Please restock this item as soon as possible.</p>";
    $message .= "<p><a href='https://nairobimetaldetectors.net'>Login to the NMD Portal</a></p>";

    $email = \Config\Services::email();
    $email->setFrom('pos@nairobimetaldetectors.net', 'NMD P.O.S System');
    $email->setTo($emails);
    $email->setSubject($subject);
    $email->setMessage($message);
    $email->setMailType('html');

    if (!$email->send()) {
        log_message('error', 'Email failed: ' . $email->printDebugger(['headers']));
    }
}

private function sendReorderLevelSMS($data)
{
   $db = \Config\Database::connect();

   $smsSettings = $db->table('bulksms_settings')->get()->getRow();
   $username = $smsSettings->username;
   $password = $smsSettings->password;
   $url = $smsSettings->api_url;

   $builder = $db->table('user_notifications');
   $builder->select('users.mobile');
   $builder->join('users', 'users.user_id = user_notifications.user_id');
   $builder->where('user_notifications.low_stock_sms', 1);
   $query = $builder->get();
   $users = $query->getResult();

   $seven_bit_msg = "Item is below Re-order level. {$data['item_name']} (ID: {$data['item_id']})";
   $seven_bit_msg .= " Current Stock: {$data['current_quantity']}";
   $seven_bit_msg .= " Reorder Level: {$data['reorder_level']}";
   $seven_bit_msg .= " Please restock this item as soon as possible.";

   foreach ($users as $user) {
       $raw_mobile = trim($user->mobile);

       // Normalize to international format
       if (preg_match('/^0(7|1)\d{8}$/', $raw_mobile)) {
           $msisdn = '254' . substr($raw_mobile, 1);
       } else {
           $msisdn = $raw_mobile;
       }

       $post_body = $this->seven_bit_sms($username, $password, $seven_bit_msg, $msisdn);
       $result = $this->send_message($post_body, $url);

       if (!$result['success']) {
           log_message('error', 'SMS failed to ' . $msisdn);
       }
   }
}




private function sendSalesSMS($data)
{
   $db = \Config\Database::connect();

   $smsSettings = $db->table('bulksms_settings')->get()->getRow();
   $username = $smsSettings->username;
   $password = $smsSettings->password;
   $url = $smsSettings->api_url;

   $builder = $db->table('user_notifications');
   $builder->select('users.mobile');
   $builder->join('users', 'users.user_id = user_notifications.user_id');
   $builder->where('user_notifications.item_sale', 1);
   $query = $builder->get();
   $users = $query->getResult();

   $seven_bit_msg = "Item has been sold. {$data['item_name']} (ID: {$data['item_id']})";
   $seven_bit_msg .= " Current Stock: {$data['current_quantity']}";
   $seven_bit_msg .= " Reorder Level: {$data['reorder_level']}";
   $seven_bit_msg .= " This item has been sold.";

   foreach ($users as $user) {
       $raw_mobile = trim($user->mobile);

       // Normalize to international format
       if (preg_match('/^0(7|1)\d{8}$/', $raw_mobile)) {
           $msisdn = '254' . substr($raw_mobile, 1);
       } else {
           $msisdn = $raw_mobile;
       }

       $post_body = $this->seven_bit_sms($username, $password, $seven_bit_msg, $msisdn);
       $result = $this->send_message($post_body, $url);

       if (!$result['success']) {
           log_message('error', 'SMS failed to ' . $msisdn);
       }
   }
}





private function sendSalesEmail($data)
{
    $db = \Config\Database::connect();

    $builder = $db->table('user_notifications');
    $builder->select('users.email');
    $builder->join('users', 'users.user_id = user_notifications.user_id');
    $builder->where('user_notifications.item_sale', 1);
    $query = $builder->get();

    $emails = array_column($query->getResultArray(), 'email');

    $subject = "Item Sale Alert for " . $data['item_name'];

    $message = "<p><strong>Item:</strong> {$data['item_name']} (ID: {$data['item_id']})</p>";
    $message .= "<p><strong>Current Stock:</strong> {$data['current_quantity']}</p>";
    $message .= "<p><strong>Reorder Level:</strong> {$data['reorder_level']}</p>";
    $message .= "<p>This Item has been sold.</p>";
    $message .= "<p><a href='https://nairobimetaldetectors.net'>Login to the NMD Portal</a></p>";

    $email = \Config\Services::email();
    $email->setFrom('pos@nairobimetaldetectors.net', 'NMD P.O.S System');
    $email->setTo($emails);
    $email->setSubject($subject);
    $email->setMessage($message);
    $email->setMailType('html');

    if (!$email->send()) {
        log_message('error', 'Email failed: ' . $email->printDebugger(['headers']));
    }
}



  //bulk SMS FUNCTIONS
    public function print_ln($content) {
        if (isset($_SERVER["SERVER_NAME"])) {
          print $content."<br />";
        }
        else {
          print $content."\n";
        }
      }
    public function formatted_server_response( $result ) {
        $this_result = "";

        if ($result['success']) {
          $this_result .= "Success: batch ID " .$result['api_batch_id']. "API message: ".$result['api_message']. "\nFull details " .$result['details'];
        }
        else {
          $this_result .= "Fatal error: HTTP status " .$result['http_status_code']. ", API status " .$result['api_status_code']. " API message " .$result['api_message']. " full details " .$result['details'];

          if ($result['transient_error']) {
            $this_result .=  "This is a transient error - you should retry it in a production environment";
          }
        }
        return $this_result;
      }
    public function send_message ( $post_body, $url ) {

        $ch = curl_init( );
        curl_setopt ( $ch, CURLOPT_URL, $url );
        curl_setopt ( $ch, CURLOPT_POST, 1 );
        curl_setopt ( $ch, CURLOPT_RETURNTRANSFER, 1 );
        curl_setopt ( $ch, CURLOPT_POSTFIELDS, $post_body );
        // Allowing cUrl funtions 20 second to execute
        curl_setopt ( $ch, CURLOPT_TIMEOUT, 20 );
        // Waiting 20 seconds while trying to connect
        curl_setopt ( $ch, CURLOPT_CONNECTTIMEOUT, 20 );

        $response_string = curl_exec( $ch );
        $curl_info = curl_getinfo( $ch );

        $sms_result = array();
        $sms_result['success'] = 0;
        $sms_result['details'] = '';
        $sms_result['transient_error'] = 0;
        $sms_result['http_status_code'] = $curl_info['http_code'];
        $sms_result['api_status_code'] = '';
        $sms_result['api_message'] = '';
        $sms_result['api_batch_id'] = '';

        if ( $response_string == FALSE ) {
          $sms_result['details'] .= "cURL error: " . curl_error( $ch ) . "\n";
        } elseif ( $curl_info[ 'http_code' ] != 200 ) {
          $sms_result['transient_error'] = 1;
          $sms_result['details'] .= "Error: non-200 HTTP status code: " . $curl_info[ 'http_code' ] . "\n";
        }
        else {
          $sms_result['details'] .= "Response from server: $response_string\n";
          $api_result = explode( '|', $response_string );
          $status_code = $api_result[0];
          $sms_result['api_status_code'] = $status_code;
          $sms_result['api_message'] = $api_result[1];
          if ( count( $api_result ) != 3 ) {
            $sms_result['details'] .= "Error: could not parse valid return data from server.\n" . count( $api_result );
          } else {
            if ($status_code == '0') {
              $sms_result['success'] = 1;
              $sms_result['api_batch_id'] = $api_result[2];
              $sms_result['details'] .= "Message sent - batch ID $api_result[2]\n";
            }
            else if ($status_code == '1') {
              # Success: scheduled for later sending.
              $sms_result['success'] = 1;
              $sms_result['api_batch_id'] = $api_result[2];
            }
            else {
              $sms_result['details'] .= "Error sending: status code [$api_result[0]] description [$api_result[1]]\n";
            }

          }
        }
        curl_close( $ch );

        return $sms_result;
      }
    public function seven_bit_sms ( $username, $password, $message, $msisdn ) {
        $post_fields = array (
        'username' => $username,
        'password' => $password,
        'message'  =>$this->character_resolve( $message ),
        'msisdn'   => $msisdn,
        'allow_concat_text_sms' => 0, # Change to 1 to enable long messages
        'concat_text_sms_max_parts' => 2
        );

        return $this->make_post_body($post_fields);
      }
    public function make_post_body($post_fields) {
        $stop_dup_id = $this->make_stop_dup_id();
        if ($stop_dup_id > 0) {
          $post_fields['stop_dup_id'] =$this-> make_stop_dup_id();
        }
        $post_body = '';
        foreach( $post_fields as $key => $value ) {
          $post_body .= urlencode( $key ).'='.urlencode( $value ).'&';
        }
        $post_body = rtrim( $post_body,'&' );

        return $post_body;
      }
    public function character_resolve($body) {
        $special_chrs = array(
          'Δ'=>0xD0, 'Φ'=>0xDE, 'Γ'=>0xAC, 'Λ'=>0xC2, 'Ω'=>0xDB,
          'Π'=>0xBA, 'Ψ'=>0xDD, 'Σ'=>0xCA, 'Θ'=>0xD4, 'Ξ'=>0xB1,
          '¡'=>0xA1, '£'=>0xA3, '¤'=>0xA4, '¥'=>0xA5, '§'=>0xA7,
          '¿'=>0xBF, 'Ä'=>0xC4, 'Å'=>0xC5, 'Æ'=>0xC6, 'Ç'=>0xC7,
          'É'=>0xC9, 'Ñ'=>0xD1, 'Ö'=>0xD6, 'Ø'=>0xD8, 'Ü'=>0xDC,
          'ß'=>0xDF, 'à'=>0xE0, 'ä'=>0xE4, 'å'=>0xE5, 'æ'=>0xE6,
          'è'=>0xE8, 'é'=>0xE9, 'ì'=>0xEC, 'ñ'=>0xF1, 'ò'=>0xF2,
          'ö'=>0xF6, 'ø'=>0xF8, 'ù'=>0xF9, 'ü'=>0xFC,
        );

        $ret_msg = '';
        if( mb_detect_encoding($body, 'UTF-8') != 'UTF-8' ) {
          $body = utf8_encode($body);
        }
        for ( $i = 0; $i < mb_strlen( $body, 'UTF-8' ); $i++ ) {
          $c = mb_substr( $body, $i, 1, 'UTF-8' );
          if( isset( $special_chrs[ $c ] ) ) {
            $ret_msg .= chr( $special_chrs[ $c ] );
          }
          else {
            $ret_msg .= $c;
          }
        }
        return $ret_msg;
      }
    public function make_stop_dup_id() {
        return 0;
      }
    public function string_to_utf16_hex( $string ) {
        return bin2hex(mb_convert_encoding($string, "UTF-16", "UTF-8"));
      }
    public function xml_to_wbxml( $msg_body ) {

        $wbxmlfile = 'xml2wbxml_'.md5(uniqid(time())).'.wbxml';
        $xmlfile = 'xml2wbxml_'.md5(uniqid(time())).'.xml';

        //create temp file
        $fp = fopen($xmlfile, 'w+');

        fwrite($fp, $msg_body);
        fclose($fp);

        //convert temp file
        exec(xml2wbxml.' -v 1.2 -o '.$wbxmlfile.' '.$xmlfile.' 2>/dev/null');
        if(!file_exists($wbxmlfile)) {
          print_ln('Fatal error: xml2wbxml conversion failed');
          return false;
        }

        $wbxml = trim(file_get_contents($wbxmlfile));

        //remove temp files
        unlink($xmlfile);
        unlink($wbxmlfile);
        return $wbxml;
      }
  ///BULK SMS FUNCTIONS
}
