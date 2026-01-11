<?= $this->extend('layout') ?>

<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='roles')
      { ?>

        <?php if ($link['can_edit']=='1')
         { ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Edit Role</h4>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('roles/update/' . $role['id']) ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Role Name</label>
                            <input type="text" name="name" id="name" class="form-control" value="<?= esc($role['name']) ?>" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= site_url('roles') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left"></i> Cancel
                            </a>
                            <?php
                            if ($role['id']==2)
                            {

                            }else{?>
                            <button type="submit" class="btn btn-success">
                                <i class="bi bi-save"></i> Update Role
                            </button>
                          <?php }?>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?php } ?><?php } ?>
<?php endforeach; ?>
<?php endif; ?>
