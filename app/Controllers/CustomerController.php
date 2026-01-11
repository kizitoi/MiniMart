<?php namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\CustomerReportModel;
use CodeIgniter\Controller;
use App\Libraries\UserDataHelper;

class CustomerController extends Controller
{
    protected $customerModel;
    protected $reportModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        $this->reportModel = new CustomerReportModel();
    }

    public function index()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $userModel = new \App\Models\UserModel();
        $headerModel = new \App\Models\HeaderModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);

        $db = db_connect();

        // Fetch customers with aggregated sales info
        $query = $db->query('
            SELECT c.*,
                   COALESCE(SUM(s.paid_amount), 0) AS total_paid,
                   COALESCE(SUM(s.order_amount), 0) AS total_ordered,
                   COALESCE(SUM(s.balance), 0) AS total_balance
            FROM customers c
            LEFT JOIN sales_orders s ON s.customer_id = c.customer_id AND s.credit_paid = 0
            GROUP BY c.customer_id
        ');

        $customers = $query->getResultArray();

        $helper = new UserDataHelper();
        $data = $helper->load() + [
            'name' => $this->request->getPost('name'),
            'customers' => $customers,
            'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
        ];

        return view('customers/index', $data);
    }

    public function form($id = null)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $userModel = new \App\Models\UserModel();
        $headerModel = new \App\Models\HeaderModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);

        $helper = new UserDataHelper();
        $data = $helper->load() + [
            'name' => $this->request->getPost('name'),
        ];

        $data['customer'] = $id ? $this->customerModel->find($id) : null;
        return view('customers/form', $data);
    }

    public function save()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $data = $this->request->getPost();

        if (!empty($data['customer_id'])) {
            $this->customerModel->update($data['customer_id'], $data);
        } else {
            $this->customerModel->insert($data);
        }

        return redirect()->to('/customers')->with('success', 'Customer saved successfully.');
    }

    public function delete($id)
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $related = $this->reportModel->where('customer_id', $id)->countAllResults();
        if ($related > 0) {
            return redirect()->to('/customers')->with('error', 'Cannot delete customer with reports.');
        }

        $this->customerModel->delete($id);
        return redirect()->to('/customers')->with('success', 'Customer deleted successfully.');
    }
}



/* namespace App\Controllers;

use App\Models\CustomerModel;
use App\Models\CustomerReportModel;
use CodeIgniter\Controller;
use App\Libraries\UserDataHelper;

class CustomerController extends Controller
{
    protected $customerModel;
    protected $reportModel;

    public function __construct()
    {
        $this->customerModel = new CustomerModel();
        $this->reportModel = new CustomerReportModel();
    }

    public function index()
    {

      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
      if (!$session->get('logged_in'))
      {
          return redirect()->to('login');
      }

      $userModel = new \App\Models\UserModel();
$headerModel = new \App\Models\HeaderModel();
$userId = session()->get('user_id');
$user = $userModel->find($userId);

        $helper = new UserDataHelper();
        $data = $helper->load() + [
          'name' => $this->request->getPost('name'),
           'customers' => $this->customerModel->findAll(),
           'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
        ];



        return view('customers/index', $data);
    }

    public function form($id = null)
    {
      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
      $userModel = new \App\Models\UserModel();
$headerModel = new \App\Models\HeaderModel();
$userId = session()->get('user_id');
$user = $userModel->find($userId);

      if (!$session->get('logged_in'))
      {
          return redirect()->to('login');
      }

         $helper = new UserDataHelper();
        $data = $helper->load() + [
          'name' => $this->request->getPost('name'),
        ];


        $data['customer'] = $id ? $this->customerModel->find($id) : null;
        return view('customers/form', $data);
    }

    public function save()
    {
      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
        $data = $this->request->getPost();

        if (!empty($data['customer_id'])) {
            $this->customerModel->update($data['customer_id'], $data);
        } else {
            $this->customerModel->insert($data);
        }

        return redirect()->to('/customers')->with('success', 'Customer saved successfully.');
    }

    public function delete($id)
    {
      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
        $related = $this->reportModel->where('customer_id', $id)->countAllResults();
        if ($related > 0) {
            return redirect()->to('/customers')->with('error', 'Cannot delete customer with reports.');
        }

        $this->customerModel->delete($id);
        return redirect()->to('/customers')->with('success', 'Customer deleted successfully.');
    }
}
*/
