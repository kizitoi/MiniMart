<?

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SupplierModel;
use App\Models\TownModel;
use App\Models\UserModel;
use App\Models\HeaderModel;
use App\Libraries\UserDataHelper;


class SuppliersController extends BaseController
{
    protected $supplierModel;
    protected $townModel;
    protected $userModel;

    public function __construct()
    {
        $this->supplierModel = new SupplierModel();
        $this->townModel = new TownModel();
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

        if ($filter) {
            $suppliers = $this->supplierModel
                ->like('name', $filter)
                ->findAll();
        } else {
            $suppliers = $this->supplierModel->findAll();
        }

        $data['suppliers'] = $suppliers;
        $data['filter'] = $filter;
        return view('suppliers/index', $data);
    }

    public function create()
    {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
      $helper = new UserDataHelper();
      $data = $helper->load(); // optional param
        $data['towns'] = $this->townModel->findAll();
        return view('suppliers/create', $data);
    }

    public function store()
    {   $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $this->supplierModel->save($this->request->getPost());
        session()->setFlashdata('success', 'Supplier added successfully.');
        return redirect()->to('/suppliers');
    }

    public function edit($id)
    {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
      $helper = new UserDataHelper();
      $data = $helper->load(); // optional param
        $data['supplier'] = $this->supplierModel->find($id);
        $data['towns'] = $this->townModel->findAll();
        return view('suppliers/edit', $data);
    }

    public function update($id)
    {   $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $this->supplierModel->update($id, $this->request->getPost());
        session()->setFlashdata('success', 'Supplier updated successfully.');
        return redirect()->to('/suppliers');
    }

    public function delete($id)
    {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $this->supplierModel->delete($id);
        session()->setFlashdata('success', 'Supplier deleted successfully.');
        return redirect()->to('/suppliers');
    }
}
