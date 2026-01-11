<?= $this->extend('layout') ?>

<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='role_permissions')
      { ?>



<?= $this->section('content') ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Role Permissions</h2>
    </div>

    <!-- Flash Message -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <!-- Role Quick Links -->
    <div class="mb-4">
        <h5>Select a Role to View Permissions Quickly</h5>
        <div class="d-flex flex-wrap gap-2">
            <?php foreach ($roles as $role) : ?>

                <a href="<?= site_url('role-permissions/view/' . $role['id']) ?>" class="btn btn-outline-primary btn-sm">
                    <?= esc($role['name']) ?>
                </a>

            <?php endforeach; ?>
        </div>
    </div>

    <!-- Permissions Form -->
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Assign Permissions to Role</h5>
        </div>
        <div class="card-body">
            <form action="<?= site_url('role_permissions/save') ?>" method="post">
                <?= csrf_field() ?>

                <div class="mb-3">
                    <label for="role_id" class="form-label">Select Role</label>
                    <select name="role_id" id="role_id" class="form-control" required>
                        <option value="">-- Select Role --</option>
                        <?php foreach ($roles as $role) : ?>
                          <?php  if($role['id']!=2 && $role['id']!=4)
                            {?>
                            <option value="<?= $role['id'] ?>" <?= isset($roleId) && $roleId == $role['id'] ? 'selected' : '' ?>>
                                <?= esc($role['name']) ?>
                            </option>
                          <?php }?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <h5 class="mt-4 mb-3">Available Permissions</h5>

                <div class="mb-3">
                    <button type="button" id="toggleCheckBtn" class="btn btn-secondary btn-sm">
                        <i class="bi bi-check2-square"></i> Check All
                    </button>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Permission</th>
                                <th>Main</th>
                                <th>View</th>
                                <th>Add</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($permissions as $permission) : ?>
                                <tr>
                                    <td><strong><?= esc($permission['name']) ?></strong></td>
                                    <td class="text-center">
                                        <input type="checkbox" name="permissions[]" value="<?= $permission['id'] ?>"
                                            <?= isset($role_permissions[$permission['id']]) ? 'checked' : '' ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="can_view[<?= $permission['id'] ?>]" value="1"
                                            <?= isset($role_permissions[$permission['id']]) && $role_permissions[$permission['id']]['can_view'] ? 'checked' : '' ?>>
                                    </td>

                                    <td class="text-center">
                                        <input type="checkbox" name="can_add[<?= $permission['id'] ?>]" value="1"
                                            <?= isset($role_permissions[$permission['id']]) && $role_permissions[$permission['id']]['can_add'] ? 'checked' : '' ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="can_edit[<?= $permission['id'] ?>]" value="1"
                                            <?= isset($role_permissions[$permission['id']]) && $role_permissions[$permission['id']]['can_edit'] ? 'checked' : '' ?>>
                                    </td>
                                    <td class="text-center">
                                        <input type="checkbox" name="can_delete[<?= $permission['id'] ?>]" value="1"
                                            <?= isset($role_permissions[$permission['id']]) && $role_permissions[$permission['id']]['can_delete'] ? 'checked' : '' ?>>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>

                <div class="text-end mt-4">
                    <button type="submit" class="btn btn-success">
                        <i class="bi bi-save2"></i> Save Permissions
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Optional Modal (for AJAX Preview - disabled unless needed) -->
<!--
Include modal only if you're actually triggering it from somewhere.
Right now, it's not active so you may remove if unused.
-->

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        let allChecked = false;

        $('#toggleCheckBtn').click(function() {
            allChecked = !allChecked;

            $('input[type="checkbox"]').prop('checked', allChecked);

            // Update button text and icon
            if (allChecked) {
                $(this).html('<i class="bi bi-x-square"></i> Uncheck All');
            } else {
                $(this).html('<i class="bi bi-check2-square"></i> Check All');
            }
        });
    });
</script>


<?= $this->endSection() ?>

<?php } ?>
<?php endforeach; ?>
<?php endif; ?>
