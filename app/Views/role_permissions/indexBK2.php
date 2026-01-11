<?= $this->extend('layout') ?>

<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='role_permissions')
      { ?>

        <?php if ($link['can_view']=='1')
         { ?>

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

<select id="roleSelect">
    <option value="">-- Select Role --</option>
    <?php foreach ($roles as $role): ?>
      <!--  <option value="<?//= $role->id ?>"><?//= $role->name ?></option>-->
        <option value="<?= $role['id'] ?>"><?= $role['name'] ?></option>

    <?php endforeach; ?>
</select>

<table border="1" id="permissionsTable" style="margin-top:20px; display:none;">
    <thead>
        <tr>
            <th>Permission</th>
            <th>View</th>
            <th>Edit</th>
            <th>Delete</th>
        </tr>
    </thead>
    <tbody></tbody>
</table>

<script>
$('#roleSelect').change(function() {
    const roleId = $(this).val();
    if (!roleId) {
        $('#permissionsTable').hide();
        return;
    }

    $.getJSON('<?= base_url("permissions/get_permissions_for_role/") ?>' + roleId, function(data) {
        const tbody = $('#permissionsTable tbody');
        tbody.empty();
        data.permissions.forEach(permission => {
            const rp = data.role_permissions[permission.id] || {};
            const can_view = rp.can_view ? 'checked' : '';
            const can_edit = rp.can_edit ? 'checked' : '';
            const can_delete = rp.can_delete ? 'checked' : '';
            const row = `
                <tr>
                    <td>${permission.name}</td>
                    <td><input type="checkbox" class="perm" data-type="view" data-id="${permission.id}" ${can_view}></td>
                    <td><input type="checkbox" class="perm" data-type="edit" data-id="${permission.id}" ${can_edit}></td>
                    <td><input type="checkbox" class="perm" data-type="delete" data-id="${permission.id}" ${can_delete}></td>
                </tr>
            `;
            tbody.append(row);
        });
        $('#permissionsTable').show();
    });
});

$(document).on('change', '.perm', function() {
    const roleId = $('#roleSelect').val();
    const permissionId = $(this).data('id');
    const row = $(this).closest('tr');
    const can_view = row.find('[data-type="view"]').is(':checked');
    const can_edit = row.find('[data-type="edit"]').is(':checked');
    const can_delete = row.find('[data-type="delete"]').is(':checked');

    $.post('<?= base_url("permissions/update_permission") ?>', {
        role_id: roleId,
        permission_id: permissionId,
        can_view: can_view,
        can_edit: can_edit,
        can_delete: can_delete
    }, function(response) {
        console.log(response);
    }, 'json');
});
</script>

<?php } ?>
<?php } ?>
<?php endforeach; ?>
<?php endif; ?>
