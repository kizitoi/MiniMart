<?= $this->extend('layout') ?>

<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']!='roles')
      { ?>

 header('Location: https://nairobimetaldetectors.net/index.php/officer/officer', true, 301);
exit;

<?php } ?>
<?php endforeach; ?>
<?php endif; ?>

<?= $this->section('content') ?>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0">Add New Role</h4>
                </div>
                <div class="card-body">
                    <form action="<?= site_url('roles/store') ?>" method="post">
                        <?= csrf_field() ?>

                        <div class="mb-3">
                            <label for="name" class="form-label">Role Name</label>
                            <input type="text" name="name" id="name" class="form-control" placeholder="Enter role name" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="<?= site_url('roles') ?>" class="btn btn-secondary">
                                <i class="bi bi-arrow-left-circle"></i> Cancel
                            </a>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle"></i> Save Role
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
 

