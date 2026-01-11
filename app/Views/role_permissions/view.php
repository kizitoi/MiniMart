<?= $this->extend('layout') ?>

<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='role_permissions')
      { ?>


<?= $this->section('content') ?>

<div class="container py-4">
    <div class="card shadow-sm border-0">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h4 class="mb-0">Role Permissions for <span class="badge badge-light text-dark"><?= esc($role['name']) ?></span></h4>
        </div>

        <div class="card-body">
            <form action="<?= site_url('role_permissions/updatePermissions') ?>" method="post">
                <?= csrf_field() ?>
                <input type="hidden" name="role_id" value="<?= esc($role['id']) ?>">

                <div class="table-responsive">
                    <table class="table table-hover table-bordered align-middle">
                        <thead class="thead-light">
                            <tr>
                                <th>Permission</th>
                                <th class="text-center">View</th>
                                <th class="text-center">Add</th>
                                <th class="text-center">Edit</th>
                                <th class="text-center">Delete</th>
                            </tr>
                        </thead>


                        <tbody>
                          <?php if (!empty($permissions)) : ?>
                              <?php foreach ($permissions as $permission) : ?>
                                  <tr>
                                      <td><strong><?= esc($permission['permission_name']) ?></strong></td>
                                      <td class="text-center">
                                          <input type="checkbox" disabled
                                              <?= $permission['can_view'] ? 'checked' : '' ?>>
                                      </td>

                                      <td class="text-center">
                                          <input type="checkbox" disabled
                                              <?= $permission['can_add'] ? 'checked' : '' ?>>
                                      </td>
                                      
                                      <td class="text-center">
                                          <input type="checkbox" disabled
                                              <?= $permission['can_edit'] ? 'checked' : '' ?>>
                                      </td>
                                      <td class="text-center">
                                          <input type="checkbox" disabled
                                              <?= $permission['can_delete'] ? 'checked' : '' ?>>
                                      </td>
                                  </tr>
                              <?php endforeach; ?>
                          <?php else : ?>
                              <tr>
                                  <td colspan="4" class="text-center text-muted">No permissions assigned to this role.</td>
                              </tr>
                          <?php endif; ?>
                      </tbody>



                    </table>
                </div>

                <div class="text-right">
                  <!--  <button type="submit" class="btn btn-success mt-3">
                        <i class="fas fa-save"></i> Update Permissions
                    </button> -->
                    <a href="<?= site_url('role_permissions') ?>" class="btn btn-secondary">
                        <i class="bi bi-arrow-left-circle"></i> Back
                    </a>

                </div>
            </form>
        </div>
    </div>
</div>

<!-- FontAwesome for icons (optional) -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />

<?= $this->endSection() ?>

<?php } ?>
<?php endforeach; ?>
<?php endif; ?>
