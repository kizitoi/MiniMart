<?php

namespace App\Controllers;

use App\Models\RoleModel;
use App\Models\PermissionModel;
use App\Models\RolePermissionModel;
use CodeIgniter\Controller;

class RolePermissionController extends Controller
{
    // Show the role permission matrix
    public function index()
    {
        $roleModel = new RoleModel();
        $permissionModel = new PermissionModel();

        // Get all roles and permissions
        $roles = $roleModel->findAll();
        $permissions = $permissionModel->findAll();

        // Get the current logged-in user session data
        $session = session();
        $data = [
            'username' => $session->get('username'),
            'name' => $session->get('name'),
            'signature_link' => $session->get('signature_link'),
            'email' => $session->get('email'),
            'mobile' => $session->get('mobile'),
            'profile_image' => $session->get('profile_image'),
            'roles' => $roles,
            'permissions' => $permissions,
        ];

        return view('role_permissions/index', $data);
    }

    // Save the role permissions
    public function save()
    {
        $rolePermissionModel = new RolePermissionModel();
        $roleId = $this->request->getPost('role_id');
        $permissions = $this->request->getPost('permissions');

        // Delete previous permissions for the role
        $rolePermissionModel->where('role_id', $roleId)->delete();

        // Add the selected permissions
        if ($permissions) {
            foreach ($permissions as $permissionId) {
                $rolePermissionModel->insert([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                ]);
            }
        }

        // Redirect back with success message
        return redirect()->to('/role_permissions')->with('success', 'Permissions updated successfully');
    }
}
