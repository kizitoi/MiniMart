<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use App\Libraries\UserDataHelper;


class ProfileController extends Controller
{

      public function index()
      {

        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }
          $userModel = new UserModel();

          $userModel = new \App\Models\UserModel();
              $headerModel = new \App\Models\HeaderModel();
              $userId = session()->get('user_id');
              $user = $userModel->find($userId);
              //'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),


          $user = $userModel->getUserById($session->get('user_id'));

          if (!$session->get('logged_in'))
          {
              return redirect()->to('login');
          }

            $data = [
                'username' => $session->get('username'),
                'email' => $session->get('email'),
                'mobile' => $session->get('mobile'),
                'name' => $this->request->getPost('name'),
                'profile_image' => $session->get('profile_image'),
                 'user' => $user,
                'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
            ];

          return view('profile/edit',  $data );
      }

      public function viewImage($id)
  {

    $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }

    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }

      $picture = $this->pictureModel->find($id);

      if (!$picture) {
          throw new \CodeIgniter\Exceptions\PageNotFoundException('Picture not found');
      }

      $path = $picture['image_path'];

      if (!file_exists($path)) {
          throw new \CodeIgniter\Exceptions\PageNotFoundException('Image not found');
      }

      // Get the file content and mime type
      $mimeType = mime_content_type($path);
      $response = response()
          ->setHeader('Content-Type', $mimeType)
          ->setBody(file_get_contents($path));

      return $response;
  }



public function updateProfile()
{

  $session = session();
  if (!$session->get('logged_in')) {
      return redirect()->to('login');
  }
    $userModel = new UserModel();
    $user_id = $session->get('user_id');
    $user = $userModel->getUserById($user_id);

    $data = [
        'username'    => $this->request->getPost('username'),
        'email'       => $this->request->getPost('email'),
        'mobile'      => $this->request->getPost('mobile'),
        'name'        => $this->request->getPost('name'),
        'designation' => $this->request->getPost('designation'),
        'updated_at'  => date('Y-m-d H:i:s')
    ];

    // Define private directory path
    $privateDir = "/home/nairobimetaldetectors/private/pos/profile_pictures/{$user_id}/";

    // Create user directory if not exists
    if (!is_dir($privateDir)) {
        mkdir($privateDir, 0755, true);
    }

    // PROFILE IMAGE
    $profileImage = $this->request->getFile('profile_image');
    if ($profileImage && $profileImage->isValid() && !$profileImage->hasMoved()) {
        $profileName = 'profile.' . $profileImage->getClientExtension();
        $profilePath = $privateDir . $profileName;

        // Delete old if exists
        if (!empty($user['profile_image']) && file_exists($user['profile_image'])) {
            unlink($user['profile_image']);
        }

        $profileImage->move($privateDir, $profileName);
        $data['profile_image'] = $profilePath;
    }

    // SIGNATURE IMAGE
    $signature = $this->request->getFile('signature');
    if ($signature && $signature->isValid() && !$signature->hasMoved()) {
        $signatureName = 'signature.' . $signature->getClientExtension();
        $signaturePath = $privateDir . $signatureName;

        // Delete old if exists
        if (!empty($user['signature_link']) && file_exists($user['signature_link'])) {
            unlink($user['signature_link']);
        }

        $signature->move($privateDir, $signatureName);
        $data['signature_link'] = $signaturePath;
    }

    // Update user
    $userModel->updateUser($user_id, $data);

    $session->setFlashdata('success', 'Profile updated successfully.');
    return redirect()->to('/profile');
}

public function viewPrivateImage($user_id, $type)
{
  $session = session();
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
{
  $session = session();
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


    public function changePassword()
    {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $userModel = new UserModel();
        $user_id = $session->get('user_id');

        $currentPassword = $this->request->getPost('current_password');
        $newPassword = $this->request->getPost('new_password');
        $confirmPassword = $this->request->getPost('confirm_password');

        $user = $userModel->getUserById($user_id);

        if (!password_verify($currentPassword, $user['password'])) {
            return redirect()->back()->with('error', 'Current password is incorrect.');
        }

        if ($newPassword !== $confirmPassword) {
            return redirect()->back()->with('error', 'New passwords do not match.');
        }

        $userModel->updateUser($user_id, [
            'password' => password_hash($newPassword, PASSWORD_BCRYPT),
            'updated_at' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/profile')->with('success', 'Password updated successfully.');
    }
}
