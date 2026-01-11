<?php namespace App\Controllers;

use App\Models\HeaderModel;
use App\Models\UserModel;
use App\Models\SalesOrderModel;
use App\Models\DiscountSettingsModel;
use CodeIgniter\Controller;
use App\Libraries\UserDataHelper;
use App\Models\MpesaSettingsModel;

class SalesOrderController extends BaseController
{
    protected $salesOrderModel;
    protected $salesModel;
  	protected $userModel;


    public function __construct()
    {
        $this->salesOrderModel = new SalesOrderModel();
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


    private function sendStkPush($phone, $amount){
        $mpesa = model(MpesaSettingsModel::class)->first();
        // Get token
        $credentials = base64_encode($mpesa['consumer_key'].':'.$mpesa['consumer_secret']);
        $tokenRes = json_decode($this->curlRequest('https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials', 'GET',['Authorization: Basic '.$credentials]));
        $token = $tokenRes->access_token;
        // Prepare STK
        $timestamp = date('YmdHis');
        $password = base64_encode($mpesa['shortcode'].$mpesa['passkey'].$timestamp);
        $stkBody = [
            "BusinessShortCode" => $mpesa['shortcode'],
            "Password" => $password,
            "Timestamp" => $timestamp,
            "TransactionType" => "CustomerPayBillOnline",
            "Amount" => $amount,
            "PartyA" => $phone,
            "PartyB" => $mpesa['shortcode'],
            "PhoneNumber" => $phone,
            "CallBackURL" => site_url('mpesa/callback'),
            "AccountReference" => "OrderPayment",
            "TransactionDesc" => "Payment for Sales Order"
        ];
        $res = json_decode($this->curlRequest('https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest', 'POST',
            ["Authorization: Bearer $token","Content-Type: application/json"],
            json_encode($stkBody)
        ), true);
        return $res;
    }

    private function curlRequest($url,$method,$headers=[],$payload=null){
        $ch=curl_init($url);
        curl_setopt($ch,CURLOPT_RETURNTRANSFER,true);
        curl_setopt($ch,CURLOPT_HTTPHEADER,$headers);
        if($payload){
            curl_setopt($ch,CURLOPT_POST,true);
            curl_setopt($ch,CURLOPT_POSTFIELDS,$payload);
        }
        return curl_exec($ch);
    }

public function current()
{
    $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }

    $userId = $session->get('user_id');
    $helper = new UserDataHelper();
    $data = $helper->load();
    $db = \Config\Database::connect();

    // Fetch customers
    $customers = $db->table('customers')
        ->orderBy('name', 'asc')
        ->get()
        ->getResultArray(); // use getResultArray for use in form dropdown

    $order = $db->table('sales_orders')
        ->where('created_by_user_id', $userId)
        ->where('status', 'new')
        ->get()
        ->getRow();

    if (!$order) {
        return view('items/sales_order', ['order' => null, 'items' => [], 'customers' => $customers] + $data);
    }

    $items = $db->query("
        SELECT s.*, i.name as item_name, vs.vat_perc, vs.vat_code,
               (s.sellingPrice * s.quantity) as line_total,
               ((s.sellingPrice * s.quantity) * IFNULL(vs.vat_perc, 0) / 100) as vat_amnt
        FROM sales s
        JOIN items i ON i.id = s.item_id
        LEFT JOIN vat_settings vs ON i.vat_id = vs.id
        WHERE s.sales_order_id = ?
    ", [$order->id])->getResult();

    $grandTotal = 0;
    $totalVAT = 0;
    foreach ($items as $item) {
        $grandTotal += $item->line_total;
        $totalVAT += $item->vat_amnt ?? 0;
    }

    $discountModel = new DiscountSettingsModel();

    $availableDiscounts = $discountModel
        ->where('enabled', 1)
        ->findAll();

    $discount = $discountModel
        ->where('enabled', 1)
        ->where('min_shopping_amount <=', $grandTotal)
        ->where('maximum_shopping_amount >=', $grandTotal)
        ->orderBy('discount_amount', 'DESC')
        ->first();

    $appliedDiscount = $discount ? [
        'id' => $discount['id'],
        'discount_name' => $discount['discount_name'],
        'discount_amount' => $discount['discount_amount'],
    ] : null;

    $company = $db->table('companies')->get()->getRow();

    return view('items/sales_order', $data + [
        'order' => $order,
        'items' => $items,
        'company' => $company,
        'grandTotal' => $grandTotal,
        'totalVAT' => $totalVAT,
        'appliedDiscount' => $appliedDiscount,
        'availableDiscounts' => $availableDiscounts,
        'customers' => $customers // 👈 pass to view
    ]);
}




    public function close()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $userId = $session->get('user_id');
        $helper = new \App\Libraries\UserDataHelper();
        $data = $helper->load(); // Optional param can be passed if needed

        $userName = $data['username'];
        $cashierId = $userId;

        $amountPaid = (float) $this->request->getPost('amount_paid');
        $paymentMethod = $this->request->getPost('payment_method');

        $db = \Config\Database::connect();

        $order = $db->table('sales_orders')
            ->where('created_by_user_id', $userId)
            ->where('status', 'new')
            ->get()
            ->getRow();

        if (!$order) {
            return redirect()->back()->with('error', 'No active order to close.');
        }

        // Fetch all sales items for this order
        $items = $db->query("
            SELECT s.*, i.name as item_name, i.vatable, vs.vat_perc, vs.vat_code,
                   (s.sellingPrice * s.quantity) as line_total, s.id as sales_id, s.shop_id
            FROM sales s
            JOIN items i ON i.id = s.item_id
            LEFT JOIN vat_settings vs ON i.vat_id = vs.id
            WHERE s.sales_order_id = ?
        ", [$order->id])->getResult();

        $grandTotal = 0;
        foreach ($items as $item) {
            $grandTotal += $item->line_total;
        }

        $shopId = isset($items[0]->shop_id) ? $items[0]->shop_id : null;

        // Apply discount if eligible
        $discountModel = new \App\Models\DiscountSettingsModel();
        $discount = $discountModel
            ->where('enabled', 1)
            ->where('min_shopping_amount <=', $grandTotal)
            ->where('maximum_shopping_amount >=', $grandTotal)
            ->orderBy('discount_amount', 'DESC')
            ->first();

        $discountAmount = $discount['discount_amount'] ?? 0;
        $orderAmount = $grandTotal - $discountAmount;
        $balance = $amountPaid - $orderAmount;

        // If payment is Mpesa, trigger STK Push and return modal wait view
        if ($paymentMethod === 'Mpesa') {
            $phone = $this->request->getPost('mpesa_phone');
            $res = $this->sendStkPush($phone, $orderAmount);
            $data += [
                'stk_res' => $res,
                'phone'   => $phone,
                'amount'  => $orderAmount
            ];
            return view('items/mpesa_wait', $data);
        }

        $customer_id = $this->request->getPost('customer_id') ?: null; // allow null
      // Update each sales item
        foreach ($items as $item) {
            $vatableAmount = ($item->vatable == 1) ? $item->line_total : 0;
            $vatRate = $item->vat_perc ?? 0;
            $vatCode = $item->vat_code ?? '';
            $vatAmount = $vatableAmount * ($vatRate / 100);
            $proportionalDiscount = ($grandTotal > 0) ? ($discountAmount * ($item->line_total / $grandTotal)) : 0;

            $db->table('sales')
                ->where('id', $item->sales_id)
                ->update([
                    'cashier_id'     => $cashierId,
                    'vatable_amnt'   => $vatableAmount,
                    'vat_rate'       => $vatRate,
                    'vat_code'       => $vatCode,
                    'vat_amnt'       => $vatAmount,
                    'total_discount' => $proportionalDiscount,
                    'total_cost'     => $item->line_total,
                    'customer_id'     => $customer_id

                ]);
        }

        // Update sales order
        $db->table('sales_orders')
            ->where('id', $order->id)
            ->update([
                'status'               => 'closed',
                'closed_at'            => date('Y-m-d H:i:s'),
                'paid_amount'           => $amountPaid,
                'order_amount'          => $orderAmount,
                'balance'              => $balance,
                'payment_method'       => $paymentMethod,
                'discount_id'          => $discount['id'] ?? null,
                'cashier_id'           => $cashierId,
                'shop_id'              => $shopId,
                'created_by_user_name' => $userName,
                'customer_id'     => $customer_id
            ]);

       return redirect()->to(site_url("salesorder/receipt/{$order->id}"));
      // return view('items/receipt_redirect', ['orderId' => $order->id]);
    }


    public function remove_item()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $itemId = $this->request->getPost('item_id');

        if ($itemId) {
            $salesModel = new \App\Models\SalesOrderModel(); // Sales table model
            $itemModel = new \App\Models\ItemModel(); // Items table model

            // Get the sale record before deleting
            $saleItem = $salesModel->find($itemId);

            if ($saleItem) {
         // Restore the item quantity
         $itemModel->where('id', $saleItem['item_id'])
        ->set('quantity', 'quantity + ' . (int)$saleItem['quantity'], false)
        ->update();


                // Now delete the sale record
                $salesModel->delete($itemId);

                return redirect()->back()->with('message', 'Item removed and stock restored.');
            }
        }

        return redirect()->back()->with('error', 'Failed to remove item.');
    }


    public function history()
	{
		$session = session();
		if (!$session->get('logged_in')) {
			return redirect()->to('login');
		}
		  $session = session();
		  $userModel = new UserModel();
		  $headerModel = new HeaderModel();
		  $userId = $session->get('user_id');
		  $user = $userModel->find($userId);

		$filters = [
			'from' => $this->request->getGet('from'),
			'to' => $this->request->getGet('to'),
			'item' => $this->request->getGet('item'),
			'cashier' => $this->request->getGet('cashier'),
      'customer' => $this->request->getGet('customer'), // ✅ new
		];


		$salesOrderModel = new SalesOrderModel();

		// Get paginated sales orders based on filters
		$salesOrders = $salesOrderModel->getSalesOrderHistory($filters);

		$data = [
			  'username'      => $session->get('username'),
			  'name'          => $session->get('name'),
			  'signature_link'=> $session->get('signature_link'),
			  'email'         => $session->get('email'),
			  'mobile'        => $session->get('mobile'),
			  'profile_image' => $session->get('profile_image'),
			  'header_links'  => $headerModel->getHeaderLinksByUser($user['user_id']),
			  'salesOrders'   => $salesOrders,
			  'filters'       => $filters,
		];

		return view('sales/history', $data);
	}


  public function orderPdf($orderId)
  {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }

      $salesOrderModel = new SalesOrderModel();
      $data = $salesOrderModel->getOrderWithDetails($orderId);

      if (!$data['order']) {
          return redirect()->back()->with('error', 'Order not found.');
      }

      $company = \Config\Database::connect()->table('companies')->get()->getRow();

      $html = view('sales/order_pdf', [
          'company' => $company,
          'order' => $data['order'],
          'items' => $data['items']
      ]);

      $options = new \Dompdf\Options();
      $options->set('isRemoteEnabled', true);
      $dompdf = new \Dompdf\Dompdf($options);
      $dompdf->loadHtml($html);
      $dompdf->setPaper('A4', 'portrait');
      $dompdf->render();

      if (ob_get_length()) {
          ob_end_clean();
      }

      $dompdf->stream("order_{$orderId}.pdf", ['Attachment' => false]);
      exit;
  }


	public function receipt($orderId)
	{
		$session = session();
		if (!$session->get('logged_in')) {
			return redirect()->to('login');
		}

		$db = \Config\Database::connect();

		$items = $db->query("
			SELECT s.*, i.name AS item_name, vs.vat_perc, vs.vat_code
			FROM sales s
			JOIN items i ON i.id = s.item_id
			LEFT JOIN vat_settings vs ON i.vat_id = vs.id
			WHERE s.sales_order_id = ?
		", [$orderId])->getResult();

		$company = $db->table('companies')->get()->getRow();

		$order = $db->table('sales_orders as so')
			->select('so.*, ds.discount_name, ds.discount_amount')
			->join('discount_settings ds', 'so.discount_id = ds.id', 'left')
			->where('so.id', $orderId)
			->get()
			->getRow();

		$appliedDiscount = null;
		if (!empty($order->discount_name) && !empty($order->discount_amount)) {
			$appliedDiscount = [
				'discount_name' => $order->discount_name,
				'discount_amount' => $order->discount_amount,
			];
		}

		$orderAmount = (float) $order->order_amount;
		$amountPaid = (float) $order->paid_amount;
		$change = $amountPaid - $orderAmount;

		$pay_method = $order->payment_method;


		$html = view('items/receipt_pdf', [
			'items' => $items,
			'company' => $company,
			'order' => $order,
			'appliedDiscount' => $appliedDiscount,
			'amountPaid' => $amountPaid,
			'bchange' => $change,
		]);

		$options = new \Dompdf\Options();
		$options->set('isRemoteEnabled', true);
		$dompdf = new \Dompdf\Dompdf($options);
		$dompdf->loadHtml($html);

		$dompdf->setPaper([0, 0, 164.41, 1000], 'portrait');
		$dompdf->render();

		if (ob_get_length()) {
			ob_end_clean();
		}

		$dompdf->stream("receipt_{$orderId}.pdf", ['Attachment' => false]);
		exit;
	}

}
