<?php

namespace App\Controllers;

use App\Models\RoleModel;
use CodeIgniter\Controller;
use App\Libraries\UserDataHelper;


class Roles extends Controller
{
    public function index()
    {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $roleModel = new RoleModel();

    $userModel = new \App\Models\UserModel();
        $headerModel = new \App\Models\HeaderModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);
        //'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),



        $data = [
            'roles' => $roleModel->findAll(),
            'username' => $session->get('username'),
            'name' => $session->get('name'),
            'signature_link' => $session->get('signature_link'),
            'email' => $session->get('email'),
            'mobile' => $session->get('mobile'),
            'profile_image' => $session->get('profile_image'),
            'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
        ];
        return view('roles/index', $data);
    }

    public function create()
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
            'username' => $session->get('username'),
            'name' => $session->get('name'),
            'signature_link' => $session->get('signature_link'),
            'email' => $session->get('email'),
            'mobile' => $session->get('mobile'),
            'profile_image' => $session->get('profile_image'),
            'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
        ];
        return view('roles/create', $data);
    }

    /*public function store()
    {
        $roleModel = new RoleModel();
        $roleModel->save(['name' => $this->request->getPost('name')]);

        return redirect()->to('/roles')->with('success', 'Role created successfully');
    }*/

    public function edit($id)
    {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $roleModel = new RoleModel();
        $role = $roleModel->find($id);


        $userModel = new \App\Models\UserModel();
            $headerModel = new \App\Models\HeaderModel();
            $userId = session()->get('user_id');
            $user = $userModel->find($userId);
            //'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),



        if (!$role) {
            return redirect()->to('/roles')->with('error', 'Role not found.');
        }

        $data = [
            'role' => $role,
            'username' => $session->get('username'),
            'name' => $session->get('name'),
            'signature_link' => $session->get('signature_link'),
            'email' => $session->get('email'),
            'mobile' => $session->get('mobile'),
            'profile_image' => $session->get('profile_image'),
            'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
        ];
        return view('roles/edit', $data);
    }

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
                'can_edit' => $rp['can_edit'],
                'can_delete' => $rp['can_delete']
            ];
        }

        return $this->response->setJSON([
            'permissions' => $permissions,
            'assigned' => $assigned
        ]);
    }



    public function delete($id)
    {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }

        $roleModel = new RoleModel();
        $roleModel->delete($id);

        return redirect()->to('/roles')->with('success', 'Role deleted successfully');
    }




    public function store()
{
  $session = session();
  if (!$session->get('logged_in')) {
      return redirect()->to('login');
  }

  $roleModel = new RoleModel();
    $name = $this->request->getPost('name');

    // Check if role name already exists
    if ($roleModel->where('name', $name)->first()) {
        return redirect()->back()->withInput()->with('error', 'Role name already exists. Please choose a different name.');
    }

    $roleModel->save(['name' => $name]);

    return redirect()->to('/roles')->with('success', 'Role created successfully');
}

public function update($id)
{   $session = session();
  if (!$session->get('logged_in')) {
      return redirect()->to('login');
  }
    $roleModel = new RoleModel();
    $name = $this->request->getPost('name');

    // Check if another role (not the one being updated) has the same name
    $existing = $roleModel->where('name', $name)->where('id !=', $id)->first();
    if ($existing) {
        return redirect()->back()->withInput()->with('error', 'Role name already exists. Please choose a different name.');
    }

    $roleModel->update($id, ['name' => $name]);

    return redirect()->to('/roles')->with('success', 'Role updated successfully');
}

}
