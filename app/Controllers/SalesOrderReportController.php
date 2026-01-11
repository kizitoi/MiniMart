<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\HeaderModel;
use App\Models\SalesOrderModel;
use Dompdf\Dompdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Libraries\UserDataHelper;


class SalesOrderReportController extends BaseController
{
    protected $salesOrderModel;
    protected $userModel;
    protected $headerModel;

    public function __construct()
    {
        $this->salesOrderModel = new SalesOrderModel();
        $this->userModel = new UserModel();
        $this->headerModel = new HeaderModel();
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
		$session = session();
		if (!$session->get('logged_in')) {
			return redirect()->to('login');
		}

		$filters = [
			'from'    => $this->request->getGet('from'),
			'to'      => $this->request->getGet('to'),
			'item'    => $this->request->getGet('item'),
			'cashier' => $this->request->getGet('cashier'),
		];

		// Fetch distinct cashiers who have recorded sales
		$db = \Config\Database::connect();
		$cashiers = $db->table('users')
			->select('users.user_id, users.username')
			->join('sales', 'sales.cashier_id = users.user_id', 'inner')
			->groupBy('users.user_id')
			->orderBy('users.username', 'asc')
			->get()
			->getResultArray();

		// Get sales orders filtered
		$salesOrderModel = new SalesOrderModel();
		$salesOrders = $salesOrderModel->getSalesOrderHistory($filters);

		// Prepare view data
		$data = $this->loadUserData() + [
			'filters'     => $filters,
			'salesOrders' => $salesOrders,
			'cashiers'    => $cashiers,
		];

		return view('reports/sales_order_report', $data);
	}

    public function exportPdf()
    {
        $filters = [
            'from' => $this->request->getGet('from'),
            'to' => $this->request->getGet('to'),
            'item' => $this->request->getGet('item'),
            'cashier' => $this->request->getGet('cashier'),
        ];

        $salesOrders = $this->salesOrderModel->getSalesOrderHistory($filters);

        $dompdf = new Dompdf();
        $html = view('reports/exports/sales_order_pdf', ['salesOrders' => $salesOrders]);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        $dompdf->stream('sales_order_report.pdf', ['Attachment' => true]);
    }

    public function exportWord()
    {
        $filters = [
            'from' => $this->request->getGet('from'),
            'to' => $this->request->getGet('to'),
            'item' => $this->request->getGet('item'),
            'cashier' => $this->request->getGet('cashier'),
        ];

        $salesOrders = $this->salesOrderModel->getSalesOrderHistory($filters);

        $phpWord = new PhpWord();
        $section = $phpWord->addSection();
        $section->addText("Sales Order Report", ['bold' => true, 'size' => 16]);

        foreach ($salesOrders as $orderId => $items) {
            $section->addText("Order #{$orderId}", ['bold' => true]);

            foreach ($items as $item) {
                $section->addText(" - {$item->item_name}, Qty: {$item->quantity}, Price: Ksh {$item->sellingPrice}, Cashier: {$item->cashier}, Date: {$item->created_at}");
            }
        }

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename=\"sales_order_report.docx\"");

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save("php://output");
    }

    public function exportExcel()
    {
        $filters = [
            'from' => $this->request->getGet('from'),
            'to' => $this->request->getGet('to'),
            'item' => $this->request->getGet('item'),
            'cashier' => $this->request->getGet('cashier'),
        ];

        $salesOrders = $this->salesOrderModel->getSalesOrderHistory($filters);

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'Order #');
        $sheet->setCellValue('B1', 'Item');
        $sheet->setCellValue('C1', 'Quantity');
        $sheet->setCellValue('D1', 'Price');
        $sheet->setCellValue('E1', 'Cashier');
        $sheet->setCellValue('F1', 'Date');

        $row = 2;
        foreach ($salesOrders as $orderId => $items) {
            foreach ($items as $item) {
                $sheet->setCellValue("A{$row}", $orderId);
                $sheet->setCellValue("B{$row}", $item->item_name);
                $sheet->setCellValue("C{$row}", $item->quantity);
                $sheet->setCellValue("D{$row}", $item->sellingPrice);
                $sheet->setCellValue("E{$row}", $item->cashier);
                $sheet->setCellValue("F{$row}", $item->created_at);
                $row++;
            }
        }

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="sales_order_report.xlsx"');

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
    }
}
