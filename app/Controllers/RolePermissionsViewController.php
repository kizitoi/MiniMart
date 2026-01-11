<?php

namespace App\Controllers;

use App\Models\RoleModel;
use App\Models\PermissionModel;
use App\Models\RolePermissionModel;
use CodeIgniter\Controller;
use App\Libraries\UserDataHelper;


class RolePermissionsViewController extends Controller
{
    // Constructor to load the models
    public function __construct()
    {
        $this->roleModel = new RoleModel();
        $this->permissionModel = new PermissionModel();
        $this->rolePermissionModel = new RolePermissionModel();
    }

    // Display all the permissions of the selected role
    public function index($roleId)
    {   $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        // Get session data
        $session = session();

        $userModel = new \App\Models\UserModel();
            $headerModel = new \App\Models\HeaderModel();
            $userId = session()->get('user_id');
            $user = $userModel->find($userId);
            //'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),


        // Fetch role data
        $role = $this->roleModel->find($roleId);
        if (!$role) {
            throw new \CodeIgniter\Exceptions\PageNotFoundException('Role not found');
        }

        // Fetch all permissions assigned to the role
        // Fetch all permissions assigned to the role
  $permissions = $this->rolePermissionModel->where('role_id', $roleId)->findAll();
  $permissionData = [];

  foreach ($permissions as $permission) {
    $perm = $this->permissionModel->find($permission['permission_id']);

    $permissionData[] = [
        'id' => $permission['id'],
        'permission_name' => $perm['name'] ?? 'Unknown / Deleted',
        'can_view' => $permission['can_view'] ?? 0,
        'can_add' => $permission['can_add'] ?? 0,
        'can_edit' => $permission['can_edit'] ?? 0,
        'can_delete' => $permission['can_delete'] ?? 0
    ];
  }



        // Prepare data to pass to the view, including session data
        $data = [
            'role' => $role,
            'permissions' => $permissionData,
            'username' => $session->get('username'),
            'name' => $session->get('name'),
            'signature_link' => $session->get('signature_link'),
            'email' => $session->get('email'),
            'mobile' => $session->get('mobile'),
            'profile_image' => $session->get('profile_image'),
            'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
        ];

        return view('role_permissions/view', $data);
    }

    // Update the permissions for a specific role
    public function updatePermissions()
    {   $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $rolePermissionModel = new RolePermissionModel();
        $data = $this->request->getPost();

        // Extract role ID and permissions from post data
        $roleId = $data['role_id'];
        $permissions = $data['permissions'];

        // Loop through permissions and update them
        foreach ($permissions as $permissionId => $permissionData) {
            $existing = $rolePermissionModel->where('role_id', $roleId)
                ->where('permission_id', $permissionId)
                ->first();

            $fields = [
                'role_id' => $roleId,
                'permission_id' => $permissionId,
                'can_view' => isset($permissionData['can_view']) ? 1 : 0,
                'can_add' => isset($permissionData['can_add']) ? 1 : 0,
                'can_edit' => isset($permissionData['can_edit']) ? 1 : 0,
                'can_delete' => isset($permissionData['can_delete']) ? 1 : 0,
            ];

            if ($existing) {
                $rolePermissionModel->update($existing['id'], $fields);
            } else {
                $rolePermissionModel->insert($fields);
            }
        }

        // Redirect back with a success message
        return redirect()->to('/role_permissions/view/' . $roleId)->with('success', 'Permissions updated successfully');
    }
}
