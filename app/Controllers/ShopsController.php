<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ShopModel;
use App\Models\UserModel;
use App\Models\HeaderModel;
use App\Libraries\UserDataHelper;


class ShopsController extends BaseController
{
    protected $shopModel;
    protected $userModel;

    public function __construct()
    {
        $this->shopModel = new ShopModel();
        $this->userModel = new UserModel();
    }


    public function index()
    {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $helper = new UserDataHelper(); $data = $helper->load(); // optional param
        $search = $this->request->getGet('search');

        if ($search) {
            $data['shops'] = $this->shopModel
                ->like('name', $search)
                ->findAll();
        } else {
            $data['shops'] = $this->shopModel->findAll();
        }

        $data['search'] = $search;

        return view('shops/index', $data);
    }


    public function create()
    {   $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $helper = new UserDataHelper(); $data = $helper->load(); // optional param
        return view('shops/create', $data);
    }

    public function store()
    {   $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $this->shopModel->save([
            'name' => $this->request->getPost('name'),
        ]);
        return redirect()->to('/shops')->with('success', 'Shop added successfully.');
    }

    public function edit($id)
    {   $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $helper = new UserDataHelper(); $data = $helper->load(); // optional param
        $data['shop'] = $this->shopModel->find($id);

        return view('shops/edit', $data);
    }

    public function update($id)
    {   $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $this->shopModel->update($id, [
            'name' => $this->request->getPost('name'),
        ]);
        return redirect()->to('/shops')->with('success', 'Shop updated successfully.');
    }

    public function delete($id)
    {   $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $this->shopModel->delete($id);
        return redirect()->to('/shops')->with('success', 'Shop deleted successfully.');
    }
}
