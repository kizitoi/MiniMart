<?php

namespace App\Controllers;

use App\Models\ItemModel;
use App\Models\ItemCategoryModel;
use App\Models\ShopModel;
use App\Models\PosListModel;
use App\Models\HeaderModel;
use App\Models\UserModel;
use App\Libraries\UserDataHelper;


class PosController extends BaseController
{
    protected $itemModel;
    protected $categoryModel;
    protected $shopModel;
    protected $posListModel;
    protected $userModel;

	public function __construct()
	{
		$this->itemModel = new ItemModel();
		$this->categoryModel = new ItemCategoryModel();
		$this->shopModel = new ShopModel();
		$this->posListModel = new PosListModel();
		$this->userModel = new UserModel(); // ← this was missing
	}


    public function index()
    {    $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $helper = new UserDataHelper(); $data = $helper->load(); // optional param
        $data['shops'] = $this->shopModel->findAll();
        return view('pos/index', $data);
    }

	private function loadUserData()
    {       $session = session();
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


    public function getCategoriesByShop($shopId)
    {       $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $db = \Config\Database::connect();
        $builder = $db->table('pos_list');
        $builder->select('item_categories.id, item_categories.name, item_categories.image');
        $builder->join('items', 'items.id = pos_list.item_id');
        $builder->join('item_categories', 'item_categories.id = items.category_id');
        $builder->where('pos_list.shop_id', $shopId);
        $builder->groupBy('item_categories.id');
        $query = $builder->get();

        return $this->response->setJSON($query->getResult());
    }

    public function getItemsByCategory($categoryId)
    {       $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $db = \Config\Database::connect();
        $builder = $db->table('items');
        $builder->select('items.id, items.name, items.unit_price, items.photo');
        $builder->where('items.category_id', $categoryId);
        $query = $builder->get();

        return $this->response->setJSON($query->getResult());
    }
}
