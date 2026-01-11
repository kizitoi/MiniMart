<?php

namespace App\Models;

use CodeIgniter\Model;

class PaymentDescriptionModel extends Model
{
    protected $table = 'payment_descriptions';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'description',
    ];

    protected $useTimestamps = false;

    /**
     * Check if a payment description is already in use in the customer_accounts table.
     *
     * @param int $id
     * @return bool
     */
    public function isInUse($id): bool
    {
        $db = \Config\Database::connect();
        $count = $db->table('customer_accounts')
            ->where('description_id', $id)
            ->countAllResults();

        return $count > 0;
    }
}
