<?php

namespace App\Controllers;
use App\Libraries\UserDataHelper;
use App\Models\PermissionsModel;

class SystemAdministration extends BaseController
{
    protected $helper;
    protected $permissions;

    public function __construct()
    {
        $this->helper = new UserDataHelper();
        $this->permissions = new PermissionsModel();
    }

    public function index()
    {
        $sessionData = $this->helper->load();

        // fetch all permissions that are buttons
        $header_links = $this->permissions->where('isbutton', 1)->findAll();

        $data = $sessionData + [
            'header_links' => $header_links,
        ];

        return view('system_administration/index', $data);
    }
}
