<?php
namespace App\Models;
use CodeIgniter\Model;

class UserModel extends Model
{
  /*  protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['username', 'email', 'mobile', 'name', 'profile_image', 'signature_link', 'password', 'updated_at'];
*/
    protected $table = 'users';
    protected $primaryKey = 'user_id';
    protected $allowedFields = ['username', 'password', 'role_id', 'shop_id', 'profile_image', 'email', 'mobile', 'name', 'signature_link', 'designation','logged_in','company_id'];
    protected $useTimestamps = true;


     public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }

    public function getUserById($user_id)
    {
        return $this->where('user_id', $user_id)->first();
    }

    public function updateUser($user_id, $data)
    {
        return $this->update($user_id, $data);
    }


        public function getUser($userId)
        {
            return $this->find($userId);
        }



    /*    public function getUsers()
{
    return $this->select('users.*, roles.name as role_name, shops.name as shop_name')
                ->join('roles', 'roles.id = users.role_id', 'left')
                ->join('shops', 'shops.id = users.shop_id', 'left')
                ->findAll();
}*/


/*public function getUsers($role_id = null, $shop_id = null)
{
    $builder = $this->db->table('users')
        ->select('users.*, roles.name as role_name, roles.id as role_id, shops.name as shop_name')
        ->join('roles', 'roles.id = users.role_id', 'left')
        ->join('shops', 'shops.id = users.shop_id', 'left');

    if (!empty($role_id)) {
        $builder->where('users.role_id', $role_id);
    }

    if (!empty($shop_id)) {
        $builder->where('users.shop_id', $shop_id);
    }

    return $builder->get()->getResultArray();
}*/

public function getUsers($role_id = null, $shop_id = null, $search = null)
{
    $builder = $this->db->table('users')
        ->select('users.*, roles.name as role_name , roles.id as role_id, shops.name as shop_name')
        ->join('roles', 'roles.id = users.role_id', 'left')
        ->join('shops', 'shops.id = users.shop_id', 'left');

    if (!empty($role_id)) {
        $builder->where('users.role_id', $role_id);
    }

    if (!empty($shop_id)) {
        $builder->where('users.shop_id', $shop_id);
    }

    if (!empty($search)) {
        $builder->groupStart()
                ->like('users.username', $search)
                ->orLike('users.email', $search)
                ->groupEnd();
    }

    return $builder->get()->getResultArray();
}




        public function getRoles()
        {
            return $this->db->table('roles')->get()->getResult();
        }

        public function getShops()
        {
            return $this->db->table('shops')->get()->getResult();
        }


        public function getAllUsersExcept($currentUserId)
{
    return $this->select('user_id, name, username, profile_image, logged_in')
                ->where('user_id !=', $currentUserId)
                ->findAll();
}

}
