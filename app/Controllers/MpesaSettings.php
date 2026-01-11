<?php namespace App\Controllers;

use App\Models\MpesaSettingsModel;
use App\Libraries\UserDataHelper;

class MpesaSettings extends BaseController
{
    public function index()
    {
        $model = new MpesaSettingsModel();
        $settings = $model->first();

        $helper = new UserDataHelper();
        $data = $helper->load() + [
            'settings' => $settings
        ];

        return view('mpesa/settings', $data);
    }

    public function edit($id)
    {
        $model = new MpesaSettingsModel();
        $settings = $model->find($id);

        $helper = new UserDataHelper();
        $data = $helper->load() + [
            'settings' => $settings
        ];

        return view('mpesa/edit_settings', $data);
    }

    public function update($id)
    {
        $model = new MpesaSettingsModel();
        $post = $this->request->getPost();

        $model->update($id, $post);

        return redirect()->to('mpesa_settings')->with('message', 'Settings updated');
    }
}


/*namespace App\Controllers;
use App\Models\MpesaSettingsModel;
use App\Models\UserModel;
use App\Models\HeaderModel;

class MpesaSettings extends BaseController {

    protected $userModel;

    public function __construct()
    {

    $this->userModel = new UserModel();
    }

  private function loadUserData()
  {
  $session = session();
  if (!$session->get('logged_in')) {
    return redirect()->to('login');
  }
      $session = session();
      $headerModel = new HeaderModel();
      $userId = $session->get('user_id');
      $user = $this->userModel->find($userId);
      return [
          'username' => $session->get('username'),
          'name' => $session->get('name'),
          'signature_link' => $session->get('signature_link'),
          'email' => $session->get('email'),
          'mobile' => $session->get('mobile'),
          'profile_image' => $session->get('profile_image'),
          'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
      ];
  }
    public function index(){
        $model = new MpesaSettingsModel();
        $data = array_merge($this->loadUserData(), [
            'settings' => $model->first()
        ]);
        return view('mpesa/settings', $data);
    }

    public function edit($id){
        $model = new MpesaSettingsModel();
        $data = array_merge($this->loadUserData(), [
            'settings' => $model->find($id)
        ]);
        return view('mpesa/edit_settings', $data);
    }

    public function update($id){
        $model = new MpesaSettingsModel();
        $post = $this->request->getPost();
        $model->update($id, $post);
        return redirect()->to('mpesa_settings')->with('message','Settings updated');
    }
}
*/
