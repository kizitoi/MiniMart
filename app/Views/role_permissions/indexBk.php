<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container-fluid">
    <h1 class="m-0">Role Permissions</h1>
    <p>Welcome, <?= esc($name) ?>!</p>

    <form action="<?= site_url('role_permissions/save') ?>" method="post">
        <?= csrf_field() ?>

        <div class="form-group">
            <label for="role_id">Select Role</label>
            <select name="role_id" id="role_id" class="form-control" required>
                <option value="">Select Role</option>
                <?php foreach ($roles as $role) : ?>
                    <option value="<?= $role['id'] ?>" <?= isset($roleId) && $roleId == $role['id'] ? 'selected' : '' ?>>
                        <?= esc($role['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <h3>Permissions</h3>
        <?php foreach ($permissions as $permission) : ?>
            <div class="form-check">
                <input type="checkbox" name="permissions[]" value="<?= $permission['id'] ?>"
                    <?= isset($role_permissions[$permission['id']]) ? 'checked' : '' ?>>
                <label class="form-check-label"><?= esc($permission['name']) ?></label>

                <div>
                    <input type="checkbox" name="can_view[<?= $permission['id'] ?>]" value="1"
                        <?= isset($role_permissions[$permission['id']]) && $role_permissions[$permission['id']]['can_view'] ? 'checked' : '' ?>>
                    View
                    <input type="checkbox" name="can_edit[<?= $permission['id'] ?>]" value="1"
                        <?= isset($role_permissions[$permission['id']]) && $role_permissions[$permission['id']]['can_edit'] ? 'checked' : '' ?>>
                    Edit
                    <input type="checkbox" name="can_delete[<?= $permission['id'] ?>]" value="1"
                        <?= isset($role_permissions[$permission['id']]) && $role_permissions[$permission['id']]['can_delete'] ? 'checked' : '' ?>>
                    Delete
                </div>
            </div>
        <?php endforeach; ?>

        <button type="submit" class="btn btn-primary mt-3">Save Permissions</button>
    </form>
</div>



 
<script>
function loadPermissions(roleId) {
    fetch(`/role-permissions/getPermissions/${roleId}`)
        .then(res => res.json())
        .then(data => {
            let html = '';
            data.permissions.forEach(p => {
                const assigned = data.assigned[p.id] || {};
                html += `
                <tr>
                    <td>${p.name}</td>
                    <td><input type="checkbox" onchange="updatePermission(${roleId}, ${p.id})" name="can_view" ${assigned.can_view ? 'checked' : ''}></td>
                    <td><input type="checkbox" onchange="updatePermission(${roleId}, ${p.id})" name="can_edit" ${assigned.can_edit ? 'checked' : ''}></td>
                    <td><input type="checkbox" onchange="updatePermission(${roleId}, ${p.id})" name="can_delete" ${assigned.can_delete ? 'checked' : ''}></td>
                </tr>`;
            });
            document.querySelector('#permissions-body').innerHTML = html;
        });
}

function updatePermission(roleId, permissionId) {
    const row = event.target.closest('tr');
    const inputs = row.querySelectorAll('input[type="checkbox"]');
    const data = {
        role_id: roleId,
        permission_id: permissionId,
        can_view: inputs[0].checked ? 1 : 0,
        can_edit: inputs[1].checked ? 1 : 0,
        can_delete: inputs[2].checked ? 1 : 0
    };
    fetch('/role-permissions/updatePermission', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: new URLSearchParams(data)
    });
}
</script>

<?= $this->endSection() ?>
