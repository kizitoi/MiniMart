<?php
namespace App\Controllers;
use App\Libraries\UserDataHelper;
use App\Models\PermissionsModel;

class Permissions extends BaseController
{
    protected $model;
    protected $helper;

    public function __construct()
    {
        $this->model = new PermissionsModel();
        $this->helper = new UserDataHelper();
    }

    // List with search + pagination
    public function index()
    {
        $sessionData = $this->helper->load();
        $search = $this->request->getGet('search');
        $perPage = 10;
        $currentPage = $this->request->getGet('page') ?? 1;

        $query = $this->model;
        if ($search) {
            $query = $query->like('name', $search)->orLike('label', $search);
        }
$data = [
    'permissions' => $query->paginate($perPage, 'permissions'),
    'pager'       => $this->model->pager,
    'search'      => $search,
] + $sessionData;


      /*  $data = [
            'permissions' => $query->paginate($perPage, 'group1', $currentPage),
            'pager'       => $this->model->pager,
            'search'      => $search,
        ] + $sessionData;*/

        return view('permissions/index', $data);
    }

    public function add()
    {
        $data = $this->helper->load();
        return view('permissions/add', $data);
    }

    public function store()
    {
        $this->model->save([
            'name'     => $this->request->getPost('name'),
            'label'    => $this->request->getPost('label'),
            'link'     => $this->request->getPost('link'),
            'icon'     => $this->request->getPost('icon'),
            'isbutton' => $this->request->getPost('isbutton') ? 1 : 0,
        ]);

        return redirect()->to('permissions')->with('success', 'Permission added.');
    }

    public function edit($id)
    {
        $perm = $this->model->find($id);
        $data = $this->helper->load() + ['perm' => $perm];
        return view('permissions/edit', $data);
    }

    public function update($id)
    {
        $this->model->update($id, [
            'name'     => $this->request->getPost('name'),
            'label'    => $this->request->getPost('label'),
            'link'     => $this->request->getPost('link'),
            'icon'     => $this->request->getPost('icon'),
            'isbutton' => $this->request->getPost('isbutton') ? 1 : 0,
        ]);

        return redirect()->to('permissions')->with('success', 'Permission updated.');
    }

    public function delete($id)
    {
        $this->model->delete($id);
        return redirect()->to('permissions')->with('success', 'Permission deleted.');
    }
}


/*
class Permissions extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('RolePermission_model');
    }

    public function index() {

      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
      $userModel = new \App\Models\UserModel();
      $headerModel = new \App\Models\HeaderModel();
      $userId = session()->get('user_id');
      $user = $userModel->find($userId);

        $data = [

            'username' => $session->get('username'),
            'name' => $session->get('name'),
            'signature_link' => $session->get('signature_link'),
            'email' => $session->get('email'),
            'mobile' => $session->get('mobile'),
            'profile_image' => $session->get('profile_image'),
            'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
            'roles' => $this->RolePermission_model->get_roles(),

        ];

        $this->load->view('permissions_view', $data);
    }

    public function get_permissions_for_role($role_id) {
        $permissions = $this->RolePermission_model->get_permissions();
        $role_permissions = $this->RolePermission_model->get_role_permissions($role_id);

        $rp_map = [];
        foreach ($role_permissions as $rp) {
            $rp_map[$rp->permission_id] = $rp;
        }

        echo json_encode([
            'permissions' => $permissions,
            'role_permissions' => $rp_map
        ]);
    }

    public function getPermissions($roleId)
      {      $session = session();
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

    public function update_permission() {

      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
        $role_id = $this->input->post('role_id');
        $permission_id = $this->input->post('permission_id');
        $can_view = $this->input->post('can_view') == "true";
        $can_add = $this->input->post('can_add') == "true";
        $can_edit = $this->input->post('can_edit') == "true";
        $can_delete = $this->input->post('can_delete') == "true";

        $this->RolePermission_model->update_permission($role_id, $permission_id, $can_view, $can_add, $can_edit, $can_delete);

        echo json_encode(['status' => 'success']);
    }
}
*/
