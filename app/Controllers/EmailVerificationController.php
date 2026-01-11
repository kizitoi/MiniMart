<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use Config\Database;

class EmailVerificationController extends Controller
{
    public function verify()
    {
        $token = $this->request->getGet('token');
        $db = Database::connect();

        $row = $db->table('email_verifications')->where('token', $token)->get()->getRow();

        if ($row && strtotime($row->expires_at) > time()) {
            // Mark user as verified
            $db->table('users')->where('user_id', $row->user_id)->update(['is_verified' => 1]);

            // Remove token
            $db->table('email_verifications')->where('token', $token)->delete();

            return redirect()->to('/login')->with('success', 'Email verified successfully.');
        }

        return redirect()->to('/login')->with('error', 'Invalid or expired verification link.');
    }
}
