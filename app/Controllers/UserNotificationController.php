<?php namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserNotificationModel;
use App\Models\UserModel;
use App\Libraries\UserDataHelper;


class UserNotificationController extends BaseController
{
    public function index($userId)
    {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }

        $session = session();
        $headerModel = new \App\Models\HeaderModel();

        $userModel = new UserModel();
        $notificationModel = new UserNotificationModel();

        $userl = $userModel->find($userId);
        $settings = $notificationModel->where('user_id', $userId)->first();

        if (!$settings) {
            $notificationModel->insert(['user_id' => $userId]); // Create default row
            $settings = $notificationModel->where('user_id', $userId)->first();
        }


        return view('notifications/user_notifications', [
            'user' => $userl,
            'settings' => $settings,
            'username' => $session->get('username'),
            'name' =>   $userl['name'],
            'signature_link' => $session->get('signature_link'),
            'email' => $session->get('email'),
            'mobile' => $session->get('mobile'),
            'profile_image' => $session->get('profile_image'),
            'header_links' => $headerModel->getHeaderLinksByUser($session->get('user_id')),
        ]);
    }


 public function edit($userId)
{
  $session = session();
  if (!$session->get('logged_in')) {
      return redirect()->to('login');
  }
    $session = session();
    $headerModel = new \App\Models\HeaderModel();

    $notificationModel = new \App\Models\UserNotificationModel();
    $userModel = new \App\Models\UserModel();

    // Selected user (target of the notification settings)
    $selectedUser = $userModel->find($userId);
    if (!$selectedUser) {
        throw new \CodeIgniter\Exceptions\PageNotFoundException('User not found');
    }

    // Logged-in user (from session)
    $loggedInUser = $userModel->find($session->get('user_id'));

    $notification = $notificationModel->where('user_id', $userId)->first();

    // If no notification row exists, create a blank one
    if (!$notification) {
        $notificationModel->insert(['user_id' => $userId]);
        $notification = $notificationModel->where('user_id', $userId)->first();
    }

    return view('notifications/edit', [
        'user' => $selectedUser, // Selected user
        'notification' => $notification,
        // Logged-in user data
        'username' => $session->get('username'),
        'name' => $loggedInUser['name'],
        'signature_link' => $session->get('signature_link'),
        'email' => $session->get('email'),
        'mobile' => $session->get('mobile'),
        'profile_image' => $session->get('profile_image'),
        'header_links' => $headerModel->getHeaderLinksByUser($session->get('user_id')),
    ]);
}


    public function update($userId)
    {
      $session = session();
      if (!$session->get('logged_in')) {
          return redirect()->to('login');
      }
        $notificationModel = new UserNotificationModel();

        $data = [

            'new_user_registration' => $this->request->getPost('new_user_registration') ? 1 : 0,
            'low_stock' => $this->request->getPost('low_stock') ? 1 : 0,
            'item_sale' => $this->request->getPost('item_sale') ? 1 : 0,

            'new_user_registration_sms' => $this->request->getPost('new_user_registration_sms') ? 1 : 0,
            'low_stock_sms' => $this->request->getPost('low_stock_sms') ? 1 : 0,
            'item_sale_sms' => $this->request->getPost('item_sale_sms') ? 1 : 0,
        ];

        $notificationModel->where('user_id', $userId)->set($data)->update();

        return redirect()->to("/notifications/$userId")->with('success', 'Notification settings updated.');
    }
}
