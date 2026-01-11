<?= $this->extend('layout') ?>

<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']!='roles')
      { ?>

header('Location: https://nairobimetaldetectors.net/index.php/officer/officer', true, 301);
exit;

<?PHP } ?>
 
<?php endforeach; ?>
<?php endif; ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">Manage Roles</h2>
        <?php if ($link['can_add']=='1')
         { ?>
        <a href="<?= site_url('roles/create') ?>" class="btn btn-success">
            <i class="bi bi-plus-circle"></i> Add New Role
        </a>
      <?php } ?>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card shadow-sm">
        <div class="card-body">
            <table class="table table-hover align-middle">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th style="width: 30%">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($roles as $role): ?>
                        <tr>
                            <td><?= esc($role['id']) ?></td>
                            <td><?= esc($role['name']) ?></td>
                            <td>
                            <?php  if ($role['id']==2 || $role['id']==4)
                              {
                              }else{?>

                                <?php if ($link['can_edit']=='1')
                                 { ?>
                                <a href="<?= site_url('roles/edit/' . $role['id']) ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                              <?php }?>

                              <?php if ($link['can_delete']=='1')
                               { ?>
                                <a href="<?= site_url('roles/delete/' . $role['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete this role?')">
                                    <i class="bi bi-trash"></i> Delete
                                </a>
                              <?php }?>
                            <?php  }?>
                                <a href="<?= site_url('role-permissions/view/' . $role['id']) ?>" class="btn btn-sm btn-info">
                                    <i class="bi bi-shield-lock"></i> Permissions
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?= $this->endSection() ?>


