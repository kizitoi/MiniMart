<?php

namespace App\Models;
use CodeIgniter\Model;

class PermissionsModel extends Model
{
    protected $table = 'permissions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'label', 'link', 'icon', 'isbutton'];
    protected $useTimestamps = true;
}
