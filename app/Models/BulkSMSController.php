<?php namespace App\Controllers;

use App\Models\BulkSMSModel;

class BulkSMSController extends BaseController
{
    public function index()
    {
        $model = new BulkSMSModel();
        $data['setting'] = $model->first(); // Only one setting row expected
        return view('bulksms/index', $data);
    }

    public function save()
    {
        $model = new BulkSMSModel();
        $postData = [
            'username' => $this->request->getPost('username'),
            'password' => $this->request->getPost('password'),
            'api_url'  => $this->request->getPost('api_url'),
        ];

        $existing = $model->first();
        if ($existing) {
            $model->update($existing['id'], $postData);
        } else {
            $model->insert($postData);
        }

        return redirect()->to('/bulksms')->with('success', 'Settings saved.');
    }
}
