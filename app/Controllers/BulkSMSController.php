<?php namespace App\Controllers;

use App\Models\BulkSMSModel;
use App\Libraries\UserDataHelper;

class BulkSMSController extends BaseController
{
    public function index()
    {

      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }

        $model = new BulkSMSModel();
      ///  $data['setting'] = $model->first(); // Only one setting row expected


        $userModel = new \App\Models\UserModel();
        $headerModel = new \App\Models\HeaderModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);
        //'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),


                $searchQuery = $this->request->getGet('search'); // Get search term
              /*  $data = [
                    'setting' => $model->first(),
                    'username' => $session->get('username'),
                    'name' => $session->get('name'),
                    'signature_link' => $session->get('signature_link'),
                    'email' => $session->get('email'),
                    'mobile' => $session->get('mobile'),
                    'profile_image' => $session->get('profile_image'),
                    'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
                ];*/


                $helper = new UserDataHelper();
                $data = $helper->load() + [
                  'setting' => $model->first(),
                ////  'username' => $session->get('username'),
                //  'name' => $session->get('name'),
                //  'signature_link' => $session->get('signature_link'),
                //  'email' => $session->get('email'),
                  //'mobile' => $session->get('mobile'),
                //  'profile_image' => $session->get('profile_image'),
                  'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
                ];





        return view('bulksms/index', $data);
    }

    public function save()
    {

      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
        $model = new BulkSMSModel();
        $postData = [
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'api_url'  => $this->request->getPost('api_url'),
        ];

        $existing = $model->first();
        if ($existing) {
            $model->update($existing['id'], $postData);
        } else {
            $model->insert($postData);
        }

        return redirect()->to('/bulksms')->with('success', 'Settings saved.');
    }
}
