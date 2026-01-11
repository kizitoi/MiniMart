<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <h2>Permissions</h2>
    <a href="<?= site_url('permissions/add') ?>" class="btn btn-primary mb-3">Add Permission</a>

    <form method="get" class="mb-3 d-flex">
        <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Search by name or label" class="form-control me-2" style="max-width:300px;">
        <button type="submit" class="btn btn-secondary">Search</button>
    </form>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-hover align-middle">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Label</th>
                <th>Link</th>
                <th>Icon</th>
                <th>Button?</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($permissions as $perm): ?>
            <tr>
                <td><?= $perm['id'] ?></td>
                <td><?= esc($perm['name']) ?></td>
                <td><?= esc($perm['label']) ?></td>
                <td><?= esc($perm['link']) ?></td>
                <td><i class="<?= esc($perm['icon']) ?>"></i> <?= esc($perm['icon']) ?></td>
                <td><?= $perm['isbutton'] ? 'Yes' : 'No' ?></td>
                <td>
                    <a href="<?= site_url('permissions/edit/'.$perm['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="<?= site_url('permissions/delete/'.$perm['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this permission?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

  <!--  <?//= $pager->links() ?>-->
    <?//= $pager->links('permissions', 'default_full') ?>

    <!-- Pagination -->
  <?php if (isset($pager)) : ?>
    <div class="mt-4">
      <?= $pager->links('permissions', 'short_pagination') ?>
    </div>
  <?php endif; ?>


</div>

<?= $this->endSection() ?>
