<?php namespace App\Models;

use CodeIgniter\Model;

class ChatModel extends Model
{
    protected $table = 'chats';
    protected $primaryKey = 'chat_id';
    protected $allowedFields = ['sender_id', 'receiver_id', 'message', 'timestamp', 'is_read'];

    public function getChatHistory($user1, $user2)
    {
        return $this->where("(sender_id = $user1 AND receiver_id = $user2) OR (sender_id = $user2 AND receiver_id = $user1)")
                    ->orderBy('timestamp', 'ASC')
                    ->findAll();
    }

    public function getConversation($user1, $user2)
{
    return $this->where("(sender_id = $user1 AND receiver_id = $user2) OR (sender_id = $user2 AND receiver_id = $user1)")
                ->orderBy('timestamp', 'asc')
                ->findAll();
}

public function getUnreadCount($receiverId, $senderId)
{
    return $this->where('receiver_id', $receiverId)
                ->where('sender_id', $senderId)
                ->where('is_read', 0)
                ->countAllResults();
}

}
