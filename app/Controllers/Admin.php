<?php namespace App\Controllers;

use CodeIgniter\Controller;

class Admin extends Controller
{
    public function index()
    {
        $session = session();
        if (!$session->get('logged_in') || $session->get('role') != 2) {
            return redirect()->to('login');
        }

        $data = [
            'username' => $session->get('username'),
            'profile_image' => $session->get('profile_image'),

        ];

        return view('admin/admin', $data);
    }
}
