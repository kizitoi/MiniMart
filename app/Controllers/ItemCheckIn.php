<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ItemCheckInModel;
use App\Models\ItemModel;
use App\Models\SupplierModel;
use App\Models\UserModel;
use App\Models\HeaderModel;
use App\Libraries\UserDataHelper;

class ItemCheckIn extends BaseController
{
    protected $itemCheckInModel;
    protected $itemModel;
    protected $supplierModel;
    protected $userModel;

    public function __construct()
    {
        $this->itemCheckInModel = new ItemCheckInModel();
        $this->itemModel = new ItemModel();
        $this->supplierModel = new SupplierModel();
        $this->userModel = new UserModel();
    }

    private function loadUserData(): array
    {      $session = session();
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
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $helper = new UserDataHelper(); $data = $helper->load(); // optional param
        $checkIns = $this->itemCheckInModel->findAll();

        foreach ($checkIns as &$checkIn) {
            $item = $this->itemModel->find($checkIn['item_id']);
            $supplier = $this->supplierModel->find($checkIn['supplier_id']);

            $checkIn['item_name'] = $item ? $item['name'] : 'Unknown Item';
            $checkIn['supplier_name'] = $supplier ? $supplier['name'] : 'Unknown Supplier';
        }

        $data['check_ins'] = $checkIns;
        return view('item_check_in/index', $data);
    }

    public function create()
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $helper = new UserDataHelper(); $data = $helper->load(); // optional param
        $data['items'] = $this->itemModel->findAll();
        $data['suppliers'] = $this->supplierModel->findAll();
        return view('item_check_in/create', $data);
    }

    public function store()
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $session = session();
        $quantity = $this->request->getPost('check_in_quantity');
        $unit_price = $this->request->getPost('unit_price');
        $total_price = $quantity * $unit_price;

        $data = [
            'item_id' => $this->request->getPost('item_id'),
            'supplier_id' => $this->request->getPost('supplier_id'),
            'check_in_quantity' => $quantity,
            'date' => $this->request->getPost('date'),
            'time' => $this->request->getPost('time'),
            'unit_price' => $unit_price,
            'total_price' => $total_price,
            'user_id' => $session->get('user_id'),
            'user_name' => $session->get('username'),
        ];

        // Insert into item_check_in
        $this->itemCheckInModel->save($data);

        // Update the related item's quantity
        $item = $this->itemModel->find($data['item_id']);
        if ($item) {
            $newQuantity = $item['quantity'] + $quantity;
            $this->itemModel->update($data['item_id'], ['quantity' => $newQuantity]);
        }

        return redirect()->to(site_url('item_check_in'))->with('success', 'Item checked in successfully.');
    }
}
