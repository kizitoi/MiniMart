<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container">
    <h2>Role Permissions</h2>

    <!-- Role Selection -->
    <div class="form-group">
        <label for="role">Select Role</label>
        <select id="role" name="role" class="form-control">
            <option value="">Select Role</option>
            <?php foreach ($roles as $role): ?>
                <option value="<?= $role['id'] ?>"><?= esc($role['name']) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <!-- Permissions Table -->
    <form action="<?= site_url('rolepermissions/save') ?>" method="post" id="permissionsForm">
        <?= csrf_field() ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Permission</th>
                    <th>View</th>
                    <th>Edit</th>
                    <th>Delete</th>
                </tr>
            </thead>
            <tbody id="permissionsTableBody">
                <!-- Permissions will be populated here by AJAX -->
            </tbody>
        </table>
        <button type="submit" class="btn btn-primary">Save Permissions</button>
    </form>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        // On role selection change, make AJAX call to get permissions for the selected role
        $('#role').change(function() {
            var roleId = $(this).val();
            if (roleId) {
                $.ajax({
                    url: '<?= site_url('rolepermissions/get_permissions') ?>',
                    type: 'GET',
                    data: { role_id: roleId },
                    dataType: 'json',
                    success: function(response) {
                        // Clear previous rows
                        $('#permissionsTableBody').html('');

                        // Loop through the permissions and populate the table
                        $.each(response.permissions, function(index, permission) {
                            var row = `<tr>
                                        <td>${permission.name}</td>
                                        <td><input type="checkbox" name="permissions[${permission.id}][view]" ${permission.can_view ? 'checked' : ''}></td>
                                        <td><input type="checkbox" name="permissions[${permission.id}][edit]" ${permission.can_edit ? 'checked' : ''}></td>
                                        <td><input type="checkbox" name="permissions[${permission.id}][delete]" ${permission.can_delete ? 'checked' : ''}></td>
                                    </tr>`;
                            $('#permissionsTableBody').append(row);
                        });
                    },
                    error: function() {
                        alert('Error loading permissions');
                    }
                });
            } else {
                $('#permissionsTableBody').html('');
            }
        });
    });
</script>

<?= $this->endSection() ?>
