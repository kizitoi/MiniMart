<?php
namespace App\Controllers;

use App\Models\PaymentMethodModel;
use App\Models\PaymentDescriptionModel;
use App\Libraries\UserDataHelper;
use CodeIgniter\Controller;

class PaymentDescriptions extends Controller
{
    public function index()
    {
        $helper = new UserDataHelper();
        $data = $helper->load();

        $model = new PaymentDescriptionModel();
        $descriptions = $model->findAll();

        return view('payment_descriptions/index', $data + ['descriptions' => $descriptions]);
    }

    public function create()
    {
        $helper = new UserDataHelper();
        $data = $helper->load();

        return view('payment_descriptions/create', $data);
    }

    public function store()
    {
        $model = new PaymentDescriptionModel();
        $model->save([
            'description' => $this->request->getPost('description')
        ]);

        return redirect()->to('payment_descriptions')->with('message', 'Payment description added');
    }

    public function edit($id)
    {
        $helper = new UserDataHelper();
        $data = $helper->load();

        $model = new PaymentDescriptionModel();
        $description = $model->find($id);

        return view('payment_descriptions/edit', $data + ['description' => $description]);
    }

    public function update($id)
    {
        $model = new PaymentDescriptionModel();
        $model->update($id, [
            'description' => $this->request->getPost('description')
        ]);

        return redirect()->to('payment_descriptions')->with('message', 'Payment description updated');
    }

    public function delete($id)
    {
        $model = new PaymentDescriptionModel();
        if (!$model->isInUse($id)) {
            $model->delete($id);
            return redirect()->to('payment_descriptions')->with('message', 'Payment description deleted');
        }
        return redirect()->to('payment_descriptions')->with('error', 'Cannot delete, description in use.');
    }
}
