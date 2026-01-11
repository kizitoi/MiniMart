<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ItemUnitsOfMeasureModel;
use App\Models\UserModel;
use App\Models\HeaderModel;
use App\Models\UnitOfMeasureModel;
use App\Libraries\UserDataHelper;


class ItemUnitsOfMeasure extends BaseController
{
    protected $userModel;
    protected $headerModel;
    protected $model;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->headerModel = new HeaderModel();
        $this->model = new ItemUnitsOfMeasureModel();
    }

    private function loadUserData()
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $session = session();
        $userId = $session->get('user_id');
        $user = $this->userModel->find($userId);

        return [
            'username'       => $session->get('username'),
            'name'           => $session->get('name'),
            'signature_link' => $session->get('signature_link'),
            'email'          => $session->get('email'),
            'mobile'         => $session->get('mobile'),
            'profile_image'  => $session->get('profile_image'),
            'header_links'   => $this->headerModel->getHeaderLinksByUser($user['user_id']),
        ];
    }

    public function index($itemId)
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $db = \Config\Database::connect();
        $builder = $db->table('items_units_of_measure');
        $builder->select('items_units_of_measure.*, unit_of_measure.unit_name');
        $builder->join('unit_of_measure', 'unit_of_measure.id = items_units_of_measure.unit_id');
        $builder->where('items_units_of_measure.item_id', $itemId);
        $query = $builder->get();
        $units = $query->getResultArray();

        $data = array_merge($this->loadUserData(), [
            'units'   => $units,
            'itemId'  => $itemId,
        ]);

        return view('items_units_of_measure/index', $data);
    }

    public function create($itemId)
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $unitModel = new UnitOfMeasureModel();

        $data = array_merge($this->loadUserData(), [
            'itemId' => $itemId,
            'units' => $unitModel->findAll(),
        ]);

        return view('items_units_of_measure/create', $data);
    }

    public function store()
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $this->model->save([
            'item_id'    => $this->request->getPost('item_id'),
            'unit_id'    => $this->request->getPost('unit_id'),
            'unit_value' => $this->request->getPost('unit_value'),
        ]);

        return redirect()->to('items_units_of_measure/' . $this->request->getPost('item_id'))
                         ->with('success', 'Unit of measure added successfully.');
    }

    public function edit($id)
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $unitModel = new UnitOfMeasureModel();
        $unitRecord = $this->model->find($id);

        $data = array_merge($this->loadUserData(), [
            'unit'      => $unitRecord,
            'units'     => $unitModel->findAll(),
            'itemId'    => $unitRecord['item_id'],
        ]);

        return view('items_units_of_measure/edit', $data);
    }

    public function update($id)
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $this->model->update($id, [
            'unit_id'    => $this->request->getPost('unit_id'),
            'unit_value' => $this->request->getPost('unit_value'),
        ]);

        return redirect()->to('items_units_of_measure/' . $this->request->getPost('item_id'))
                         ->with('success', 'Unit of measure updated successfully.');
    }

    public function delete($id)
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $unit = $this->model->find($id);
        $itemId = $unit['item_id'];

        $this->model->delete($id);

        return redirect()->to('items_units_of_measure/' . $itemId)
                         ->with('success', 'Unit of measure deleted successfully.');
    }
}
