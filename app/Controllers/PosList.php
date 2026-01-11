<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PosListModel;
use App\Models\ShopModel;
use App\Models\ItemModel;
use App\Models\UserModel;
use App\Models\HeaderModel;
use App\Libraries\UserDataHelper;


class PosList extends BaseController
{
    protected $posListModel;
    protected $shopModel;
    protected $itemModel;
    protected $userModel;

    public function __construct()
    {
        $this->posListModel = new PosListModel();
        $this->shopModel = new ShopModel();
        $this->itemModel = new ItemModel();
        $this->userModel = new UserModel();
    }

    private function loadUserData()
    {    $session = session();
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

    public function index()
      {     $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }
        $helper = new UserDataHelper(); $data = $helper->load(); // optional param
        $data['pos_list'] = $this->posListModel->getPOSData();
        return view('pos_list/index', $data);
    }
}
