<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Shops</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <div class="row mb-3">
        <div class="col-md-6">
            <form action="<?= site_url('shops') ?>" method="get" class="d-flex">
                <input type="text" name="search" class="form-control me-2" placeholder="Search by shop name..." value="<?= esc($search ?? '') ?>">
                <button type="submit" class="btn btn-outline-primary">Search</button>
            </form>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= site_url('shops/create') ?>" class="btn btn-success">Add Shop</a>
        </div>
    </div>

    <table class="table table-bordered table-striped">
        <thead class="table-light">
            <tr>
                <th>#</th>
                <th>Shop Name</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($shops)): ?>
                <?php foreach ($shops as $index => $shop): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= esc($shop['name']) ?></td>
                        <td>
                            <a href="<?= site_url('shops/edit/' . $shop['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                            <form action="<?= site_url('shops/delete/' . $shop['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this shop?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php else: ?>
                <tr>
                    <td colspan="3" class="text-center">No shops found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
