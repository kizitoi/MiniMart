<?php

namespace App\Libraries;

use App\Models\HeaderModel;
use App\Models\UserModel;
use Config\Database;

class UserDataHelper
{
    protected $userModel;
    protected $headerModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->headerModel = new HeaderModel();
    }

    public function load($existingOrder = null): array
    {
        $session = session();

        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $userId = $session->get('user_id');
        $user = $this->userModel->find($userId);

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

        return [
            'username'          => $session->get('username'),
            'name'              => $session->get('name'),
            'user_id'           => $session->get('user_id'),
            'user_id_logged_in' => $session->get('user_id'),
            'signature_link'    => $session->get('signature_link'),
            'email'             => $session->get('email'),
            'mobile'            => $session->get('mobile'),
            'profile_image'     => $session->get('profile_image'),
            'header_links'      => $this->headerModel->getHeaderLinksByUser($user['user_id']),
            'existing_order'    => $existingOrder,
            'companyName'       => $companyName,
        ];
    }
}
