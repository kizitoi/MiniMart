<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ItemModel;
use App\Models\HeaderModel;
use App\Models\UserModel;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Dompdf\Dompdf;
use App\Libraries\UserDataHelper;


class ReorderLevelReport extends BaseController
{
	public function index()
	{
		$session = session();
		$headerModel = new HeaderModel();
		$userModel = new UserModel();
		$itemModel = new ItemModel();
		$categoryModel = new \App\Models\ItemCategoryModel();

		$userId = $session->get('user_id');
		$search = $this->request->getGet('search');
		$categoryId = $this->request->getGet('category_id');

		$itemsQuery = $itemModel
			->select('items.*, item_categories.category_name')
			->join('item_categories', 'item_categories.id = items.category_id')
			->where('items.quantity <= items.reorder_level_quantity');

		if (!empty($search)) {
			$itemsQuery->groupStart()
					   ->like('items.name', $search)
					   ->orLike('items.item_no', $search)
					   ->groupEnd();
		}

		if (!empty($categoryId)) {
			$itemsQuery->where('items.category_id', $categoryId);
		}

		$items = $itemsQuery->findAll();

		$data = [
			'username'       => $session->get('username'),
			'name'           => $session->get('name'),
			'signature_link' => $session->get('signature_link'),
			'email'          => $session->get('email'),
			'mobile'         => $session->get('mobile'),
			'profile_image'  => $session->get('profile_image'),
			'header_links'   => $headerModel->getHeaderLinksByUser($userId),
			'items'          => $items,
			'search'         => $search,
			'category_id'    => $categoryId,
			'categories'     => $categoryModel->findAll()
		];

		return view('reports/items_by_reorder', $data);
	}

	public function export($format)
	{
		$itemModel = new ItemModel();
		$search = $this->request->getGet('search');
		$categoryId = $this->request->getGet('category_id');

		$itemsQuery = $itemModel
			->select('items.*, item_categories.category_name')
			->join('item_categories', 'item_categories.id = items.category_id')
			->where('items.quantity <= items.reorder_level_quantity');

		if (!empty($search)) {
			$itemsQuery->groupStart()
					   ->like('items.name', $search)
					   ->orLike('items.item_no', $search)
					   ->groupEnd();
		}

		if (!empty($categoryId)) {
			$itemsQuery->where('items.category_id', $categoryId);
		}

		$items = $itemsQuery->findAll();

		if ($format === 'pdf') {
			$dompdf = new Dompdf();
			$html = view('reports/exports/items_by_reorder_pdf', ['items' => $items]);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'landscape');
			$dompdf->render();
			$dompdf->stream('items_by_reorder_level_report.pdf');
			exit;
		}

		if ($format === 'word') {
			$phpWord = new PhpWord();
			$section = $phpWord->addSection();
			$table = $section->addTable();

			$headers = ['Item No', 'Item Name', 'Category', 'Qty', 'Reorder Level'];
			$table->addRow();
			foreach ($headers as $header) {
				$table->addCell()->addText($header);
			}

			foreach ($items as $item) {
				$table->addRow();
				$table->addCell()->addText($item['item_no']);
				$table->addCell()->addText($item['name']);
				$table->addCell()->addText($item['category_name']);
				$table->addCell()->addText($item['quantity']);
				$table->addCell()->addText($item['reorder_level_quantity']);
			}

			header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
			header('Content-Disposition: attachment;filename="items_by_reorder.docx"');
			header('Cache-Control: max-age=0');

			$writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
			$writer->save('php://output');
			exit;
		}

		if ($format === 'excel') {
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();

			$headers = ['Item No', 'Item Name', 'Category', 'Qty', 'Reorder Level'];
			$col = 'A';
			foreach ($headers as $header) {
				$sheet->setCellValue("{$col}1", $header);
				$col++;
			}

			$row = 2;
			foreach ($items as $item) {
				$sheet->setCellValue("A{$row}", $item['item_no']);
				$sheet->setCellValue("B{$row}", $item['name']);
				$sheet->setCellValue("C{$row}", $item['category_name']);
				$sheet->setCellValue("D{$row}", $item['quantity']);
				$sheet->setCellValue("E{$row}", $item['reorder_level_quantity']);
				$row++;
			}

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="items_by_reorder.xlsx"');
			header('Cache-Control: max-age=0');

			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
			exit;
		}

		return redirect()->back()->with('error', 'Unsupported export format');
	}

}
