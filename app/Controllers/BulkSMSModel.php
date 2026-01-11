<?php namespace App\Models;

use CodeIgniter\Model;


class BulkSMSModel extends Model
{
    protected $table = 'bulksms_settings';
    protected $primaryKey = 'id';
    protected $allowedFields = ['username', 'password', 'api_url'];
    protected $useTimestamps = true;
}
