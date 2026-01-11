<?php namespace App\Models;

use CodeIgniter\Model;

class SalesOrderModel extends Model
{
    protected $table = 'sales';
    protected $primaryKey = 'id';


       public function getSalesOrderHistory(array $filters = [])
       {
           $builder = $this->db->table('sales s')
               ->select('s.*, i.name as item_name, u.username as cashier, s.created_at, so.payment_method,
                         c.name as customer_name, c.email as customer_email, c.phone as customer_phone, c.address as customer_address')
               ->join('items i', 'i.id = s.item_id')
               ->join('users u', 'u.user_id = s.user_id')
               ->join('sales_orders so', 'so.id = s.sales_order_id')
               ->join('customers c', 'c.customer_id = so.customer_id', 'left');

           if (!empty($filters['customer'])) {
               $builder->like('c.name', $filters['customer']);
           }
           if (!empty($filters['from'])) {
               $builder->where('s.created_at >=', $filters['from']);
           }
           if (!empty($filters['to'])) {
               $builder->where('s.created_at <=', $filters['to'] . ' 23:59:59');
           }
           if (!empty($filters['item'])) {
               $builder->like('i.name', $filters['item']);
           }
           if (!empty($filters['cashier'])) {
               $builder->like('u.username', $filters['cashier']);
           }

           $sales = $builder->orderBy('s.created_at', 'DESC')->get()->getResult();

           $grouped = [];
           foreach ($sales as $sale) {
               $grouped[$sale->sales_order_id][] = $sale;
           }
           return $grouped;
       }

       /**
        * Get full order with items + customer details for PDF
        */
       public function getOrderWithDetails($orderId)
       {
           $db = \Config\Database::connect();

           $order = $db->table('sales_orders so')
               ->select('so.*, c.name as customer_name, c.email as customer_email, c.phone as customer_phone, c.address as customer_address')
               ->join('customers c', 'c.customer_id = so.customer_id', 'left')
               ->where('so.id', $orderId)
               ->get()
               ->getRow();

           $items = $db->table('sales s')
               ->select('s.*, i.name as item_name, u.username as cashier')
               ->join('items i', 'i.id = s.item_id')
               ->join('users u', 'u.user_id = s.user_id')
               ->where('s.sales_order_id', $orderId)
               ->get()
               ->getResult();

           return ['order' => $order, 'items' => $items];
       }

/*
	public function getSalesOrderHistory(array $filters = [])
	{
    $builder = $this->db->table('sales s')
        ->select('s.*, i.name as item_name, u.username as cashier, s.created_at, so.payment_method,
                  c.name as customer_name, c.email as customer_email, c.phone as customer_phone, c.address as customer_address')
        ->join('items i', 'i.id = s.item_id')
        ->join('users u', 'u.user_id = s.user_id')
        ->join('sales_orders so', 'so.id = s.sales_order_id')
        ->join('customers c', 'c.customer_id = so.customer_id', 'left'); // left join in case order has no customer


     if (!empty($filters['customer'])) {
            $builder->like('c.name', $filters['customer']);
     }

		// Apply filters
		if (!empty($filters['from'])) {
			$builder->where('s.created_at >=', $filters['from']);
		}
		if (!empty($filters['to'])) {
			$builder->where('s.created_at <=', $filters['to'] . ' 23:59:59');
		}
		if (!empty($filters['item'])) {
			$builder->like('i.name', $filters['item']);
		}
		if (!empty($filters['cashier'])) {
			$builder->like('u.username', $filters['cashier']);
		}

		$sales = $builder->orderBy('s.created_at', 'DESC')->get()->getResult();

		// Group by order ID
		$grouped = [];
		foreach ($sales as $sale) {
			$grouped[$sale->sales_order_id][] = $sale;
		}

		return $grouped;
	}
*/
}
