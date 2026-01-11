<?php namespace App\Models;

use CodeIgniter\Model;

class UserNotificationModel extends Model
{
    protected $table = 'user_notifications';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'user_id',
        'new_user_registration',
        'low_stock',
        'new_user_registration_sms',
        'low_stock_sms',
        'item_sale',
        'item_sale_sms',
    ];
}
