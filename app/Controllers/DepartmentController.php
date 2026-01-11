<?php

namespace App\Controllers;

use App\Models\DepartmentModel;
use CodeIgniter\Controller;
use App\Libraries\UserDataHelper;

class DepartmentController extends Controller
{
    public function index()
    {

      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }


  $model = new DepartmentModel();
  $userModel = new \App\Models\UserModel();
  $headerModel = new \App\Models\HeaderModel();
  $userId = session()->get('user_id');
  $user = $userModel->find($userId);
  //'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
        $data = [
            'departments' => $model->findAll(),
          //  'pager' => $this->incidentModel->pager, // Load pagination links
        //    'searchQuery' => $searchQuery, // Retain search input
            'username' => $session->get('username'),
            'name' => $session->get('name'),
            'signature_link' => $session->get('signature_link'),
            'email' => $session->get('email'),
            'mobile' => $session->get('mobile'),
            'profile_image' => $session->get('profile_image'),
            'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
        ];

        return view('departments/index', $data);
    }

    public function create()
    {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }

      $model = new DepartmentModel();
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

        return view('departments/create', $data);
    }

    public function store()
    {
      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
        $model = new DepartmentModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description')
        ];

        $model->insert($data);

        return redirect()->to('/departments')->with('success', 'Department added successfully.');
    }


    public function edit($id)
    {


      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }

      $model = new DepartmentModel();
      $userModel = new \App\Models\UserModel();
      $headerModel = new \App\Models\HeaderModel();
      $userId = session()->get('user_id');
      $user = $userModel->find($userId);
      //'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
        $model = new DepartmentModel();
      //  $data['department'] = $model->find($id);
                $data = [
                    'department' => $model->find($id),
                    //'pager' => $this->incidentModel->pager, // Load pagination links
                  //  'searchQuery' => $searchQuery, // Retain search input
                    'username' => $session->get('username'),
                    'name' => $session->get('name'),
                    'signature_link' => $session->get('signature_link'),
                    'email' => $session->get('email'),
                    'mobile' => $session->get('mobile'),
                    'profile_image' => $session->get('profile_image'),
                    'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
                ];

        return view('departments/edit', $data);
    }

    public function update($id)
    {

      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
        $model = new DepartmentModel();

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description')
        ];

        $model->update($id, $data);

        return redirect()->to('/departments')->with('success', 'Department updated successfully.');
    }

    public function delete($id)
    {

      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
        $model = new DepartmentModel();

        // Check if department is assigned to an incident
        if ($model->isAssignedToIncident($id)) {
            return redirect()->to('/departments')->with('error', 'Cannot delete: Department is assigned to an incident.');
        }

        $model->delete($id);
        return redirect()->to('/departments')->with('success', 'Department deleted successfully.');
    }
}
