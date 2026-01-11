<?php

namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;
use CodeIgniter\Email\Email;
use App\Models\shopModel;
use App\Libraries\UserDataHelper;
use Config\Database;
use App\Models\RoleModel;
use App\Models\HeaderModel;


class UserController extends Controller
{
    protected $userModel;
    protected $roleModel;
    protected $shopModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new \App\Models\RoleModel(); // Assuming RoleModel is already created
        $this->shopModel = new \App\Models\ShopModel(); // Assuming RoleModel is already created
    }

/*
    // List all users
    public function index()
    {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }

        $userModel = new \App\Models\UserModel();
        $headerModel = new \App\Models\HeaderModel();
        $userId = session()->get('user_id');
        $user = $userModel->find($userId);
        //'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),


        $companyName = 'Company';

        if (!empty($user) && !empty($user['company_id'])) {
            $db = Database::connect();
            $company = $db->table('companies')
                          ->select('name')
                          ->where('id', $user['company_id'])
                          ->get()
                          ->getRow();

            if ($company) {
                $companyName = $company->name;
            }
        }

        $data = [
            'username' => $session->get('username'),
            'name' => $session->get('name'),
            'signature_link' => $session->get('signature_link'),
            'email' => $session->get('email'),
            'mobile' => $session->get('mobile'),
            'profile_image' => $session->get('profile_image'),
            'users' => $this->userModel->getUsers(),
            'roles' => $this->roleModel->findAll(),
            'shops' => $this->shopModel->findAll(),
            'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
            'companyName'       => $companyName,

        ];



        return view('users/index', $data);
    }
*/

/*
public function index()
{
    $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }

    $userModel = new \App\Models\UserModel();
    $headerModel = new \App\Models\HeaderModel();
    $userId = session()->get('user_id');
    $user = $userModel->find($userId);

    $companyName = 'Company';

    if (!empty($user) && !empty($user['company_id'])) {
        $db = \Config\Database::connect();
        $company = $db->table('companies')
                      ->select('name')
                      ->where('id', $user['company_id'])
                      ->get()
                      ->getRow();

        if ($company) {
            $companyName = $company->name;
        }
    }

    // Get filter values from query params
    $filterRole = $this->request->getGet('role_id');
    $filterShop = $this->request->getGet('shop_id');

    // Get users with filters
    $users = $this->userModel->getUsers($filterRole, $filterShop);

    $data = [
        'username' => $session->get('username'),
        'name' => $session->get('name'),
        'signature_link' => $session->get('signature_link'),
        'email' => $session->get('email'),
        'mobile' => $session->get('mobile'),
        'profile_image' => $session->get('profile_image'),
        'users' => $users,
        'roles' => $this->roleModel->findAll(),
        'shops' => $this->shopModel->findAll(),
        'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
        'companyName' => $companyName,
        'filterRole' => $filterRole,
        'filterShop' => $filterShop,
    ];

    return view('users/index', $data);
}

*/

public function index()
{
    $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }

    $userModel = new \App\Models\UserModel();
    $headerModel = new \App\Models\HeaderModel();
    $userId = session()->get('user_id');
    $user = $userModel->find($userId);

    $companyName = 'Company';

    if (!empty($user) && !empty($user['company_id'])) {
        $db = \Config\Database::connect();
        $company = $db->table('companies')
                      ->select('name')
                      ->where('id', $user['company_id'])
                      ->get()
                      ->getRow();
        if ($company) {
            $companyName = $company->name;
        }
    }

    // Filters from GET
    $filterRole = $this->request->getGet('role_id');
    $filterShop = $this->request->getGet('shop_id');
    $search = $this->request->getGet('search');

    // Pass filters to model
    $users = $this->userModel->getUsers($filterRole, $filterShop, $search);

    $data = [
        'username' => $session->get('username'),
        'name' => $session->get('name'),
        'signature_link' => $session->get('signature_link'),
        'email' => $session->get('email'),
        'mobile' => $session->get('mobile'),
        'profile_image' => $session->get('profile_image'),
        'users' => $users,
        'roles' => $this->roleModel->findAll(),
        'shops' => $this->shopModel->findAll(),
        'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
        'companyName' => $companyName,
        'filterRole' => $filterRole,
        'filterShop' => $filterShop,
        'search' => $search
    ];

    return view('users/index', $data);
}


    public function sendEmailVerification($user)
    {
        $email = \Config\Services::email();
        $token = bin2hex(random_bytes(32));

        // Save token to database
        $db = \Config\Database::connect();
        $db->table('email_verifications')->insert([
            'user_id'    => $user['user_id'],
            'token'      => $token,
            'expires_at' => date('Y-m-d H:i:s', strtotime('+1 day')) // 24hr expiry
        ]);

        // Generate the verification link
        $verifyLink = base_url('verify-email?token=' . urlencode($token));

        // Compose the email
        $message = "Hello " . esc($user['name']) . ",<br><br>";
        $message .= "Please verify your email address by clicking the link below:<br>";
        $message .= "<a href='" . $verifyLink . "'>Verify Email</a><br><br>";
        $message .= "If you did not request this, please ignore this email.";

        // Send the email
        $email->setTo($user['email']);
        $email->setSubject('Verify Your Email');
        $email->setMessage($message);
        $email->setMailType('html');

        if ($email->send()) {
            return true;
        } else {
            return false;
        }
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

    // Show add/edit user form
    public function form($userId = null)
    {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }

      $userModel = new \App\Models\UserModel();
          $headerModel = new \App\Models\HeaderModel();
          $userIdN = session()->get('user_id');
          $userN = $userModel->find($userIdN);
          //'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),

        $user = $userId ? $this->userModel->getUser($userId) : null;
        $roles = $this->roleModel->findAll();
        $shops = $this->shopModel->findAll();

        $session = session();
        $data = [
            'username' => $session->get('username'),
            'name' => $session->get('name'),
            'signature_link' => $session->get('signature_link'),
            'email' => $session->get('email'),
            'mobile' => $session->get('mobile'),
            'profile_image' => $session->get('profile_image'),
            'user' => $user,
            'roles' => $roles,
            'shops' => $shops,
            'header_links' => $headerModel->getHeaderLinksByUser($userN['user_id']),
        ];

        return view('users/form', $data);
    }

    public function viewImage($id)
{
  $session = session();
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

    // Add or edit user
    public function save()
    {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $userData = $this->request->getPost();
        $userId = $userData['user_id'] ?? null;

        // Hash password if set and not empty
        if (!empty($userData['password'])) {
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        } else {
            unset($userData['password']); // Don't update password if field is empty
        }

        if ($userId) {
            // Edit user
            $this->userModel->update($userId, $userData);
        } else {
            // Add user
            $this->userModel->insert($userData);
        }

        return redirect()->to('/users')->with('success', 'User saved successfully');
    }

    // Delete user
    public function delete($userId)
    {   $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $this->userModel->delete($userId);
        return redirect()->to('/users')->with('success', 'User deleted successfully');
    }

    public function sendPasswordReset($userId)
  {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }

      $user = $this->userModel->getUser($userId);

      if ($user) {
          $timezone = new \DateTimeZone('Africa/Nairobi');
          $now = new \DateTime('now', $timezone);
          $createdAt = $now->format('Y-m-d H:i:s');
          $expiresAt = $now->modify('+1 hour')->format('Y-m-d H:i:s');

          $token = bin2hex(random_bytes(32));

          // Save token (overwrite previous if exists)
          $tokenModel = new \App\Models\PasswordResetTokenModel();
          $tokenModel->where('user_id', $user['user_id'])->delete(); // Clear existing token

          $tokenModel->insert([
              'user_id'    => $user['user_id'],
              'token'      => $token,
              'created_at' => $createdAt,
              'expires_at' => $expiresAt
          ]);

          $resetLink = base_url('reset-password?token=' . urlencode($token));

          $email = \Config\Services::email();
          $email->setTo($user['email']);
          $email->setSubject('Password Reset Request');

          $message  = "Hello <strong>" . esc($user['name']) . "</strong>,<br><br>";
          $message .= "You requested a password reset. Click the link below to reset your password:<br><br>";
          $message .= "<a href='" . $resetLink . "' style='color: #3366cc;'>Reset Your Password</a><br><br>";
          $message .= "If you didn’t request this, you can ignore this email.<br><br>";
          $message .= "This link will expire in 1 hour.<br><br>";
          $message .= "Best regards,<br>nairobimetaldetectors POS";

          $email->setMessage($message);
          $email->setMailType('html');

          if ($email->send()) {
              return redirect()->to('/users')->with('success', 'Password reset link sent to the user.');
          } else {
              return redirect()->to('/users')->with('error', 'Failed to send reset link.');
          }
      }

      return redirect()->to('/users')->with('error', 'User not found.');
  }


}
