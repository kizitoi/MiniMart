<?php namespace App\Controllers;

use App\Models\UserModel;
use CodeIgniter\Controller;

class Auth extends Controller
{
    public function login()
    {
        return view('login');
    }

    public function authenticate()
    {
      ini_set('session.gc_maxlifetime', 900); ////15 minutes
      // $baseurl='https://nairobimetaldetectors.net';
        $session = session();
        $model = new UserModel();

        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $data = $model->where('username', $username)->first();

        if($data){
            $pass = $data['password'];
            $verify_pass = password_verify($password, $pass);

            if($verify_pass){
                $ses_data = [
                    'user_id'        => $data['user_id'],
                    'user_id_logged_in' => $data['user_id'],
                    'owner_id'        => $data['user_id'],
                    'username'  => $data['username'],
                    'email'  => $data['email'],
                    'mobile'  => $data['mobile'],
                    'name'  => $data['name'],
                    'designation'  => $data['designation'],
                    'role'      => $data['role_id'],
                    'role_id'      => $data['role_id'],
                    'company_id'      => $data['company_id'],
                    'shop_id'      => $data['shop_id'],
                    'profile_image' => $data['profile_image'],
                    'signature_link' => $data['signature_link'],// Assuming profile_image is a column in your users table
                    'logged_in' => TRUE,
                    'isLoggedIn' => true

                ];
                $session->set($ses_data);

                // ✅ Update the logged_in column in the users table
                    $model->update($data['user_id'], ['logged_in' => 1]);

                error_log("User authenticated, redirecting to dashboard.");

                switch($data['role_id']){
                    case 1:
                        return redirect()->to('/officer/officer');
                    case 2:
                      //  return redirect()->to('/admin/admin');
                          return redirect()->to('/officer/officer');
                    case 4:
                      //  return redirect()->to('/institution/institution');
                          return redirect()->to('/officer/officer');
                    case 6:
                      //  return redirect()->to('/user/user');
                          return redirect()->to('/officer/officer');
                    case 8:
                      //  return redirect()->to('/manager/hostel_manager');
                          return redirect()->to('/officer/officer');
                    default:
                        $session->setFlashdata('msg', 'Role not defined');
                        return redirect()->to('login');
                }
            } else {
                $session->setFlashdata('msg', 'Authentication Failed');
                error_log("Password verification failed.");
                return redirect()->to('login');
            }


        } else {
            $session->setFlashdata('msg', 'Username not found');
            return redirect()->to('login');
        }
    }


	public function logout()
	{
		$session = session();
		$user_id = $session->get('user_id');

		// Update the logged_in column to 0
		$model = new UserModel();
		if ($user_id) {
			$model->update($user_id, ['logged_in' => 0]);
		}

		$session->destroy();
		return redirect()->to('login');
	}


	public function checkSession()
	{
		$session = session();
		if (!$session->has('user_id')) {
			// Session expired, call logout logic
			$user_id = $session->get('user_id');
			if ($user_id) {
				$model = new \App\Models\UserModel();
				$model->update($user_id, ['logged_in' => 0]);
			}
			return $this->response->setJSON(['logged_in' => false]);
		}
		return $this->response->setJSON(['logged_in' => true]);
	}

	public function viewPrivateImage($user_id, $type)
	{
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


}
