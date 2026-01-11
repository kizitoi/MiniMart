<?php


namespace App\Controllers;

use CodeIgniter\Controller;
use App\Models\UserModel;
use App\Models\PasswordResetTokenModel;

class PasswordResetController extends Controller
{
  protected $db;
  public function __construct()
  {
      $this->db = \Config\Database::connect();
  }


    public function index()
    {
        // Show password reset form (token is passed via GET)
        $db = \Config\Database::connect();
        $token = $this->request->getGet('token');
        $reset = $db->table('password_reset_tokens')->where('token', $token)->get()->getRow();

        if (!$reset || strtotime($reset->expires_at) < time()) {
            return redirect()->to('/login')->with('error', 'Token is invalid or expired.');
        }

        return view('password_reset/form');
    }


    public function resetPassword()
{
    $token = $this->request->getGet('token');
    $password = $this->request->getPost('password');
    $confirm = $this->request->getPost('confirm_password');

    if (!$token || empty($password) || $password !== $confirm) {
        return redirect()->to('/reset-password?token=' . urlencode($token))->with('error', 'Passwords do not match or token is missing.');
    }

    // Fetch token from DB
    $db = \Config\Database::connect();
    $tokenRow = $db->table('password_reset_tokens')->where('token', $token)->get()->getRow();

    if (!$tokenRow) {
        return redirect()->to('/login')->with('error', 'Invalid or expired token.');
    }

    // Check expiration
    if (strtotime($tokenRow->expires_at) < time()) {
        return redirect()->to('/login')->with('error', 'Token expired.');
    }

    // Hash new password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Update user password
    $db->table('users')->where('user_id', $tokenRow->user_id)->update([
        'password' => $hashedPassword
    ]);

    // Delete token to prevent reuse
    $db->table('password_reset_tokens')->where('token', $token)->delete();

    return redirect()->to('/login')->with('success', 'Password updated. Please log in.');
}


    /*public function resetPassword()
    {
        helper(['form']);
        $validation = \Config\Services::validation();

        // Validate input
        $validation->setRules([
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
            'token' => 'required'
        ]);

        if (!$this->validate($validation->getRules())) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $token = $this->request->getPost('token');
        $password = $this->request->getPost('password');

        // Load token model to verify
        $tokenModel = new PasswordResetTokenModel();
        $tokenData = $tokenModel->where('token', $token)->first();

        if (!$tokenData) {
            return redirect()->to('/login')->with('error', 'Invalid or expired token.');
        }

        // Update password for the user
        $userModel = new UserModel();
        $hashed = password_hash($password, PASSWORD_DEFAULT);
        $userModel->update($tokenData['user_id'], ['password' => $hashed]);

        // Invalidate the token
        $tokenModel->delete($tokenData['id']);
        $db->table('password_reset_tokens')->where('token', $token)->delete();

        return redirect()->to('/login')->with('success', 'Your password has been reset successfully. Please login.');
    }*/
}





/*
namespace App\Controllers;

use CodeIgniter\Controller;
use App\Libraries\UserDataHelper;


class PasswordResetController extends Controller
{


  protected $db;
  public function __construct()
  {
      $this->db = \Config\Database::connect();
  }



    public function index()
    {


      // Show password reset form (token is passed via GET)
      $db = \Config\Database::connect();
      $token = $this->request->getGet('token');
      $reset = $db->table('password_resets')->where('token', $token)->get()->getRow();

      if (!$reset || strtotime($reset->expires_at) < time()) {
          return redirect()->to('/login')->with('error', 'Token is invalid or expired.');
      }

        // Show password reset form
        return view('password_reset/form');
    }

    public function resetPassword()
    {
        $data = $this->request->getPost();

        // Handle password reset logic (e.g., verify token, update password in the database)

        // Assuming you have a TokenModel to validate the token and reset password
        // $token = $data['token'];
        // $newPassword = $data['password'];

        // Reset password logic

        // Redirect to a success page or login page
        return redirect()->to('/login')->with('success', 'Password successfully updated');
    }
}

*/
