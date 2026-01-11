<?php
class RolePermission_model extends CI_Model {

    public function get_roles() {
        return $this->db->get('roles')->result();
    }

    public function get_permissions() {
        return $this->db->get('permissions')->result();
    }

    public function get_role_permissions($role_id) {
        $this->db->where('role_id', $role_id);
        return $this->db->get('role_permissions')->result();
    }

    public function update_permission($role_id, $permission_id, $can_view,$can_add, $can_edit, $can_delete) {
        $data = [
            'can_view' => $can_view,
              'can_add' => $can_add,
            'can_edit' => $can_edit,
            'can_delete' => $can_delete
        ];

        $exists = $this->db->get_where('role_permissions', [
            'role_id' => $role_id,
            'permission_id' => $permission_id
        ])->row();

        if ($exists) {
            $this->db->where('role_id', $role_id);
            $this->db->where('permission_id', $permission_id);
            return $this->db->update('role_permissions', $data);
        } else {
            $data['role_id'] = $role_id;
            $data['permission_id'] = $permission_id;
            return $this->db->insert('role_permissions', $data);
        }
    }
}
