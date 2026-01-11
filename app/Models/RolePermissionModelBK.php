<?php

/*namespace App\Models;

use CodeIgniter\Model;

class RolePermissionModel extends Model
{
    protected $table = 'role_permissions';
    protected $primaryKey = 'id';
    protected $allowedFields = ['role_id', 'permission_id', 'can_view', 'can_edit', 'can_delete', 'created_at', 'updated_at'];
}
*/

namespace App\Models;
use CodeIgniter\Model;

class RolePermissionModel extends Model
{
    protected $table = 'role_permissions';
    protected $primaryKey = 'id';
    protected $allowedFields = [
        'role_id', 'permission_id', 'can_view', 'can_edit', 'can_delete',
    ];
    protected $useTimestamps = true;
}
