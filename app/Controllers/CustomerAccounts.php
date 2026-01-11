<?php


namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\CustomerAccountModel;
use App\Models\CustomerModel;
use App\Models\PaymentDescriptionModel;
use App\Models\PaymentMethodModel;
use CodeIgniter\I18n\Time;
use Dompdf\Dompdf;
use Dompdf\Options;
use App\Libraries\UserDataHelper;

class CustomerAccounts extends BaseController
{
    protected $accountModel;
    protected $db;

    public function __construct()
    {
        $this->accountModel = new CustomerAccountModel();
        $this->db = \Config\Database::connect();
    }


public function index($customer_id)
{
    $session = session();
    if (!$session->get('logged_in')) return redirect()->to('login');

    $helper = new \App\Libraries\UserDataHelper();
    $data = $helper->load();

    $customerModel = new \App\Models\CustomerModel();
    $descModel = new \App\Models\PaymentDescriptionModel();
    $methodModel = new \App\Models\PaymentMethodModel();
    $accountModel = new \App\Models\CustomerAccountModel();

    $customer = $customerModel->find($customer_id);

    // Use model's paginated query
    $entries = $accountModel->getDetailedAccountsPaginated($customer_id, 10);

    $credits = db_connect()->table('sales_orders')
        ->select('id, order_amount, paid_amount, balance, credit_paid, created_at')
        ->where('customer_id', $customer_id)
        ->where('payment_method', 'credit')
        ->where('credit_paid', '0')
        ->get()->getResult();

    /*$total_credit = db_connect()->table('sales_orders')
    ->selectSum('order_amount')
    ->where('customer_id', $customer_id)
    ->where('payment_method', 'Credit')
    ->where('credit_paid', 0)
    ->get()->getRow()->order_amount ?? 0;*/

    $total_credit = db_connect()->table('sales_orders')
    ->selectSum('balance')
    ->where('customer_id', $customer_id)
    ->where('LOWER(payment_method)', 'credit') // 👈 ensures case-insensitive match
    ->where('credit_paid', 0)
    ->get()
    ->getRow()
    ->balance ?? 0;



    $payment_descriptions = $descModel->findAll();
    $payment_methods = $methodModel->findAll();



    return view('customer_accounts/index', $data + [
    'customer' => $customer,
    'entries' => $entries,
    'pager' => $accountModel->pager,
    'credits' => $credits,
    'payment_descriptions' => $payment_descriptions,
    'payment_methods' => $payment_methods,
    'total_credit_balance' => $total_credit
]);

}



public function exportStatement($customer_id)
{
    $session = session();
    if (!$session->get('logged_in')) return redirect()->to('login');

    $start = $this->request->getGet('start');
    $end = $this->request->getGet('end');

    if (!$start || !$end) {
        return redirect()->back()->with('error', 'Start and End dates are required.');
    }

    $db = db_connect();

    $company = $db->table('companies')->get()->getRow();
    $customer = $db->table('customers')->where('customer_id', $customer_id)->get()->getRow();

    $entries = $db->table('customer_accounts ca')
        ->select('ca.*, pd.description, pm.method, u.username')
        ->join('payment_descriptions pd', 'pd.id = ca.description_id', 'left')
        ->join('payment_methods pm', 'pm.id = ca.payment_method_id', 'left')
        ->join('users u', 'u.user_id = ca.created_by_user_id', 'left')
        ->where('ca.customer_id', $customer_id)
        ->where('ca.created_at >=', $start)
        ->where('ca.created_at <=', $end . ' 23:59:59')
        ->orderBy('ca.created_at', 'asc')
        ->get()
        ->getResult();

    $html = view('customer_accounts/statement_pdf', [
        'company' => $company,
        'customer' => $customer,
        'entries' => $entries,
        'start' => $start,
        'end' => $end
    ]);

    $options = new \Dompdf\Options();
    $options->set('isRemoteEnabled', true);
    $dompdf = new \Dompdf\Dompdf($options);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $dompdf->stream("statement_{$customer->name}_{$start}_to_{$end}.pdf", ['Attachment' => false]);
    exit;
}


    public function add($customer_id)
    {
        $session = session();
        if (!$session->get('logged_in')) return redirect()->to('login');

        $user_id = $session->get('user_id');
        $request = service('request');

        $type = $request->getPost('type');
        $desc_id = $request->getPost('description_id');
        $method_id = $request->getPost('payment_method_id');
        $amount = (float) $request->getPost('amount');

        if (!$type || !$desc_id || !$method_id || !$amount) {
            return redirect()->back()->with('error', 'All fields are required.');
        }

        $last = $this->accountModel->where('customer_id', $customer_id)->orderBy('id', 'DESC')->first();
        $prev_balance = $last['balance'] ?? 0;

        $new_balance = $type === 'credit' ? ($prev_balance + $amount) : ($prev_balance - $amount);
        $nairobiTime = new \DateTime('now', new \DateTimeZone('Africa/Nairobi'));

        $this->accountModel->insert([
            'customer_id' => $customer_id,
            'type' => $type,
            'description_id' => $desc_id,
            'payment_method_id' => $method_id,
            'amount' => $amount,
            'balance' => $new_balance,
            'created_by_user_id' => $user_id,
            'created_at' => $nairobiTime->format('Y-m-d H:i:s')
        ]);

        $insertId = $this->accountModel->getInsertID();
        return redirect()->to(site_url("customer_accounts/printReceipt/{$insertId}"));
    }

  /*  public function markPaid()
    {
        $session = session();
        if (!$session->get('logged_in')) return redirect()->to('login');

        $request = service('request');
        $paid = $request->getPost('paid');

        if ($paid && is_array($paid)) {
            $nairobiTime = new \DateTime('now', new \DateTimeZone('Africa/Nairobi'));

            foreach ($paid as $orderId) {
                $this->db->table('sales_orders')->update([
                    'credit_paid' => 1,
                    'updated_at' => $nairobiTime->format('Y-m-d H:i:s')
                ], ['id' => $orderId]);
            }
        }

        return redirect()->back()->with('message', 'Selected credits marked as paid.');
    }*/

    public function markPaid()
{
    $session = session();
    if (!$session->get('logged_in')) return redirect()->to('login');

    $request = service('request');
    $paid = $request->getPost('paid');

    if ($paid && is_array($paid)) {
        $nairobiTime = new \DateTime('now', new \DateTimeZone('Africa/Nairobi'));

        foreach ($paid as $orderId) {
            // Fetch the order_amount first
            $order = $this->db->table('sales_orders')->select('order_amount')->where('id', $orderId)->get()->getRow();

            if ($order) {
                $this->db->table('sales_orders')->update([
                    'credit_paid' => 1,
                    'paid_amount' => $order->order_amount,
                    'balance' => 0,
                    'updated_at' => $nairobiTime->format('Y-m-d H:i:s')
                ], ['id' => $orderId]);
            }
        }
    }

    return redirect()->back()->with('message', 'Selected credits marked as paid.');
}


    public function printReceipt($entryId)
    {
        $session = session();
        if (!$session->get('logged_in')) return redirect()->to('login');

        $entry = $this->db->table('customer_accounts ca')
            ->select('ca.*, pd.description, pm.method')
            ->join('payment_descriptions pd', 'pd.id = ca.description_id', 'left')
            ->join('payment_methods pm', 'pm.id = ca.payment_method_id', 'left')
            ->where('ca.id', $entryId)
            ->get()
            ->getRowArray();

        $customer = $this->db->table('customers')->where('customer_id', $entry['customer_id'])->get()->getRowArray();
        $company = $this->db->table('companies')->get()->getRow();
        $recorded_by = $session->get('username') ?? 'System';

        $html = view('customer_accounts/receipt_pdf', [
            'entry' => $entry,
            'customer' => $customer,
            'company' => $company,
            'recorded_by' => $recorded_by
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 226.77, 1000], 'portrait');
        $dompdf->render();

        if (ob_get_length()) ob_end_clean();
        $dompdf->stream("receipt_{$entryId}.pdf", ['Attachment' => false]);
        exit;
    }

    public function generateReceipt($accountId)
    {
        $session = session();
        if (!$session->get('logged_in')) return redirect()->to('login');

        $accountModel = new CustomerAccountModel();
        $descModel = new PaymentDescriptionModel();
        $methodModel = new PaymentMethodModel();
        $customerModel = new CustomerModel();

        $entry = $accountModel->find($accountId);
        $customer = $customerModel->find($entry['customer_id']);
        $description = $descModel->find($entry['description_id']);
        $method = $methodModel->find($entry['payment_method_id']);
        $company = $this->db->table('companies')->get()->getRow();
        $userName = $session->get('username');

        $html = view('customer_accounts/receipt_pdf', [
            'entry' => $entry,
            'customer' => $customer,
            'description' => $description,
            'method' => $method,
            'company' => $company,
            'recorded_by' => $userName
        ]);

        $options = new Options();
        $options->set('isRemoteEnabled', true);
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper([0, 0, 164.41, 1000], 'portrait');
        $dompdf->render();

        if (ob_get_length()) ob_end_clean();
        $dompdf->stream("receipt_{$accountId}.pdf", ['Attachment' => false]);
        exit;
    }
}
