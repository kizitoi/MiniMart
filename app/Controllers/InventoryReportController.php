<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\HeaderModel;
use App\Models\ItemModel;
use App\Models\ItemCategoryModel;
use CodeIgniter\Controller;
use Dompdf\Dompdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Libraries\UserDataHelper;

class InventoryReportController extends BaseController
{
    protected $itemModel;
    protected $itemCategoryModel;
    protected $userModel;

    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->itemCategoryModel = new ItemCategoryModel();
        $this->userModel = new UserModel();
    }

    private function loadUserData()
    {
        $session = session();
        $headerModel = new HeaderModel();
        $userId = $session->get('user_id');

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
        $viewData = $this->loadUserData();

        // Get the filter and search values
        $categoryId = $this->request->getGet('category_id');  // Get category filter
        $search = $this->request->getGet('search');  // Get search value

        // Start query builder
        $builder = $this->itemModel
            ->select('items.*, item_categories.category_name')
            ->join('item_categories', 'item_categories.id = items.category_id', 'left');

        // Apply category filter if category is selected
        if ($categoryId) {
            $builder->where('items.category_id', $categoryId);
        }

        // Apply search filter (if search query is provided)
        if ($search) {
            $builder->groupStart()
                ->like('items.item_no', $search)
                ->orLike('items.name', $search)
                ->orLike('items.description', $search)
                ->groupEnd();
        }

        // Fetch filtered items
        $items = $builder->findAll();

        // Fetch categories for the filter dropdown
        $categories = $this->itemCategoryModel->findAll();

        // Prepare filter data for view
        $filters = [
            'search' => $search,
            'category_id' => $categoryId,
        ];

        return view('reports/inventory_report', array_merge($viewData, [
            'items' => $items,
            'categories' => $categories,
            'selectedCategory' => $categoryId,
            'filters' => $filters,
        ]));
    }



    public function export($type)
    {
        $categoryId = $this->request->getGet('category_id');

        $items = $this->itemModel
            ->select('items.*, item_categories.category_name')
            ->when($categoryId, function ($query) use ($categoryId) {
                return $query->where('items.category_id', $categoryId);
            })
            ->join('item_categories', 'item_categories.id = items.category_id', 'left')
            ->findAll();

        switch ($type) {
            case 'pdf':
                return $this->exportPdf($items);
            case 'word':
                return $this->exportWord($items);
            case 'excel':
                return $this->exportExcel($items);
            default:
                return redirect()->to('/inventory-report');
        }
    }

    protected function exportPdf($items)
    {
        $html = view('reports/exports/inventory_pdf', ['items' => $items]);

        $dompdf = new Dompdf();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();
        $dompdf->stream("inventory_report.pdf", ["Attachment" => true]);
        exit;
    }

    protected function exportWord($items)
    {
        $phpWord = new PhpWord();
        $section = $phpWord->addSection();

        $section->addText('Inventory Report', ['bold' => true, 'size' => 16]);
        $table = $section->addTable();

        $headers = ['#', 'Item No', 'Item Name', 'Description', 'Item Category', 'Quantity', 'Reorder Qty', 'Unit Price'];
        $table->addRow();
        foreach ($headers as $head) {
            $table->addCell(2000)->addText($head);
        }

        $i = 1;
        foreach ($items as $item) {
            $table->addRow();
            $table->addCell(2000)->addText($i++);
            $table->addCell(2000)->addText($item['item_no']);
            $table->addCell(2000)->addText($item['name']);
            $table->addCell(3000)->addText($item['description']);
            $table->addCell(2000)->addText($item['category_name']);
            $table->addCell(2000)->addText($item['quantity']);
            $table->addCell(2000)->addText($item['reorder_level_quantity']);
            $table->addCell(2000)->addText($item['unit_price']);
        }

        header("Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document");
        header("Content-Disposition: attachment; filename=inventory_report.docx");

        $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
        $writer->save("php://output");
        exit;
    }

    protected function exportExcel($items)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        $headers = ['#', 'Item No', 'Item Name', 'Description', 'Item Category', 'Quantity', 'Reorder Qty', 'Unit Price'];
        $sheet->fromArray($headers, null, 'A1');

        $row = 2;
        $i = 1;

        foreach ($items as $item) {
            $sheet->setCellValue('A' . $row, $i++);
            $sheet->setCellValue('B' . $row, $item['item_no']);
            $sheet->setCellValue('C' . $row, $item['name']);
            $sheet->setCellValue('D' . $row, $item['description']);
            $sheet->setCellValue('E' . $row, $item['category_name']);
            $sheet->setCellValue('F' . $row, $item['quantity']);
            $sheet->setCellValue('G' . $row, $item['reorder_level_quantity']);
            $sheet->setCellValue('H' . $row, $item['unit_price']);
            $row++;
        }

        header("Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet");
        header("Content-Disposition: attachment;filename=\"inventory_report.xlsx\"");
        header("Cache-Control: max-age=0");

        $writer = new Xlsx($spreadsheet);
        $writer->save('php://output');
        exit;
    }
}
