<?php namespace App\Controllers;

use App\Models\ChatModel;
use App\Models\UserModel;
use App\Libraries\UserDataHelper;

class ChatController extends BaseController
{




    public function loadChat($otherUserId)
    {

      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
        $session = session();
        $currentUserId = $session->get('user_id');

        $chatModel = new ChatModel();
        $userModel = new UserModel();

        $messages = $chatModel->getConversation($currentUserId, $otherUserId);
        $user = $userModel->find($otherUserId);

        return view('chat/chat_window', [
            'messages' => $messages,
            'user' => $user,
            'otherUserId' => $otherUserId
        ]);
    }

    public function send()
    {

      $session = session();
    if (!$session->get('logged_in')) {
        return redirect()->to('login');
    }
        $session = session();
        $chatModel = new ChatModel();

        $data = [
            'sender_id' => $session->get('user_id'),
            'receiver_id' => $this->request->getPost('receiver_id'),
            'message' => $this->request->getPost('message')
        ];
        $chatModel->insert($data);
        return redirect()->to('/chat/load/' . $data['receiver_id']);
    }
}
