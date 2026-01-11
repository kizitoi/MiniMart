<?php

namespace App\Models;

use CodeIgniter\Model;

class RemindersModel extends Model
{
    protected $table         = 'reminders';
    protected $primaryKey    = 'id';
    protected $allowedFields = ['user_id','title','about','remind_at','status'];
    protected $useTimestamps = true;

    // Simple helper for search
    public function searchWithUser(?string $term, int $userId)
    {
        $builder = $this->where('user_id', $userId);
        if ($term) {
            $builder->groupStart()
                ->like('title', $term)
                ->orLike('about', $term)
            ->groupEnd();
        }
        return $builder->orderBy('remind_at', 'DESC');
    }
}
