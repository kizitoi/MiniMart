<?php

namespace App\Models;

use CodeIgniter\Model;

class ItemCheckInModel extends Model
{
    protected $table = 'item_check_in';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'item_id', 'supplier_id', 'check_in_quantity', 'date', 'time',
        'unit_price', 'total_price', 'user_id', 'user_name'
    ];
}
