<?php

namespace App\Models;

use CodeIgniter\Model;

class PermissionModel extends Model
{
    // The name of the table associated with the model
    protected $table = 'permissions';

    // Primary key of the table
    protected $primaryKey = 'id';

    // Allowed fields for mass assignment
    protected $allowedFields = ['name'];

    // Automatically handle created_at and updated_at timestamps
    protected $useTimestamps = true;

    // Validation rules for the model
    protected $validationRules = [
        'name' => 'required|is_unique[permissions.name]',
    ];

    // Validation messages
    protected $validationMessages = [
        'name' => [
            'required' => 'The permission name is required.',
            'is_unique' => 'The permission name must be unique.',
        ],
    ];

    // Function to get all permissions
    public function getPermissions()
    {
        return $this->findAll();
    }

    // Function to get a specific permission by its ID
    public function getPermission($id)
    {
        return $this->find($id);
    }
}
