<?php

namespace App\Models;

use CodeIgniter\Model;

class UnitOfMeasureModel extends Model
{
    protected $table = 'unit_of_measure';
    protected $primaryKey = 'id';
    protected $allowedFields = ['unit_name'];
    protected $useTimestamps = true;
}
