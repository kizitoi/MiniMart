<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UnitOfMeasureModel;
use App\Models\HeaderModel;
use App\Models\UserModel;
use App\Libraries\UserDataHelper;


class UnitOfMeasure extends BaseController
{
    protected $unitModel;
    protected $userModel;

    public function __construct()
    {
        $this->unitModel = new UnitOfMeasureModel();
        $this->userModel = new UserModel();
    }


    public function index()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }


        $helper = new UserDataHelper();
        $data = $helper->load(); // optional param


        $filter = $this->request->getGet('filter');
        $query = $this->unitModel;

        if ($filter) {
            $query = $query->like('unit_name', $filter);
        }

        $data['units'] = $query->findAll();
        $data['filter'] = $filter;

        return view('unit_of_measure/index', $data);
    }

    public function create()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $helper = new UserDataHelper();
        $data = $helper->load(); // optional param
        return view('unit_of_measure/create', $data);
    }

    public function store()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $this->unitModel->save([
            'unit_name' => $this->request->getPost('unit_name')
        ]);

        return redirect()->to('/unit_of_measure')->with('success', 'Unit added successfully!');
    }

    public function edit($id)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $helper = new UserDataHelper();
        $data = $helper->load(); // optional param
        $unit = $this->unitModel->find($id);

        if (!$unit) {
            return redirect()->to('/unit_of_measure')->with('error', 'Unit not found');
        }

        $data['unit'] = $unit;
        return view('unit_of_measure/edit', $data);
    }

    public function update($id)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $this->unitModel->update($id, [
            'unit_name' => $this->request->getPost('unit_name')
        ]);

        return redirect()->to('/unit_of_measure')->with('success', 'Unit updated successfully!');
    }

    public function delete($id)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $this->unitModel->delete($id);

        return redirect()->to('/unit_of_measure')->with('success', 'Unit deleted.');
    }
}
