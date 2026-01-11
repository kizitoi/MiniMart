<?php namespace App\Controllers;

use App\Models\ItemCategoryModel;
use App\Models\HeaderModel;
use App\Models\UserModel;
use App\Models\ShopModel;
use App\Libraries\UserDataHelper;

class OfficerController extends BaseController
{
    protected $itemCategoryModel;
    protected $userModel;
    protected $shopModel;
    protected $db;

    public function __construct()
    {
        $this->itemCategoryModel = new ItemCategoryModel();
        $this->userModel = new UserModel();
        $this->shopModel = new ShopModel();
        $this->db = \Config\Database::connect();
    }


/*

    private function loadUserData($existingOrder = null)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $headerModel = new HeaderModel();
        $userId = $session->get('user_id');
        $user = $this->userModel->find($userId);

        // Default
        $companyName = 'Company';

        if (!empty($user) && !empty($user['company_id'])) {
            $db = \Config\Database::connect();
            $company = $db->table('companies')
                          ->select('name')
                          ->where('id', $user['company_id'])
                          ->get()
                          ->getRow();

            if ($company) {
                $companyName = $company->name;
            }
        }

        return [
            'username'        => $session->get('username'),
            'name'            => $session->get('name'),
            'user_id'         => $session->get('user_id'),
            'user_id_logged_in' => $session->get('user_id'),
            'signature_link'  => $session->get('signature_link'),
            'email'           => $session->get('email'),
            'mobile'          => $session->get('mobile'),
            'profile_image'   => $session->get('profile_image'),
            'header_links'    => $headerModel->getHeaderLinksByUser($user['user_id']),
            'existing_order'  => $existingOrder,
            'companyName'     => $companyName,
        ];
    }

*/

    public function byCategory($categoryId)
    {

      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
        $filter = $this->request->getGet('filter');

        $itemModel = new \App\Models\ItemModel();

        $query = $itemModel->where('category_id', $categoryId);

        if (!empty($filter)) {
            $query->like('name', $filter);
        }

        $data['items'] = $query->paginate(20); // or use findAll() if not paginating
        $data['filter'] = $filter;
        $data['pager'] = $itemModel->pager;

        // Load category name if needed
        $categoryModel = new \App\Models\ItemCategoryModel();
        $category = $categoryModel->find($categoryId);
        $data['category_name'] = $category['category_name'] ?? 'Selected Category';

        return view('items/by_category', $data);
    }




public function index()
{
  $session = session();
if (!$session->get('logged_in')) {
    return redirect()->to('login');
}
    $userId = $session->get('user_id'); // get user ID directly

    // ✅ Check for existing open (new) order
    $existingOrder = $this->db->table('sales_orders')
        ->where('created_by_user_id', $userId)
        ->where('status', 'new')
        ->get()
        ->getRow();


        $helper = new UserDataHelper();
        $data = $helper->load($existingOrder); // optional param

  ///  $data = $this->loadUserData($existingOrder); // ✅ pass order into user data

    $shopId = $this->request->getGet('shop_id');
    $search = $this->request->getGet('search');

    $categoryQuery = $this->itemCategoryModel
        ->select('item_categories.*, shops.name as shop_name, COUNT(items.id) as item_count')
        ->join('shops', 'shops.id = item_categories.shop_id')
        ->join('items', 'items.category_id = item_categories.id', 'left')
        ->groupBy('item_categories.id');

    if ($shopId) {
        $categoryQuery->where('item_categories.shop_id', $shopId);
    }

    if ($search) {
        $categoryQuery->like('item_categories.category_name', $search);
    }

    $data['categories'] = $categoryQuery->findAll();
    $data['selected_shop'] = $shopId;
    $data['search'] = $search;
    $data['shops'] = $this->shopModel->findAll();


    return view('officer/officer', $data);
}



}
