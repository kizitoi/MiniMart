<?= $this->extend('layout') ?>



<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='departments')
      { ?>

<?= $this->section('content') ?>
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0">📁 Departments</h2>

          <?php if ($link['can_add']=='1')
          {?>
        <a href="<?= site_url('departments/create') ?>" class="btn btn-primary">
            <i class="bi bi-plus-circle"></i> Add Department
        </a>

        <?php }?>
    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('success') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle shadow-sm">
            <thead class="table-dark">
                <tr>
                    <th>ID</th>
                    <th>Department Name</th>
                    <th>Description</th>
                    <th class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($departments as $dept): ?>
                    <tr>
                        <td><?= $dept['id'] ?></td>
                        <td><?= esc($dept['name']) ?></td>
                        <td><?= esc($dept['description']) ?></td>
                        <td class="text-center">

                          <?php if ($link['can_edit']=='1')
                           { ?>
                            <a href="<?= site_url('departments/edit/'.$dept['id']) ?>" class="btn btn-sm btn-outline-warning me-1">
                                <i class="bi bi-pencil-square"></i> Edit
                            </a>
                          <?php } ?>

                          <?php if ($link['can_delete']=='1')
                           { ?>
                            <form action="<?= site_url('departments/delete/'.$dept['id']) ?>" method="post" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this department?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-outline-danger">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </form>

                                <?php } ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
                <?php if (empty($departments)) : ?>
                    <tr>
                        <td colspan="4" class="text-center text-muted">No departments available.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?= $this->endSection() ?>

<?php } ?>
<?php endforeach; ?>
<?php endif; ?>
