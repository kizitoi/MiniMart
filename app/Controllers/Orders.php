<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Libraries\UserDataHelper;


class Orders extends BaseController
{

  protected $db;

      public function __construct()
      {
          $this->db = \Config\Database::connect();
      }

    public function create()
    {
      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
        $session = session();
        $userId = $session->get('user_id');
        $userName = $session->get('username');

        $existingOrder = $this->db->table('sales_orders')
            ->where('created_by_user_id', $userId)
            ->where('status', 'new')
            ->get()
            ->getRow();

        if ($existingOrder) {
            return redirect()->back()->with('warning', 'You already have an open order.');
        }

        $this->db->table('sales_orders')->insert([
            'date_time' => date('Y-m-d H:i:s'),
            'paid_amount' => 0,
            'order_amount' => 0,
            'balance' => 0,
            'shop_id' => null, // You can change this based on context
            'created_by_user_id' => $userId,
            'created_by_user_name' => $userName,
            'status' => 'new'
        ]);

      //  return redirect()->to('officer/officer');
      return redirect()->to('items/express_sale');

    }
}
?>
