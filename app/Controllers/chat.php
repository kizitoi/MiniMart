<?php namespace App\Controllers;

use App\Models\UserModel;
use App\Models\ChatModel;
use CodeIgniter\Controller;
use App\Libraries\UserDataHelper;

class Chat extends Controller
{
    public function index()
    {
      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
        $userId = $session->get('user_id');

        $userModel = new UserModel();
        $data['users'] = $userModel->getAllUsersExcept($userId);

        return view('chat/index', $data);
    }

    public function getMessages($userId)
    {
      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
        $chatModel = new ChatModel();
        $currentUser = session()->get('user_id');
        $messages = $chatModel->getChatHistory($currentUser, $userId);

        return $this->response->setJSON($messages);
    }

    public function sendMessage()
    {
      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
        $chatModel = new ChatModel();
        $senderId = session()->get('user_id');
        $receiverId = $this->request->getVar('receiver_id');
        $message = $this->request->getVar('message');

        $chatModel->insert([
            'sender_id' => $senderId,
            'receiver_id' => $receiverId,
            'message' => $message
        ]);

        return $this->response->setJSON(['status' => 'success']);
    }
}
