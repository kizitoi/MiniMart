<?php namespace App\Models;

use CodeIgniter\Model;

class CountyModel extends Model
{
    protected $table = 'counties';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name'];
}
