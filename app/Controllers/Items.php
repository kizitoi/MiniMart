<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ItemModel;
use App\Models\ItemCategoryModel;
use App\Models\VatSettingsModel;
use App\Models\NumberingModel;
use App\Models\UserModel;
use App\Models\HeaderModel;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpWord\PhpWord;
use Dompdf\Dompdf;
use Picqer\Barcode\BarcodeGeneratorPNG;
use Picqer\Barcode\BarcodeGeneratorSVG;
use App\Models\BarcodeLabelSizeModel;
use App\Libraries\UserDataHelper;



class Items extends BaseController
{
    protected $itemModel;
    protected $categoryModel;
    protected $vatModel;
    protected $numberingModel;

    protected $itemCategoryModel;
    protected $userModel;
  //  protected $shopModel;
    protected $db;


    public function __construct()
    {
        $this->itemModel = new ItemModel();
        $this->categoryModel = new ItemCategoryModel();
        $this->vatModel = new VatSettingsModel();
        $this->numberingModel = new NumberingModel();

        $this->itemCategoryModel = new ItemCategoryModel();
        $this->userModel = new UserModel();
    //    $this->shopModel = new ShopModel();
        $this->db = \Config\Database::connect();
    }

    public function searchItemAjax()
{
    $filter = trim($this->request->getGet('filter'));
    $db = \Config\Database::connect();
    $builder = $db->table('items');
    $builder->select('items.*, item_categories.shop_id');
    $builder->join('item_categories', 'item_categories.id = items.category_id', 'left');

    if (!empty($filter)) {
        $builder->groupStart()
            ->like('items.name', $filter)
            ->orWhere('items.item_no', $filter)
            ->orWhere('items.scanned_code', $filter)
            ->orWhere('items.id', $filter)
            ->groupEnd();
    }

    $builder->limit(2); // we only care if exactly one match

    $query = $builder->get();
    $items = $query->getResultArray();

    return $this->response->setJSON($items);
}


public function mobile()
{
    $helper = new \App\Libraries\UserDataHelper();
    $search = $this->request->getGet('search');

    $query = $this->itemModel;
    if ($search) {
        $query = $query->like('name', $search)
                       ->orLike('category', $search);
    }

    $perPage = 10;
    $data = $helper->load() + [
        'items'  => $query->paginate($perPage, 'items'),
        'pager'  => $this->itemModel->pager,
        'search' => $search,
    ];

    return view('items/mobile_index', $data);
}


    public function expressSale()
{
    $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }

    $filter = trim($this->request->getGet('filter')); // sanitize input

    $db = \Config\Database::connect();
    $builder = $db->table('items');
    $builder->select('items.*, GROUP_CONCAT(CONCAT(unit_of_measure.unit_name, " : ", items_units_of_measure.unit_value) SEPARATOR ", ") as units, item_categories.shop_id as shop_id');
    $builder->join('items_units_of_measure', 'items_units_of_measure.item_id = items.id', 'left');
    $builder->join('unit_of_measure', 'unit_of_measure.id = items_units_of_measure.unit_id', 'left');
    $builder->join('item_categories', 'item_categories.id = items.category_id', 'left');


   if (!empty($filter)) {
    $filter = trim($filter); // sanitize
    $builder->groupStart()
        ->like('items.name', $filter)
        //->orWhere('items.item_no', $filter)
        //>orWhere('items.id', $filter)
        ->orWhere('REPLACE(REPLACE(items.item_no, "\r", ""), "\n", "") =', $filter)
      //  ->orWhere('REPLACE(REPLACE(items.id, "\r", ""), "\n", "") =', $filter)
        ->orWhere('REPLACE(REPLACE(items.scanned_code, "\r", ""), "\n", "") =', $filter)
        ->orLike('CONCAT(items.item_no, " ", items.name)', $filter)
        ->groupEnd();
}


   $builder->groupBy('items.id');
    $query = $builder->get();
    $userId = $session->get('user_id');
    $existingOrder = $this->db->table('sales_orders')
        ->where('created_by_user_id', $userId)
        ->where('status', 'new')
        ->get()
        ->getRow();

        $helper = new UserDataHelper();
        $data = $helper->load($existingOrder) + [
        'pager' => $this->itemModel->pager,
        'filter' => $filter,
        'items' => $query->getResultArray(),
        'category_name' => 'Express Sale',
        'existing_order' => $existingOrder,
    ];

    return view('items/express_sale', $data);
}



	public function byCategory($categoryId)
	{
		$session = session();
		if (!$session->get('logged_in')) {
		return redirect()->to('login');
		}
		$filter = $this->request->getGet('filter');

			$itemModel = new \App\Models\ItemModel();


		$db = \Config\Database::connect();
		$builder = $db->table('items');
		$builder->select('items.*, GROUP_CONCAT(CONCAT(unit_of_measure.unit_name, " : ", items_units_of_measure.unit_value) SEPARATOR ", ") as units, item_categories.shop_id as shop_id');
		$builder->join('items_units_of_measure', 'items_units_of_measure.item_id = items.id', 'left');
		$builder->join('unit_of_measure', 'unit_of_measure.id = items_units_of_measure.unit_id', 'left');
		$builder->join('item_categories', 'item_categories.id = items.category_id', 'left');
		$builder->where('items.category_id', $categoryId);

		if (!empty($filter)) {
		$builder->like('items.name', $filter);
		}

		$builder->groupBy('items.id');

		$query = $builder->get();

		$data['items'] = $query->getResultArray();
		$data['filter'] = $filter;

		$categoryModel = new \App\Models\ItemCategoryModel();
		$category = $categoryModel->find($categoryId);
		//  $data['category_name'] = $category['category_name'] ?? 'Selected Category';

		$userId = $session->get('user_id'); // get user ID directly

		// ✅ Check for existing open (new) order
		$existingOrder = $this->db->table('sales_orders')
		->where('created_by_user_id', $userId)
		->where('status', 'new')
		->get()
		->getRow();


    $helper = new UserDataHelper();
    $data = $helper->load($existingOrder); // optional param


    $data = $helper->load() + [
			'pager' => $this->itemModel->pager,
			'filter' => $filter,
		  //  'items' => $query->paginate(20),
			'items' => $query->getResultArray(),
			'filter' => $filter,
			//'pager' => $itemModel->pager,
			'category_name' => $category['category_name'] ?? 'Selected Category',
			'existing_order' => $existingOrder, // pass it to the view
		];

		return view('items/by_category', $data);
	}


public function generate_barcodes()
{
    $barcodeType = $this->request->getPost('barcode_type');
    $startId     = $this->request->getPost('start_item');
    $endId       = $this->request->getPost('end_item');
    $layoutStyle = $this->request->getPost('layout_style');
    $customWidth = $this->request->getPost('custom_width');
    $customHeight = $this->request->getPost('custom_height');

    if (!$startId || !$endId || $startId > $endId) {
        return redirect()->back()->with('error', 'Invalid item range selected.');
    }

    $items = $this->itemModel
        ->where('id >=', $startId)
        ->where('id <=', $endId)
        ->orderBy('id', 'ASC')
        ->findAll();

    if (empty($items)) {
        return redirect()->back()->with('error', 'No items found in the selected range.');
    }

    $generator = new \Picqer\Barcode\BarcodeGeneratorPNG();
    $html = '<style>
      .label-grid { display: flex; flex-wrap: wrap; width: 100%; }
      .label { margin: 3mm; padding: 2mm; text-align: center; font-size: 11px; box-sizing: border-box; border: 1px dashed #ccc; }
      .label img { height: 50px; margin-top: 4px; }
    </style>';

    // Convert mm to pt (1 mm = ~2.835 pt)
    $ptWidth  = $customWidth ? round($customWidth * 2.835) : 180; // default ~64mm
    $ptHeight = $customHeight ? round($customHeight * 2.835) : 80; // default ~25mm

    if ($layoutStyle === 'custom') {
        $html .= '<div class="label-grid">';
        foreach ($items as $item) {
            $code = match ($barcodeType) {
                'item_no'     => $item['item_no'],
                'name'        => $item['name'],
                'item_id'     => $item['id'],
                'item_id_no'  => $item['id'] . $item['item_no'],
                default       => $item['item_no'] . $item['name']
            };

            $label = $code . ' ' . $item['name'];
            $barcode = base64_encode($generator->getBarcode($code, $generator::TYPE_CODE_128));

            $html .= "<div class='label' style='width: {$ptWidth}pt; height: {$ptHeight}pt;'>
                        <div><strong>{$label}</strong></div>
                        <img src='data:image/png;base64,{$barcode}' alt='Barcode'>
                    </div>";
        }
        $html .= '</div>';
    } else {
        // Reuse your original grid or flow layout logic here...
    }

    $dompdf = new \Dompdf\Dompdf();
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();
    $dompdf->stream("item_barcodes.pdf", ['Attachment' => true]);
}


	public function index()
	{
		$session = session();
		if (!$session->get('logged_in')) {
			return redirect()->to('login');
		}

		$filter = $this->request->getGet('filter');
		$reorderOnly = $this->request->getGet('reorder_only');
		$categoryId = $this->request->getGet('category_id');
		$perPage = 200;

if ($filter) {
    $filter = trim($filter); // clean input
$this->itemModel->groupStart()
    ->Where('items.name', $filter)
    //->orWhere('items.id', $filter)
    ->orWhere('items.item_no', $filter)
    ->orWhere('items.description', $filter)
    ->orLike('items.scanned_code', $filter)
    ->groupEnd();
}




		if ($reorderOnly) {
			$this->itemModel->where('items.quantity <= items.reorder_level_quantity');
		}

		if ($categoryId) {
			$this->itemModel->where('items.category_id', $categoryId);
		}

		$items = $this->itemModel->getItemsWithCategory($perPage);

		// Load categories
		$categoryModel = new \App\Models\ItemCategoryModel();
		$categories = $categoryModel->findAll();

    $sizeModel = new BarcodeLabelSizeModel();


    $helper = new UserDataHelper();
    $data = $helper->load()  + [
			'items' => $items,
			'pager' => $this->itemModel->pager,
			'filter' => $filter,
			'reorderOnly' => $reorderOnly,
			'categoryId' => $categoryId,
			'categories' => $categories,
      'saved_sizes' => $sizeModel->findAll()
		];

		return view('items/index', $data);
	}


  public function save_label_size()
  {
      $name = $this->request->getPost('name');
      $width = $this->request->getPost('width');
      $height = $this->request->getPost('height');

      if (!$name || !$width || !$height) {
          return redirect()->back()->with('error', 'All fields are required.');
      }

      $model = new \App\Models\BarcodeLabelSizeModel();
      $model->save([
          'name' => $name,
          'width_mm' => $width,
          'height_mm' => $height,
          'user_id' => session()->get('user_id') ?? null
      ]);

      return redirect()->back()->with('success', 'Label size saved successfully.');
  }


	public function export($format)
	{
		$filter = $this->request->getGet('filter');
		$items = $this->itemModel->getFilteredItems($filter);

		if ($format === 'pdf') {
			$dompdf = new Dompdf();
			$html = view('items/export_pdf', ['items' => $items]);
			$dompdf->loadHtml($html);
			$dompdf->setPaper('A4', 'landscape');
			$dompdf->render();
			$dompdf->stream("items.pdf", ["Attachment" => 1]);
		}

		elseif ($format === 'word') {
			$phpWord = new PhpWord();
			$section = $phpWord->addSection();
			$table = $section->addTable();
			$table->addRow();
			$table->addCell()->addText('Item No');
			$table->addCell()->addText('Name');
			$table->addCell()->addText('Category');
			$table->addCell()->addText('Qty');
			$table->addCell()->addText('Reorder');
			$table->addCell()->addText('Price');

			foreach ($items as $item) {
				$table->addRow();
				$table->addCell()->addText($item['item_no']);
				$table->addCell()->addText($item['name']);
				$table->addCell()->addText($item['category_name']);
				$table->addCell()->addText($item['quantity']);
				$table->addCell()->addText($item['reorder_level_quantity']);
				$table->addCell()->addText(number_format($item['unit_price'], 2));
			}

			header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
			header('Content-Disposition: attachment;filename="items.docx"');
			header('Cache-Control: max-age=0');
			$writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
			$writer->save('php://output');
			exit;
		}

		elseif ($format === 'excel') {
			$spreadsheet = new Spreadsheet();
			$sheet = $spreadsheet->getActiveSheet();
			$sheet->setCellValue('A1', 'Item No');
			$sheet->setCellValue('B1', 'Name');
			$sheet->setCellValue('C1', 'Category');
			$sheet->setCellValue('D1', 'Qty');
			$sheet->setCellValue('E1', 'Reorder');
			$sheet->setCellValue('F1', 'Price');

			$row = 2;
			foreach ($items as $item) {
				$sheet->setCellValue("A{$row}", $item['item_no']);
				$sheet->setCellValue("B{$row}", $item['name']);
				$sheet->setCellValue("C{$row}", $item['category_name']);
				$sheet->setCellValue("D{$row}", $item['quantity']);
				$sheet->setCellValue("E{$row}", $item['reorder_level_quantity']);
				$sheet->setCellValue("F{$row}", $item['unit_price']);
				$row++;
			}

			header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
			header('Content-Disposition: attachment;filename="items.xlsx"');
			header('Cache-Control: max-age=0');
			$writer = new Xlsx($spreadsheet);
			$writer->save('php://output');
			exit;
		}
	}




    public function create()
	{
		$session = session();
		if (!$session->get('logged_in')) {
		  return redirect()->to('login');
		}
		$numbering = $this->numberingModel->where('apply_to', 'Items')->first();
		$prefix = $numbering['prefix'];
		$lastUsed = (int) $numbering['last_used'];
		$item_no = $prefix . ($lastUsed + 1);

    $helper = new UserDataHelper();
    $data = $helper->load() + [
			'categories' => $this->categoryModel->findAll(),
			'vat_settings' => $this->vatModel->findAll(),
			'item_no' => $item_no,
		];

		return view('items/create', $data);
	}

    public function store()
	{
		$session = session();
		if (!$session->get('logged_in')) {
		  return redirect()->to('login');
		}
		helper(['form', 'url']);

		$numbering = $this->numberingModel->where('apply_to', 'Items')->first();
		$prefix = $numbering['prefix'];
		$lastUsed = (int) $numbering['last_used'];
		$item_no = $prefix . ($lastUsed + 1);

		$photoPath = null;
		$photo = $this->request->getFile('photo');

		if ($photo && $photo->isValid()) {
			$newName = 'item_' . time() . '.' . $photo->getExtension();
			$photo->move(FCPATH . 'uploads/items', $newName);
			$photoPath = 'uploads/items/' . $newName;
		}

		$this->itemModel->insert([
			'item_no' => $item_no,
			'category_id' => $this->request->getPost('category_id'),
			'name' => $this->request->getPost('name'),
			'description' => $this->request->getPost('description'),
	        'scanned_code' => $this->request->getPost('scanned_code'),
			'quantity' => $this->request->getPost('quantity'),
			'unit_price' => $this->request->getPost('unit_price'),
			'reorder_level_quantity' => $this->request->getPost('reorder_level_quantity'),
			'photo' => $photoPath,
			'vatable' => $this->request->getPost('vatable') ? 1 : 0,
			'vat_id' => $this->request->getPost('vat_id'),
		]);

		$this->numberingModel->where('apply_to', 'Items')->set('last_used', $lastUsed + 1)->update();

		return redirect()->to('/items')->with('success', 'Item created successfully...');
	}

	public function edit($id)
	{
		$session = session();
		if (!$session->get('logged_in')) {
		  return redirect()->to('login');
		}
		$item = $this->itemModel->find($id);

    $helper = new UserDataHelper();
    $data = $helper->load() + [
			'item' => $item,
			'categories' => $this->categoryModel->findAll(),
			'vat_settings' => $this->vatModel->findAll(),
		];

		return view('items/edit', $data);
	}

    public function update($id)
    {
		$session = session();
		if (!$session->get('logged_in')) {
		  return redirect()->to('login');
		}
		helper(['form', 'url']);

		$item = $this->itemModel->find($id);
		$photo = $this->request->getFile('photo');
		$photoPath = $item['photo']; // Keep old photo

		if ($photo && $photo->isValid()) {
			// Delete old photo if exists
			if (!empty($photoPath) && is_file(FCPATH . $photoPath)) {
				unlink(FCPATH . $photoPath);
			}

			$newName = 'item_' . time() . '.' . $photo->getExtension();
			$photo->move(FCPATH . 'uploads/items', $newName);
			$photoPath = 'uploads/items/' . $newName;
		}

		$this->itemModel->update($id, [
			'category_id' => $this->request->getPost('category_id'),
			'name' => $this->request->getPost('name'),
			'description' => $this->request->getPost('description'),
			'scanned_code' => $this->request->getPost('scanned_code'),
			'quantity' => $this->request->getPost('quantity'),
			'unit_price' => $this->request->getPost('unit_price'),
			'reorder_level_quantity' => $this->request->getPost('reorder_level_quantity'),
			'photo' => $photoPath,
			'vatable' => $this->request->getPost('vatable') ? 1 : 0,
			'vat_id' => $this->request->getPost('vat_id'),
		]);

		return redirect()->to('/items')->with('success', 'Item updated successfully.');
	}

    public function delete($id)
    {
		$session = session();
		if (!$session->get('logged_in')) {
		  return redirect()->to('login');
		}
		$item = $this->itemModel->find($id);

		if ($item && !empty($item['photo']) && is_file(FCPATH . $item['photo'])) {
			unlink(FCPATH . $item['photo']);
		}

		$this->itemModel->delete($id);

		return redirect()->to('/items')->with('success', 'Item deleted successfully.');
	}

    public function add()
	{
		$session = session();
		if (!$session->get('logged_in')) {
			return redirect()->to('login');
		}
		$data = $this->request->getJSON(true);
		$userId = session()->get('user_id');
		$userName = session()->get('username');

		// Get or create active sales_order
		$order = $this->db->table('sales_orders')
			->where('created_by_user_id', $userId)
			->where('status', 'new')
			->get()
			->getRow();

		if (!$order) {
			return $this->response->setJSON(['status' => 'error', 'message' => 'No active order. Please create one first.']);
		}

		$item = $this->db->table('items')->where('id', $data['item_id'])->get()->getRow();

		if (!$item) {
			return $this->response->setJSON(['status' => 'error', 'message' => 'Item not found.']);
		}

		$total = $item->unit_price * $data['quantity'];

		$this->db->table('sales')->insert([
			'sales_order_id' => $order->id,
			'category_id' => $data['category_id'],
			'item_id' => $data['item_id'],
			'item_name' => $item->name,
			'user_id' => $userId,
			'quantity' => $data['quantity'],
			'sellingPrice' => $item->unit_price,
			'shop_id' => $data['shop_id'],
			'total_cost' => $total,
			'vat_code' => 'VAT16', // example
			'vat_rate' => 16.00,
			'vat_amnt' => $total * 0.16,
			'vatable_amnt' => $total,
		]);

		return $this->response->setJSON(['status' => 'success']);
	}

}
