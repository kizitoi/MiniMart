<?php namespace App\Models;

use CodeIgniter\Model;

class CompanyModel extends Model
{
    protected $table = 'companies';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'name', 'branch_id', 'address', 'town_id', 'county_id', 'country_id',
        'phone', 'email', 'website', 'logo'
    ];
}
