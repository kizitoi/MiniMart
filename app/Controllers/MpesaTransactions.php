<?php namespace App\Controllers;

use App\Models\MpesaTransactionsModel;
use App\Models\HeaderModel;
use App\Models\UserModel;
use App\Libraries\UserDataHelper;

class MpesaTransactions extends BaseController
{
    protected $userModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
    }

  
    public function index()
    {
        $model = new MpesaTransactionsModel();

      /*  $data = array_merge($this->loadUserData(), [
            'transactions' => $model->orderBy('created_at', 'DESC')->findAll()
        ]);*/

        $helper = new UserDataHelper();
        $data = $helper->load() + [
            'transactions' => $model->orderBy('created_at', 'DESC')->findAll()
        ];


        return view('mpesa/transactions', $data);
    }

    public function callback()
    {
        $input = json_decode(file_get_contents('php://input'), true);

        if (!isset($input['Body']['stkCallback'])) {
            return $this->response->setStatusCode(400)->setBody('Invalid callback');
        }

        $body = $input['Body']['stkCallback'];
        $model = new MpesaTransactionsModel();

        $data = [
            'MerchantRequestID'   => $body['MerchantRequestID'],
            'CheckoutRequestID'   => $body['CheckoutRequestID'],
            'ResultCode'          => $body['ResultCode'],
            'ResultDesc'          => $body['ResultDesc'],
            'created_at'          => date('Y-m-d H:i:s', now('Africa/Nairobi')),
        ];

        if ($body['ResultCode'] == 0) {
            $meta = [];

            foreach ($body['CallbackMetadata']['Item'] as $item) {
                if (isset($item['Name']) && isset($item['Value'])) {
                    $meta[$item['Name']] = $item['Value'];
                }
            }

            $data += [
                'Amount'              => $meta['Amount'] ?? null,
                'MpesaReceiptNumber'  => $meta['MpesaReceiptNumber'] ?? null,
                'Balance'             => $meta['Balance'] ?? null,
                'TransactionDate'     => isset($meta['TransactionDate']) ? date('Y-m-d H:i:s', strtotime($meta['TransactionDate'])) : null,
                'PhoneNumber'         => $meta['PhoneNumber'] ?? null,
                'status'              => 'Completed',
                'name'                => $meta['FirstName'] ?? '', // optionally append LastName if available
            ];
        } else {
            $data['status'] = 'Failed';
        }

        // Avoid inserting duplicates
        $existing = $model->where('CheckoutRequestID', $data['CheckoutRequestID'])->first();
        if (!$existing) {
            $model->insert($data);
        }

        return $this->response->setStatusCode(200);
    }

    /**
     * AJAX status checker
     * Route: /api/check_payment_status?phone=2547XXXXXXX
     */
    public function checkStatus()
    {
        $phone = $this->request->getGet('phone');
        if (!$phone) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Missing phone number']);
        }

        $model = new MpesaTransactionsModel();
        $row = $model
            ->where('PhoneNumber', $phone)
            ->where('status', 'Completed')
            ->orderBy('TransactionDate', 'DESC')
            ->first();

        if ($row) {
            return $this->response->setJSON([
                'status' => 'success',
                'phone' => $row['PhoneNumber'],
                'name' => $row['name'] ?? 'Customer',
                'amount' => number_format($row['Amount'], 2)
            ]);
        }

        return $this->response->setJSON(['status' => 'pending']);
    }
}


/*namespace App\Controllers;
use App\Models\MpesaTransactionsModel;
use App\Models\HeaderModel;
use App\Models\UserModel;

class MpesaTransactions extends BaseController {


  protected $userModel;

  public function __construct()
  {

  $this->userModel = new UserModel();
  }


  private function loadUserData()
  {
  $session = session();
  if (!$session->get('logged_in')) {
    return redirect()->to('login');
  }
      $session = session();
      $headerModel = new HeaderModel();
      $userId = $session->get('user_id');
      $user = $this->userModel->find($userId);
      return [
          'username' => $session->get('username'),
          'name' => $session->get('name'),
          'signature_link' => $session->get('signature_link'),
          'email' => $session->get('email'),
          'mobile' => $session->get('mobile'),
          'profile_image' => $session->get('profile_image'),
          'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
      ];
  }
    public function index(){
        $model = new MpesaTransactionsModel();
        $data = array_merge($this->loadUserData(), [
            'transactions' => $model->orderBy('created_at','DESC')->findAll()
        ]);
        return view('mpesa/transactions', $data);
    }

    public function callback(){
        $input = json_decode(file_get_contents('php://input'), true);
        // Parse according to Daraja callback structure
        $body = $input['Body']['stkCallback'];
        $model = new MpesaTransactionsModel();
        $data = [
            'MerchantRequestID' => $body['MerchantRequestID'],
            'CheckoutRequestID' => $body['CheckoutRequestID'],
            'ResultCode' => $body['ResultCode'],
            'ResultDesc' => $body['ResultDesc']
        ];
        if($body['ResultCode'] == 0){
            $meta = [];
            foreach($body['CallbackMetadata']['Item'] as $item){
                if(isset($item['Name'])) $meta[$item['Name']] = $item['Value'];
            }
            $data += [
                'Amount' => $meta['Amount'] ?? null,
                'MpesaReceiptNumber' => $meta['MpesaReceiptNumber'] ?? null,
                'Balance' => $meta['Balance'] ?? null,
                'TransactionDate' => date('Y-m-d H:i:s',strtotime($meta['TransactionDate'])),
                'PhoneNumber' => $meta['PhoneNumber'] ?? null
            ];
        }
        $model->insert($data);
        return $this->response->setStatusCode(200);
    }
}
*/
