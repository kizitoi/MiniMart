<?php

namespace App\Models;
use CodeIgniter\Model;

class SystemDocumentationModel extends Model
{
    protected $table = 'system_documentation';
    protected $primaryKey = 'id';
    protected $allowedFields = ['title', 'file_name'];
    protected $useTimestamps = true;
}
