<?php

namespace App\Controllers;

use App\Models\FirebaseModel;

class FirebaseController extends BaseController
{
    public function index()
    {
       /* $firebaseModel = new FirebaseModel();
        $data['businesses'] = $firebaseModel->getBusinesses();

        $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $data['username'] = $session->get('username');
        $data['profile_image'] = $session->get('profile_image');
       // return view('institution/institution', $data);
        return view('firebase/firebase_view', $data); */

           $session = session();
        if (!$session->get('logged_in')) {
            return redirect()->to('login');
        }

        $username = $session->get('username');
        $profile_image = $session->get('profile_image');



       $firebaseModel = new FirebaseModel();

        // Fetch all businesses
        $businesses = $firebaseModel->getBusinesses();

        // Convert to an array for easier pagination
        $businessesArray = is_array($businesses) ? array_values($businesses) : [];

        // Pagination settings
        $perPage = 10; // Number of records per page
        $page = $this->request->getVar('page') ? $this->request->getVar('page') : 1;
        $total = count($businessesArray);

        // Slice the array for pagination
        $businessesPage = array_slice($businessesArray, ($page - 1) * $perPage, $perPage);

        // Create a pager instance
        $pager = \Config\Services::pager();

        // Pass data to the view
        return view('firebase/firebase_view', [
            'businesses' => $businessesPage,
                'username' => $username,
                   'profile_image' => $profile_image,
            'pager' => $pager->makeLinks($page, $perPage, $total),
            'total' => $total,
            'perPage' => $perPage,
            'currentPage' => $page,
        ]);


    }

  public function show($id)
{      $session = session();
  if (!$session->get('logged_in')) {
      return redirect()->to('login');
  }
    $firebaseModel = new FirebaseModel();
    $business = $firebaseModel->getBusinesses()[$id];
    $session = session();
        if (!$session->get('logged_in'))
        {
            return redirect()->to('login');
        }

    $username = $session->get('username');
    $profile_image = $session->get('profile_image');

    return view('firebase/firebase_show', [
      'business' => $business,
      'username' => $username,
      'profile_image' => $profile_image

    ]);
}

}
