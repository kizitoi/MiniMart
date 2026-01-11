<?php
namespace App\Controllers;

use App\Models\RoleModel;
use App\Models\PermissionModel;
use App\Models\RolePermissionModel;
use CodeIgniter\Controller;
use App\Libraries\UserDataHelper;


class RolePermissionController extends Controller
{
    // Constructor to load the models
    public function __construct()
    {
        $this->roleModel = new RoleModel();
        $this->permissionModel = new PermissionModel();
        $this->rolePermissionModel = new RolePermissionModel();
    }

    // Display role permissions index page
    public function index($roleId = null)
    {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }

    $userModel = new \App\Models\UserModel();
        $headerModel = new \App\Models\HeaderModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);
        //'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),


        $data = [
            'roles' => $this->roleModel->findAll(),
            'selectedRoleId' => $roleId,
            'permissions' => $this->permissionModel->findAll(),
            'rolePermissions' => [],
            'username' => $session->get('username'),
            'name' => $session->get('name'),
            'signature_link' => $session->get('signature_link'),
            'email' => $session->get('email'),
            'mobile' => $session->get('mobile'),
            'profile_image' => $session->get('profile_image'),
            'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
        ];

        if ($roleId) {
            $permissions = $this->rolePermissionModel->where('role_id', $roleId)->findAll();
            foreach ($permissions as $perm) {
                $data['rolePermissions'][$perm['permission_id']] = $perm;
            }
        }

        return view('role_permissions/index', $data);
    }

    // Method to get permissions for a specific role via AJAX
    public function getPermissions($roleId)
    {   $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $permissions = $this->permissionModel->findAll();
        $role_permissions = $this->rolePermissionModel->where('role_id', $roleId)->findAll();

        $assigned = [];
        foreach ($role_permissions as $rp) {
            $assigned[$rp['permission_id']] = [
                'can_view' => $rp['can_view'],
                'can_add' => $rp['can_add'],
                'can_edit' => $rp['can_edit'],
                'can_delete' => $rp['can_delete']
            ];
        }

        return $this->response->setJSON([
            'permissions' => $permissions,
            'assigned' => $assigned
        ]);
    }

    // Method to update a single permission
    public function updatePermission()
    {   $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $rolePermissionModel = new RolePermissionModel();
        $data = $this->request->getPost();

        $existing = $rolePermissionModel->where('role_id', $data['role_id'])
            ->where('permission_id', $data['permission_id'])
            ->first();

        $fields = [
            'role_id' => $data['role_id'],
            'permission_id' => $data['permission_id'],
            'can_view' => $data['can_view'] ?? 0,
            'can_add' => $data['can_add'] ?? 0,
            'can_edit' => $data['can_edit'] ?? 0,
            'can_delete' => $data['can_delete'] ?? 0,
        ];

        if ($existing) {
            $rolePermissionModel->update($existing['id'], $fields);
        } else {
            $rolePermissionModel->insert($fields);
        }

        return $this->response->setJSON(['status' => 'success']);
    }



    public function updatePermissions()
    {   $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $rolePermissionModel = new \App\Models\RolePermissionModel();
        $roleId = $this->request->getPost('role_id');
        $permissions = $this->request->getPost('permissions');

        if (!$roleId || !is_array($permissions)) {
            return redirect()->back()->with('error', 'Invalid role or permissions data.');
        }

        // Save permissions using the model's savePermissions method
        $rolePermissionModel->savePermissions($roleId, $permissions);

        return redirect()->back()->with('success', 'Permissions updated successfully.');
    }



    // Save the role permissions
    public function save()
    {   $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $rolePermissionModel = new RolePermissionModel();
        $roleId = $this->request->getPost('role_id');
        $permissions = $this->request->getPost('permissions');
        $canView = $this->request->getPost('can_view');
        $canAdd = $this->request->getPost('can_add');
        $canEdit = $this->request->getPost('can_edit');
        $canDelete = $this->request->getPost('can_delete');

        // Delete previous permissions for the role
        $rolePermissionModel->where('role_id', $roleId)->delete();

        // Add the selected permissions
        if ($permissions) {
            foreach ($permissions as $permissionId) {
                $rolePermissionModel->insert([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId,
                    'can_view' => isset($canView[$permissionId]) ? true : false,
                    'can_add' => isset($canAdd[$permissionId]) ? true : false,
                    'can_edit' => isset($canEdit[$permissionId]) ? true : false,
                    'can_delete' => isset($canDelete[$permissionId]) ? true : false,
                ]);
            }
        }

        // Redirect back with success message
        return redirect()->to('/role_permissions')->with('success', 'Permissions updated successfully');
    }
}
