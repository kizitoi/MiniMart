<?php namespace App\Models;
use CodeIgniter\Model;

class MpesaTransactionsModel extends Model {
    protected $table = 'mpesa_transactions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'MerchantRequestID','CheckoutRequestID','ResultCode','ResultDesc',
        'Amount','MpesaReceiptNumber','Balance','TransactionDate','PhoneNumber'
    ];
    protected $useTimestamps = true;
}
