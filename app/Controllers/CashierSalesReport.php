<?php namespace App\Controllers;

use App\Models\UserModel;
use App\Models\HeaderModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpWord\PhpWord;
use Dompdf\Dompdf;
use CodeIgniter\Controller;
use App\Libraries\UserDataHelper;

class CashierSalesReport extends BaseController
{
    protected $db;
    protected $userModel;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
        $this->userModel = new UserModel();
    }

    private function loadUserData()
    {
        $session = session();
        $headerModel = new HeaderModel();
        $userId = $session->get('user_id');
        $user = $this->userModel->find($userId);

        return [
            'username' => $session->get('username'),
            'name' => $session->get('name'),
            'signature_link' => $session->get('signature_link'),
            'email' => $session->get('email'),
            'mobile' => $session->get('mobile'),
            'profile_image' => $session->get('profile_image'),
            'header_links' => $headerModel->getHeaderLinksByUser($userId),
        ];
    }

	public function index()
	{
		$cashier = $this->request->getGet('cashier');
		$startDate = $this->request->getGet('start_date');
		$endDate = $this->request->getGet('end_date');

		$builder = $this->db->table('sales');
		$builder->select('sales.*, users.username as cashier_name');
		$builder->join('users', 'users.user_id = sales.cashier_id');

		if ($cashier) {
			$builder->where('users.username', $cashier);
		}

		if ($startDate) {
			$builder->where('sales.created_at >=', $startDate . ' 00:00:00');
		}

		if ($endDate) {
			$builder->where('sales.created_at <=', $endDate . ' 23:59:59');
		}

		$sales = $builder->get()->getResult();

		// Fetch distinct cashiers for the filter dropdown
		$cashiers = $this->db->table('users')
			->select('users.user_id, users.username')
			->join('sales', 'sales.cashier_id = users.user_id', 'inner')
			->groupBy('users.user_id')
			->orderBy('users.username', 'asc')
			->get()
			->getResult();

		$helper = new UserDataHelper(); $data = $helper->load(); // optional param
		$data['sales'] = $sales;
		$data['filters'] = compact('cashier', 'startDate', 'endDate');
		$data['cashiers'] = $cashiers;

		return view('reports/cashier_sales_report', $data);
	}


	public function export($format)
	{
		$cashier = $this->request->getGet('cashier');
		$startDate = $this->request->getGet('start_date');
		$endDate = $this->request->getGet('end_date');

		$builder = $this->db->table('sales');
		$builder->select('sales.sales_order_id, sales.item_name, sales.quantity, sales.sellingPrice, sales.total_cost, sales.total_discount, users.username as cashier_name, sales.created_at');
		$builder->join('users', 'users.user_id = sales.cashier_id');

		if ($cashier) {
			$builder->where('users.username', $cashier);
		}
		if ($startDate) {
			$builder->where('sales.created_at >=', $startDate . ' 00:00:00');
		}
		if ($endDate) {
			$builder->where('sales.created_at <=', $endDate . ' 23:59:59');
		}

		$sales = $builder->get()->getResultArray();

		if ($format === 'pdf') {
			$dompdf = new \Dompdf\Dompdf();
			$html = view('reports/exports/cashier_sales_pdf', ['sales' => $sales]);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'landscape');
			$dompdf->render();
			$dompdf->stream('cashier_sales_report.pdf');
			exit;
		}

		if ($format === 'word') {
			$phpWord = new \PhpOffice\PhpWord\PhpWord();
			$section = $phpWord->addSection();
			$table = $section->addTable();

			$headers = ['Sale ID', 'Cashier', 'Item', 'Qty', 'Unit Price', 'Total Cost', 'Discount', 'Date'];
			$table->addRow();
			foreach ($headers as $header) {
				$table->addCell()->addText($header);
			}

			foreach ($sales as $s) {
				$table->addRow();
				$table->addCell()->addText($s['sales_order_id']);
				$table->addCell()->addText($s['cashier_name']);
				$table->addCell()->addText($s['item_name']);
				$table->addCell()->addText($s['quantity']);
				$table->addCell()->addText($s['sellingPrice']);
				$table->addCell()->addText($s['total_cost']);
				$table->addCell()->addText($s['total_discount']);
				$table->addCell()->addText($s['created_at']);
			}

			header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
			header('Content-Disposition: attachment;filename="cashier_sales_report.docx"');
			header('Cache-Control: max-age=0');

			$writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
			$writer->save('php://output');
			exit;
		}

		if ($format === 'excel') {
			$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();

			$headers = ['Sale ID', 'Cashier', 'Item', 'Qty', 'Unit Price', 'Total Cost', 'Discount', 'Date'];
			$col = 'A';
			foreach ($headers as $header) {
				$sheet->setCellValue("{$col}1", $header);
				$col++;
			}

			$row = 2;
			foreach ($sales as $s) {
				$sheet->setCellValue("A{$row}", $s['sales_order_id']);
				$sheet->setCellValue("B{$row}", $s['cashier_name']);
				$sheet->setCellValue("C{$row}", $s['item_name']);
				$sheet->setCellValue("D{$row}", $s['quantity']);
				$sheet->setCellValue("E{$row}", $s['sellingPrice']);
				$sheet->setCellValue("F{$row}", $s['total_cost']);
				$sheet->setCellValue("G{$row}", $s['total_discount']);
				$sheet->setCellValue("H{$row}", $s['created_at']);
				$row++;
			}

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="cashier_sales_report.xlsx"');
			header('Cache-Control: max-age=0');

			$writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
			$writer->save('php://output');
			exit;
		}

		return redirect()->back()->with('error', 'Invalid export format');
	}

}
