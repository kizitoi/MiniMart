<?php

namespace App\Models;

use CodeIgniter\Model;

class CustomerAccountModel extends Model
{
    protected $table = 'customer_accounts';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'customer_id',
        'type',
        'description_id',
        'payment_method_id',
        'amount',
        'balance',
        'created_by_user_id',
        'created_at',
    ];

    protected $useTimestamps = false;

    public function getDetailedAccountsPaginated($customer_id, $perPage = 10)
    {
        return $this->select('customer_accounts.*, pd.description, pm.method, u.username')
            ->join('payment_descriptions pd', 'pd.id = customer_accounts.description_id', 'left')
            ->join('payment_methods pm', 'pm.id = customer_accounts.payment_method_id', 'left')
            ->join('users u', 'u.user_id = customer_accounts.created_by_user_id', 'left')
            ->where('customer_accounts.customer_id', $customer_id)
            ->orderBy('customer_accounts.created_at', 'DESC')
            ->paginate($perPage);
    }
}


/*
namespace App\Models;

use CodeIgniter\Model;

class CustomerAccountModel extends Model
{
    protected $table = 'customer_accounts';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'customer_id',
        'type', // credit or debit
        'description_id',
        'payment_method_id',
        'amount',
        'balance',
        'created_by_user_id',
        'created_at',
    ];

    protected $useTimestamps = false; // We're using CURRENT_TIMESTAMP in DB

    public function getDetailedAccounts($customer_id)
    {
        return $this->select('customer_accounts.*, pd.description, pm.method, u.username')
            ->join('payment_descriptions pd', 'pd.id = customer_accounts.description_id')
            ->join('payment_methods pm', 'pm.id = customer_accounts.payment_method_id')
            ->join('users u', 'u.user_id = customer_accounts.created_by_user_id')
            ->where('customer_accounts.customer_id', $customer_id)
            ->orderBy('customer_accounts.created_at', 'DESC')
            ->findAll();
    }


}
*/
