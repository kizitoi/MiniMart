<?php
// app/Controllers/SalesOrderController.php
namespace App\Controllers;

use Dompdf\Dompdf;
use App\Models\UserModel;
use App\Models\HeaderModel;
use App\Models\SalesOrderModel;
use App\Libraries\UserDataHelper;




class SalesOrderController extends BaseController
{
  protected function loadUserData(): array
  {   $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
      $session = session();
      $userModel = new UserModel();
      $headerModel = new HeaderModel();
      $userId = $session->get('user_id');
      $user = $userModel->find($userId);

      return [
          'username' => $session->get('username'),
          'name' => $session->get('name'),
          'signature_link' => $session->get('signature_link'),
          'email' => $session->get('email'),
          'mobile' => $session->get('mobile'),
          'profile_image' => $session->get('profile_image'),
          'header_links' => $headerModel->getHeaderLinksByUser($userId),
      ];
  }

    public function current()
    {   $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $userId = session()->get('user_id');
        $db = \Config\Database::connect();

        $order = $db->table('sales_orders')
            ->where('created_by_user_id', $userId)
            ->where('status', 'new')
            ->get()
            ->getRow();

        if (!$order) {
            return view('sales_order', ['order' => null, 'items' => []]);
        }

        $items = $db->query("
            SELECT s.*, i.name as item_name, vs.vat_perc, vs.vat_code as vat_code
            FROM sales s
            JOIN items i ON i.id = s.item_id
            LEFT JOIN vat_settings vs ON i.vat_id = vs.id
            WHERE s.sales_order_id = ?
        ", [$order->id])->getResult();

        $company = $db->table('companies')->get()->getRow();



        $data = $this->loadUserData() + [
            'order' => $order,
            'items' => $items,
             'company' => $company,
            'category_name' => $category['category_name'] ?? 'Selected Category',
        ];


  return view('items/sales_order', $data);

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
  {   $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
      $session = session();
      $userModel = new UserModel();
      $headerModel = new HeaderModel();
      $userId = $session->get('user_id');
      $user = $userModel->find($userId);

      // Get date filters from GET request, or set empty strings if not provided
    /*  $filters = [
          'from' => $this->request->getGet('from') ?? '',
          'to'   => $this->request->getGet('to') ?? ''
      ];*/


      $filters = [
    'from' => $this->request->getGet('from'),
    'to' => $this->request->getGet('to'),
    'item' => $this->request->getGet('item'),
    'cashier' => $this->request->getGet('cashier'),
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


    public function close()
    {   $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $db = \Config\Database::connect();
        $userId = session()->get('user_id');

        $order = $db->table('sales_orders')
            ->where('created_by_user_id', $userId)
            ->where('status', 'new')
            ->get()->getRow();

        if (!$order) {
            return redirect()->back()->with('error', 'No active order.');
        }

        $db->table('sales_orders')
            ->where('id', $order->id)
            ->update(['status' => 'closed', 'closed_at' => date('Y-m-d H:i:s')]);

        return redirect()->to(site_url("salesorder/receipt/{$order->id}"));
    }


    public function receipt($orderId)
    {   $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        // Connect to the database
        $db = \Config\Database::connect();

        // Fetch items with VAT details
        $items = $db->query("
            SELECT s.*, i.name AS item_name, vs.vat_perc, vs.vat_code
            FROM sales s
            JOIN items i ON i.id = s.item_id
            LEFT JOIN vat_settings vs ON i.vat_id = vs.id
            WHERE s.sales_order_id = ?
        ", [$orderId])->getResult();

        // Fetch company and order details
        $company = $db->table('companies')->get()->getRow();
        $order = $db->table('sales_orders')->where('id', $orderId)->get()->getRow();

        // Load view as HTML
        $html = view('items/receipt_pdf', [
            'items' => $items,
            'company' => $company,
            'order' => $order,
        ]);

        // Initialize Dompdf
      //  $dompdf = new Dompdf();
      //  $dompdf->loadHtml($html);

        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);


        // Set custom thermal printer paper size (80mm width)
        $dompdf->setPaper([0, 0, 226.77, 1000], 'portrait');

        // Set custom thermal printer paper size (58mm width)
       // $dompdf->setPaper([0, 0, 164.41, 1000], 'portrait');

        // Render the PDF
        $dompdf->render();

        // Clean any prior output to avoid PDF corruption
        if (ob_get_length()) {
            ob_end_clean();
        }

        // Output the PDF to browser
        $dompdf->stream("receipt_{$orderId}.pdf", ['Attachment' => false]);
        exit;
    }

}
?>
