<?php

namespace App\Models;

use CodeIgniter\Model;

class ShopModel extends Model
{
    protected $table = 'shops';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name'];
}
