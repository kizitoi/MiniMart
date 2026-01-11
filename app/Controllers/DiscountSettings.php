<?php namespace App\Controllers;

use App\Models\DiscountSettingsModel;
use App\Models\UserModel;
use App\Models\HeaderModel;
use App\Libraries\UserDataHelper;

class DiscountSettings extends BaseController
{
    protected function loadUserData(): array
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $userModel = new UserModel();
        $headerModel = new HeaderModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);

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
    {
        $model = new DiscountSettingsModel();
        $search = $this->request->getGet('search');

        $query = $model;
        if ($search) {
            $query = $query->like('discount_name', $search);
        }

        $data = $this->loadUserData() + [
            'discounts' => $query->findAll(),
            'search' => $search
        ];

        return view('discount_settings/index', $data);
    }

    public function create()
    {
        return view('discount_settings/create', $this->loadUserData());
    }

    public function store()
    {
        $model = new DiscountSettingsModel();
        $data = $this->request->getPost([
            'discount_name', 'discount_amount', 'min_shopping_amount',
            'maximum_shopping_amount', 'enabled'
        ]);
        $data['enabled'] = $this->request->getPost('enabled') ? 1 : 0;

        $model->insert($data);
        return redirect()->to('/discount_settings')->with('message', 'Discount created successfully!');
    }

    public function edit($id)
    {
        $model = new DiscountSettingsModel();
        $discount = $model->find($id);

        if (!$discount) {
            return redirect()->back()->with('error', 'Discount not found.');
        }

        return view('discount_settings/edit', $this->loadUserData() + ['discount' => $discount]);
    }

    public function update($id)
    {
        $model = new DiscountSettingsModel();
        $data = $this->request->getPost([
            'discount_name', 'discount_amount', 'min_shopping_amount',
            'maximum_shopping_amount', 'enabled'
        ]);
        $data['enabled'] = $this->request->getPost('enabled') ? 1 : 0;

        $model->update($id, $data);
        return redirect()->to('/discount_settings')->with('message', 'Discount updated successfully!');
    }

	public function delete($id)
	{
		$model = new DiscountSettingsModel();
		try {
			$model->delete($id);
			return redirect()->to('/discount_settings')->with('message', 'Discount deleted.');
		} catch (\Exception $e) {
			return redirect()->to('/discount_settings')->with('error', 'Cannot delete discount. It is used in sales orders.');
		}
	}

}
