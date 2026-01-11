<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentMethodModel extends Model
{
    protected $table = 'payment_methods';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'method',
    ];

    protected $useTimestamps = false;

    /**
     * Check if a payment method is in use in the customer_accounts table.
     *
     * @param int $id
     * @return bool
     */
    public function isInUse($id): bool
    {
        $db = \Config\Database::connect();
        $count = $db->table('customer_accounts')
            ->where('payment_method_id', $id)
            ->countAllResults();

        return $count > 0;
    }
}
