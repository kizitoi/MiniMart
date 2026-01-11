<?php namespace App\Models;

use CodeIgniter\Model;

class TownModel extends Model
{
    protected $table = 'towns';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name'];
}
