<?php

namespace App\Models;

use CodeIgniter\Model;

class RolePermissionModel extends Model
{
    protected $table = 'role_permissions';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'role_id',
        'permission_id',
        'can_view',
        'can_add',
        'can_edit',
        'can_delete'
    ];

    /**
     * Get permissions assigned to a role with access levels
     */
    public function getPermissionsByRole($roleId)
    {
        return $this->select('role_permissions.*, permissions.name AS permission_name')
                    ->join('permissions', 'permissions.id = role_permissions.permission_id')
                    ->where('role_id', $roleId)
                    ->findAll();
    }

    /**
     * Save or update permissions for a role
     */
    public function savePermissions($roleId, $permissions)
    {
        // First, delete all existing permissions for this role
        $this->where('role_id', $roleId)->delete();

        // Insert updated permissions
        $data = [];

        foreach ($permissions as $permissionId => $access) {
            $data[] = [
                'role_id'      => $roleId,
                'permission_id'=> $permissionId,
                'can_view'     => isset($access['can_view']) ? 1 : 0,
                'can_add'  => isset($access['can_add']) ? 1 : 0,
                'can_edit'     => isset($access['can_edit']) ? 1 : 0,
                'can_delete'   => isset($access['can_delete']) ? 1 : 0,
            ];
        }

        return $this->insertBatch($data);
    }

    /**
     * Get a mapping of permission_id => access flags for a role
     */
    public function getRolePermissionMap($roleId)
    {
        $results = $this->where('role_id', $roleId)->findAll();
        $map = [];

        foreach ($results as $row) {
            $map[$row['permission_id']] = [
                'can_view' => (bool) $row['can_view'],
                'can_add' => (bool) $row['can_add'],
                'can_edit' => (bool) $row['can_edit'],
                'can_delete' => (bool) $row['can_delete'],
            ];
        }

        return $map;
    }
}
