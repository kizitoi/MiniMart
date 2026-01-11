<?php

namespace App\Models;

use CodeIgniter\Model;

class DepartmentModel extends Model
{
    protected $table = 'departments';
    protected $primaryKey = 'id';
    protected $allowedFields = ['name', 'description', 'created_at', 'updated_at'];

    public function isAssignedToIncident($department_id)
    {
        $db = \Config\Database::connect();
        $query = $db->table('incidents')->where('department_id', $department_id)->countAllResults();
        return $query > 0;
    }
}
