<?php namespace App\Controllers;

use App\Models\NumberingSettingsModel;
use App\Models\HeaderModel;
use App\Models\UserModel;
use App\Libraries\UserDataHelper;


class NumberingSettings extends BaseController
{
    protected $numberingModel;
    protected $userModel;

    public function __construct()
    {
        $this->numberingModel = new NumberingSettingsModel();
        $this->userModel = new UserModel();
    }

    private function loadUserData()
    {       $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $session = session();
        $userId = $session->get('user_id');
        $user = $this->userModel->find($userId);
        $headerModel = new HeaderModel();

        return [
            'username' => $session->get('username'),
            'name' => $session->get('name'),
            'signature_link' => $session->get('signature_link'),
            'email' => $session->get('email'),
            'mobile' => $session->get('mobile'),
            'profile_image' => $session->get('profile_image'),
            'header_links' => $headerModel->getHeaderLinksByUser($userId),
        ];
    }

    public function index()
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $helper = new UserDataHelper(); $data = $helper->load(); // optional param
        $filter = $this->request->getGet('filter');

        $query = $this->numberingModel;
        if ($filter) {
            $query = $query->like('name', $filter);
        }

        $data['numberings'] = $query->findAll();
        $data['filter'] = $filter;

        return view('numbering_settings/index', $data);
    }

    public function create()
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $helper = new UserDataHelper(); $data = $helper->load(); // optional param
        return view('numbering_settings/create', $data);
    }

    public function store()
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        if ($this->numberingModel->save($this->request->getPost())) {
            return redirect()->to('/numbering_settings')->with('success', 'Numbering setting added.');
        }
        return redirect()->back()->with('error', 'Failed to add numbering setting.');
    }

    public function edit($id)
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $helper = new UserDataHelper(); $data = $helper->load(); // optional param
        $data['numbering'] = $this->numberingModel->find($id);

        if (!$data['numbering']) {
            return redirect()->to('/numbering_settings')->with('error', 'Numbering setting not found.');
        }

        return view('numbering_settings/edit', $data);
    }

    public function update($id)
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        if ($this->numberingModel->update($id, $this->request->getPost())) {
            return redirect()->to('/numbering_settings')->with('success', 'Updated successfully.');
        }
        return redirect()->back()->with('error', 'Failed to update.');
    }

    public function delete($id)
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        if ($this->numberingModel->delete($id)) {
            return redirect()->to('/numbering_settings')->with('success', 'Deleted successfully.');
        }
        return redirect()->to('/numbering_settings')->with('error', 'Delete failed.');
    }
}
