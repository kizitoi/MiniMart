<?php namespace App\Models;

use CodeIgniter\Model;

class DiscountSettingsModel extends Model
{
    protected $table = 'discount_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'discount_name', 'discount_amount', 'min_shopping_amount', 'maximum_shopping_amount', 'enabled'
    ];
    protected $useTimestamps = true;
}
