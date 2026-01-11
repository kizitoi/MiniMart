<?php namespace App\Controllers;

use App\Models\ItemCategoryModel;
use App\Models\HeaderModel;
use App\Models\UserModel;
use App\Models\ShopModel;
use Dompdf\Dompdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use App\Libraries\UserDataHelper;

class ItemCategories extends BaseController
{
    protected $itemCategoryModel;
    protected $userModel;
    protected $shopModel;
    private $imagePath = 'uploads/category_images/'; // relative to /public/

    public function __construct()
    {
        $this->itemCategoryModel = new ItemCategoryModel();
        $this->userModel = new UserModel();
        $this->shopModel = new ShopModel();
    }


    public function export($format)
    {
        $categories = $this->itemCategoryModel->select('item_categories.*, shops.name as shop_name, COUNT(items.id) as item_count')
            ->join('shops', 'shops.id = item_categories.shop_id', 'left')
            ->join('items', 'items.category_id = item_categories.id', 'left')
            ->groupBy('item_categories.id')
            ->findAll();

        if ($format === 'pdf') {
            $dompdf = new \Dompdf\Dompdf();
            $html = view('item_categories/export_pdf', ['categories' => $categories]);
            $dompdf->loadHtml($html);
            $dompdf->setPaper('A4', 'landscape');
            $dompdf->render();
            $dompdf->stream('categories.pdf');
        } elseif ($format === 'word') {
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();
            $table = $section->addTable();
            $table->addRow();
            $table->addCell()->addText('ID');
            $table->addCell()->addText('Category Name');
            $table->addCell()->addText('Shop');
            $table->addCell()->addText('Item Count');
            foreach ($categories as $cat) {
                $table->addRow();
                $table->addCell()->addText($cat['id']);
                $table->addCell()->addText($cat['category_name']);
                $table->addCell()->addText($cat['shop_name']);
                $table->addCell()->addText($cat['item_count']);
            }
            header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
            header('Content-Disposition: attachment;filename="categories.docx"');
            header('Cache-Control: max-age=0');
            $writer = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $writer->save('php://output');
            exit;
        } elseif ($format === 'excel') {
            $spreadsheet = new Spreadsheet();
            $sheet = $spreadsheet->getActiveSheet();
            $sheet->setCellValue('A1', 'ID');
            $sheet->setCellValue('B1', 'Category Name');
            $sheet->setCellValue('C1', 'Shop');
            $sheet->setCellValue('D1', 'Item Count');

            $row = 2;
            foreach ($categories as $cat) {
                $sheet->setCellValue("A{$row}", $cat['id']);
                $sheet->setCellValue("B{$row}", $cat['category_name']);
                $sheet->setCellValue("C{$row}", $cat['shop_name']);
                $sheet->setCellValue("D{$row}", $cat['item_count']);
                $row++;
            }

            header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
            header('Content-Disposition: attachment;filename="categories.xlsx"');
            header('Cache-Control: max-age=0');

            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
            exit;
        }
    }



    private function loadUserData()
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
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
            'header_links' => $headerModel->getHeaderLinksByUser($user['user_id'])
        ];
    }

  /*  public function index()
      {     $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }
        $helper = new UserDataHelper(); $data = $helper->load(); // optional param
        $shopId = $this->request->getGet('shop_id');

        $categoryQuery = $this->itemCategoryModel
            ->select('item_categories.*, shops.name as shop_name')
            ->join('shops', 'shops.id = item_categories.shop_id');

        if ($shopId) {
            $categoryQuery->where('item_categories.shop_id', $shopId);
        }

        //$data['categories'] = $categoryQuery->findAll();
        $data['categories'] = $this->itemCategoryModel->getItemCategoriesWithItemCount($shopId);
        $data['selected_shop'] = $shopId;
        $data['shops'] = $this->shopModel->findAll();

        return view('item_categories/index', $data);
    }*/

	public function index()
	{
		$session = session();
		if (!$session->get('logged_in')) {
			return redirect()->to('login');
		}

		$helper = new UserDataHelper(); $data = $helper->load(); // optional param
		$shopId = $this->request->getGet('shop_id');
		$search = $this->request->getGet('search');

		$builder = $this->itemCategoryModel->select('item_categories.*, shops.name as shop_name, COUNT(items.id) as item_count')
			->join('shops', 'shops.id = item_categories.shop_id', 'left')
			->join('items', 'items.category_id = item_categories.id', 'left')
			->groupBy('item_categories.id');

		if ($shopId) {
			$builder->where('item_categories.shop_id', $shopId);
		}

		if ($search) {
			$builder->like('item_categories.category_name', $search);
		}

		$data['categories'] = $builder->findAll();
		$data['selected_shop'] = $shopId;
		$data['search'] = $search;
		$data['shops'] = $this->shopModel->findAll();

		return view('item_categories/index', $data);
	}






    public function create()
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $helper = new UserDataHelper(); $data = $helper->load(); // optional param
        $data['shops'] = $this->shopModel->findAll();

        return view('item_categories/create', $data);
    }

    public function store()
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $data = [
            'category_name' => $this->request->getPost('category_name'),
            'shop_id' => $this->request->getPost('shop_id'),
        ];

        $this->itemCategoryModel->save($data);
        $categoryId = $this->itemCategoryModel->getInsertID();

        // Handle image upload
        $this->handleCategoryImageUpload($categoryId);

        return redirect()->to('/item_categories')->with('success', 'Category added!');
    }

    public function edit($id)
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $helper = new UserDataHelper(); $data = $helper->load(); // optional param
        $data['category'] = $this->itemCategoryModel->find($id);
        $data['shops'] = $this->shopModel->findAll();

        return view('item_categories/edit', $data);
    }

    public function update($id)
    {     $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $postData = [
            'category_name' => $this->request->getPost('category_name'),
            'shop_id' => $this->request->getPost('shop_id'),
        ];

        // Only update if $postData is not empty
        if (!empty(array_filter($postData))) {
            $this->itemCategoryModel->update($id, $postData);
        }

        // Handle image upload
        $this->handleCategoryImageUpload($id);

        return redirect()->to('/item_categories')->with('success', 'Category updated!');
    }

    public function delete($id)
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $category = $this->itemCategoryModel->find($id);

        // Delete image file if it exists
        if (!empty($category['category_image'])) {
            $oldPath = FCPATH . $this->imagePath . $category['category_image'];
            if (is_file($oldPath)) {
                unlink($oldPath);
            }
        }

        $this->itemCategoryModel->delete($id);
        return redirect()->to('/item_categories')->with('success', 'Category deleted.');
    }

    protected function handleCategoryImageUpload($categoryId)
    {      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $file = $this->request->getFile('category_image');

        if ($file && $file->isValid() && !$file->hasMoved()) {
            $category = $this->itemCategoryModel->find($categoryId);

            // Delete old image if it exists
            if (!empty($category['category_image'])) {
                $oldPath = FCPATH . $this->imagePath . $category['category_image'];
                if (is_file($oldPath)) {
                    unlink($oldPath);
                }
            }

            $ext = $file->getExtension();
            $newName = 'category_' . $categoryId . '_' . time() . '.' . $ext;

            if ($file->move(FCPATH . $this->imagePath, $newName)) {
                if ($newName !== $category['category_image']) {
                    $this->itemCategoryModel->update($categoryId, [
                        'category_image' => $newName
                    ]);
                }
            }
        }
    }
}
