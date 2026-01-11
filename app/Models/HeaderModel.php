<?php
namespace App\Models;

use CodeIgniter\Model;

class HeaderModel extends Model
{

    protected $table = 'permissions';
    protected $allowedFields = ['name', 'label', 'link', 'icon', 'isbutton', 'created_at', 'updated_at'];


    public function getHeaderLinksByUser($userId)
{
    $db = db_connect();

    // Get the user's role_id
    $user = $db->table('users')
               ->select('role_id')
               ->where('user_id', $userId)
               ->get()
               ->getRow();

    if (!$user) {
        return [];
    }

    // Get viewable permissions for the role
    $builder = $db->table('permissions');
    $builder->select('permissions.label, permissions.link, permissions.icon, permissions.isbutton,  role_permissions.can_add,  role_permissions.can_edit, role_permissions.can_delete ');
    $builder->join('role_permissions', 'role_permissions.permission_id = permissions.id');
    $builder->where('role_permissions.role_id', $user->role_id);
    $builder->where('role_permissions.can_view', 1);
    $builder->orderBy('permissions.label', 'asc');

    return $builder->get()->getResultArray();
  //  return $this->orderBy('id', 'ASC')->findAll(); // Adjust order as needed
}


}
