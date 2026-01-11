<?php

namespace App\Controllers;

use App\Models\PaymentMethodModel;
use App\Models\PaymentDescriptionModel;
use App\Libraries\UserDataHelper;
use CodeIgniter\Controller;

class PaymentMethods extends Controller
{
    public function index()
    {
        $helper = new UserDataHelper();
        $data = $helper->load();

        $model = new PaymentMethodModel();
        $methods = $model->findAll();

        return view('payment_methods/index', $data + ['methods' => $methods]);
    }

    public function create()
    {
        $helper = new UserDataHelper();
        $data = $helper->load();

        return view('payment_methods/create', $data);
    }

    public function store()
    {
        $model = new PaymentMethodModel();
        $model->save([
            'method' => $this->request->getPost('method')
        ]);

        return redirect()->to('payment_methods')->with('message', 'Payment method added');
    }

    public function edit($id)
    {
        $helper = new UserDataHelper();
        $data = $helper->load();

        $model = new PaymentMethodModel();
        $method = $model->find($id);

        return view('payment_methods/edit', $data + ['method' => $method]);
    }

    public function update($id)
    {
        $model = new PaymentMethodModel();
        $model->update($id, [
            'method' => $this->request->getPost('method')
        ]);

        return redirect()->to('payment_methods')->with('message', 'Payment method updated');
    }

    public function delete($id)
    {
        $model = new PaymentMethodModel();
        if (!$model->isInUse($id)) {
            $model->delete($id);
            return redirect()->to('payment_methods')->with('message', 'Payment method deleted');
        }
        return redirect()->to('payment_methods')->with('error', 'Cannot delete, method in use.');
    }
}
