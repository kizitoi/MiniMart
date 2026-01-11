<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\VatSettingsModel;
use App\Models\HeaderModel;
use App\Models\UserModel;
use App\Libraries\UserDataHelper;


class VatSettings extends BaseController
{

    public function index()
    {

      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }

        $vatModel = new VatSettingsModel();
        $filter = $this->request->getGet('filter');

        if ($filter) {
            $vats = $vatModel->like('vat_name', $filter)->findAll();
        } else {
            $vats = $vatModel->findAll();
        }

        $helper = new UserDataHelper();
        $data = $helper->load(); // optional param

        $data['vats'] = $vats;
        $data['filter'] = $filter;

        return view('vat_settings/index', $data);
    }

    public function create()
    {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }

      $helper = new UserDataHelper();
      $data = $helper->load(); // optional param

        return view('vat_settings/create', $data);
    }

    public function store()
    {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }

        $vatModel = new VatSettingsModel();
        $vatModel->save($this->request->getPost());

        session()->setFlashdata('success', 'VAT Setting created successfully.');
        return redirect()->to('/vat_settings');
    }

    public function edit($id)
    {     $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $vatModel = new VatSettingsModel();
        $vat = $vatModel->find($id);

        if (!$vat) {
            session()->setFlashdata('error', 'VAT Setting not found.');
            return redirect()->to('/vat_settings');
        }

        $helper = new UserDataHelper();
        $data = $helper->load(); // optional param

        $data['vat'] = $vat;

        return view('vat_settings/edit', $data);
    }

    public function update($id)
    {     $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $vatModel = new VatSettingsModel();
        $vatModel->update($id, $this->request->getPost());

        session()->setFlashdata('success', 'VAT Setting updated successfully.');
        return redirect()->to('/vat_settings');
    }

    public function delete($id)
    {     $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $vatModel = new VatSettingsModel();
        $vatModel->delete($id);

        session()->setFlashdata('success', 'VAT Setting deleted successfully.');
        return redirect()->to('/vat_settings');
    }
}
