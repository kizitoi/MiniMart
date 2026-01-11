<?php

namespace App\Controllers;

use App\Models\CompanyModel;
use App\Models\BranchModel;
use App\Models\TownModel;
use App\Models\CountyModel;
use App\Models\CountryModel;
use CodeIgniter\Controller;
use App\Libraries\UserDataHelper;

use App\Models\ClientReportModel;


class CompanyController extends Controller
{
    public function index()
    {
        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $companyModel = new CompanyModel();
        $branchModel = new BranchModel();
        $townModel = new TownModel();
        $countyModel = new CountyModel();
        $countryModel = new CountryModel();

        // Join company data with related tables
        $builder = $companyModel
            ->select('companies.*,
                      branches.name as branch_name,
                      towns.name as town_name,
                      counties.name as county_name,
                      countries.name as country_name')
            ->join('branches', 'branches.id = companies.branch_id', 'left')
            ->join('towns', 'towns.id = companies.town_id', 'left')
            ->join('counties', 'counties.id = companies.county_id', 'left')
            ->join('countries', 'countries.id = companies.country_id', 'left');


            $userModel = new \App\Models\UserModel();
      $headerModel = new \App\Models\HeaderModel();
      $userId = session()->get('user_id');
      $user = $userModel->find($userId);
 //'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),

  /*      $data = [
            'username' => $session->get('username'),
            'name' => $session->get('name'),
            'signature_link' => $session->get('signature_link'),
            'email' => $session->get('email'),
            'mobile' => $session->get('mobile'),
            'profile_image' => $session->get('profile_image'),
            'companies'  => $builder->findAll(),
            'branches'   => $branchModel->findAll(),
            'towns'      => $townModel->findAll(),
            'counties'   => $countyModel->findAll(),
            'countries'  => $countryModel->findAll(),
             'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
        ];
*/


        $helper = new UserDataHelper();
        $data = $helper->load() + [
        //  'username' => $session->get('username'),
        //  'name' => $session->get('name'),
          //'signature_link' => $session->get('signature_link'),
        //  'email' => $session->get('email'),
        //  'mobile' => $session->get('mobile'),
        //  'profile_image' => $session->get('profile_image'),
          'companies'  => $builder->findAll(),
          'branches'   => $branchModel->findAll(),
          'towns'      => $townModel->findAll(),
          'counties'   => $countyModel->findAll(),
          'countries'  => $countryModel->findAll(),
           'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
        ];




        return view('company/index', $data);
    }

    public function save()
    {
      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
        $companyModel = new CompanyModel();
        $file = $this->request->getFile('logo');

        $logoName = null;
        if ($file && $file->isValid()) {
            $logoName = $file->getRandomName();
            $file->move('public/logos', $logoName);
        }

        $companyModel->save([
            'name' => $this->request->getPost('company_name'),
            'branch_id' => $this->request->getPost('branch_id'),
            'address' => $this->request->getPost('address'),
            'town_id' => $this->request->getPost('town_id'),
            'county_id' => $this->request->getPost('county_id'),
            'country_id' => $this->request->getPost('country_id'),
            'phone' => $this->request->getPost('phone'),
            'email' => $this->request->getPost('email'),
            'website' => $this->request->getPost('website'),
            'logo' => $logoName ? $logoName : null // Only the file name is stored
        ]);

        return redirect()->back()->with('success', 'Company saved successfully!');
    }

    public function edit($id)
{
  $session = session();
if (!$session->get('logged_in')) {
    return redirect()->to('login');
}
    $session = session();
    $companyModel = new CompanyModel();
    $branchModel = new BranchModel();
    $townModel = new TownModel();
    $countyModel = new CountyModel();
    $countryModel = new CountryModel();


                $userModel = new \App\Models\UserModel();
          $headerModel = new \App\Models\HeaderModel();
          $userId = session()->get('user_id');
          $user = $userModel->find($userId);
     //'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),


  /*  $data = [
        'username' => $session->get('username'),
        'name' => $session->get('name'),
        'signature_link' => $session->get('signature_link'),
        'email' => $session->get('email'),
        'mobile' => $session->get('mobile'),
        'profile_image' => $session->get('profile_image'),
        'company' => $companyModel->find($id),
        'branches' => $branchModel->findAll(),
        'towns' => $townModel->findAll(),
        'counties' => $countyModel->findAll(),
        'countries' => $countryModel->findAll(),
        'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
    ];*/


    $helper = new UserDataHelper();
    $data = $helper->load() + [
    //  'username' => $session->get('username'),
    //  'name' => $session->get('name'),
    //  'signature_link' => $session->get('signature_link'),
    //  'email' => $session->get('email'),
    //  'mobile' => $session->get('mobile'),
    //  'profile_image' => $session->get('profile_image'),
      'company' => $companyModel->find($id),
      'branches' => $branchModel->findAll(),
      'towns' => $townModel->findAll(),
      'counties' => $countyModel->findAll(),
      'countries' => $countryModel->findAll(),
      'header_links' => $headerModel->getHeaderLinksByUser($user['user_id']),
    ];




    return view('company/edit', $data);
}

public function update($id)
{

  $session = session();
if (!$session->get('logged_in')) {
    return redirect()->to('login');
}
    $companyModel = new CompanyModel();
    $file = $this->request->getFile('logo');

    // Initialize data array with other fields
    $data = [
        'name' => $this->request->getPost('company_name'),
        'branch_id' => $this->request->getPost('branch_id'),
        'address' => $this->request->getPost('address'),
        'town_id' => $this->request->getPost('town_id'),
        'county_id' => $this->request->getPost('county_id'),
        'country_id' => $this->request->getPost('country_id'),
        'phone' => $this->request->getPost('phone'),
        'email' => $this->request->getPost('email'),
        'website' => $this->request->getPost('website'),
    ];

    // Only update logo if a new file is uploaded and valid
    if ($file && $file->isValid() && !$file->hasMoved()) {
        $logoName = $file->getRandomName();
        $file->move('public/logos', $logoName);
        $data['logo'] = 'public/logos/' . $logoName;
    }

    $companyModel->update($id, $data);

    return redirect()->to('/company')->with('success', 'Company updated successfully!');
}


public function delete($id)
{
  $session = session();
if (!$session->get('logged_in')) {
    return redirect()->to('login');
}
    $reportModel = new ClientReportModel();
    $companyModel = new CompanyModel();

    $hasReports = $reportModel->where('company_id', $id)->countAllResults();

    if ($hasReports > 0) {
        return redirect()->back()->with('error', 'Cannot delete: Company has linked client reports.');
    }

    $company = $companyModel->find($id);
    if ($company && $company['logo']) {
        @unlink($company['logo']); // delete logo file
    }

    $companyModel->delete($id);

    return redirect()->back()->with('success', 'Company deleted successfully.');
}
}
