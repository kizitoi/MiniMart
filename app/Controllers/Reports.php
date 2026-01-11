<?php namespace App\Controllers;

use App\Models\ReportModel;
use App\Models\HeaderModel;
use App\Models\UserModel;
use App\Libraries\UserDataHelper;


class Reports extends BaseController
{
    public function index()
    {
        $session = session();
        $headerModel = new HeaderModel();
        $userModel = new \App\Models\UserModel();
        $userId = $session->get('user_id');
        $user = $userModel->find($userId);

        $data = [
            'username' => $session->get('username'),
            'name' => $session->get('name'),
            'signature_link' => $session->get('signature_link'),
            'email' => $session->get('email'),
            'mobile' => $session->get('mobile'),
            'profile_image' => $session->get('profile_image'),
            'header_links' => $headerModel->getHeaderLinksByUser($userId),
        ];

        $model = new ReportModel();
        $search = $this->request->getGet('search');
        if ($search) {
            $data['reports'] = $model->like('report_name', $search)->findAll();
        } else {
            $data['reports'] = $model->findAll();
        }

        $data['search'] = $search;

        return view('reports/index', $data);
    }

    public function view($id)
    {
        $model = new ReportModel();
        $report = $model->find($id);

        if (!$report) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        return redirect()->to($report['link']);
    }

	public function cashierSalesReport()
	{
		$session = session();
		$headerModel = new HeaderModel();
		$userModel = new UserModel();
		$db = \Config\Database::connect();

		$userId = $session->get('user_id');
		$user = $userModel->find($userId);

		$cashier = $this->request->getGet('cashier');
		$startDate = $this->request->getGet('start_date');
		$endDate = $this->request->getGet('end_date');

		$builder = $db->table('sales');
		$builder->select('sales.*, users.name as cashier_name');
		$builder->join('users', 'users.id = sales.cashier_id');

		if ($cashier) {
			$builder->where('users.name', $cashier);
		}

		if ($startDate) {
			$builder->where('sales.created_at >=', $startDate . ' 00:00:00');
		}

		if ($endDate) {
			$builder->where('sales.created_at <=', $endDate . ' 23:59:59');
		}

		$query = $builder->get();
		$sales = $query->getResult();

		$data = [
			'username' => $session->get('username'),
			'name' => $session->get('name'),
			'signature_link' => $session->get('signature_link'),
			'email' => $session->get('email'),
			'mobile' => $session->get('mobile'),
			'profile_image' => $session->get('profile_image'),
			'header_links' => $headerModel->getHeaderLinksByUser($userId),
			'sales' => $sales,
			'filters' => [
				'cashier' => $cashier,
				'start_date' => $startDate,
				'end_date' => $endDate
			]
		];

		return view('reports/cashier_sales_report', $data);
	}



}
