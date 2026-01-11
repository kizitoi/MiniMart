<?php

namespace App\Models;

use CodeIgniter\Model;

class NumberingModel extends Model
{
    protected $table = 'numbering';
    protected $primaryKey = 'id';
    protected $allowedFields = ['apply_to', 'prefix', 'last_used'];
}
