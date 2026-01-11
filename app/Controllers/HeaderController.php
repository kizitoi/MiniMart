<?php
namespace App\Controllers;

use App\Models\HeaderModel;
use CodeIgniter\Controller;
use App\Libraries\UserDataHelper;
class HeaderController extends Controller
{



public function index()
{
    $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }

    $userModel = new \App\Models\UserModel();
    $headerModel = new \App\Models\HeaderModel();

    $userId = $session->get('user_id');
    $user = $userModel->find($userId);

    // Default value
    $companyName = 'Company';

    $companyId = session()->get('company_id') ?? null

  //  if (!empty($user['company_id'])) {
    if ($companyId) {
        $db = \Config\Database::connect();
        $company = $db->table('companies')
                      ->select('name')
                    //  ->where('id', $user['company_id'])
                      ->where('id', $companyId)
                      ->get()
                      ->getRow();

        if ($company) {
            $companyName = $company->name;
        }
    }

    $data = [
        'username'      => $user['username'],
        'name'          => $user['name'],
        'email'         => $user['email'],
        'mobile'        => $user['mobile'],
        'profile_image' => $user['profile_image'] ?? base_url('assets/img/default.png'),
        'companyName'   => $companyName,
        'header_links'  => $headerModel->getHeaderLinksByUser($user['user_id']),
    ];

    return view('header', $data);
}



public function viewPrivateImage($user_id, $type)
  {      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
    $allowedTypes = ['profile', 'signature'];
    if (!in_array($type, $allowedTypes)) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException("Image type not allowed.");
    }

    $path = "/home/nairobimetaldetectors/private/pos/profile_pictures/{$user_id}/{$type}.";

    // Try all supported extensions
    $extensions = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
    foreach ($extensions as $ext) {
        $fullPath = $path . $ext;
        if (file_exists($fullPath)) {
            $mime = mime_content_type($fullPath);
            header('Content-Type: ' . $mime);
            readfile($fullPath);
            exit;
        }
    }

    throw new \CodeIgniter\Exceptions\PageNotFoundException("Image not found.");
}


public function servePrivateImage($type, $userId)
{       $session = session();
  if (!$session->get('logged_in')) {
      return redirect()->to('login');
  }
    helper('filesystem');

    $validTypes = ['profile', 'signature'];
    if (!in_array($type, $validTypes)) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Invalid image type');
    }

    $baseDir = PRIVATE_STORAGE_PATH;
    $folder = $type === 'profile' ? 'profile_pictures' : 'signatures';
    $path = $baseDir . $folder . '/' . $userId . '/';

    // Get any image file in user's folder (you can improve this to get a specific name if needed)
    $files = get_filenames($path);
    if (empty($files)) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('Image not found');
    }

    $filePath = $path . $files[0];
    $mime = mime_content_type($filePath);

    return $this->response
        ->setHeader('Content-Type', $mime)
        ->setBody(file_get_contents($filePath))
        ->setCache(['max-age' => 3600, 'public' => false]);
}

public function getHeaderLinks()
   {
     $session = session();
   if (!$session->get('logged_in')) {
       return redirect()->to('login');
   }
     $userModel = new \App\Models\UserModel();
     $userId = session()->get('user_id');
     $user = $userModel->find($userId);

     $model = new HeaderModel();
     return $model->getHeaderLinks($user['role_id']);
   }

}
