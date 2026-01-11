<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <h2 class="my-4">Suppliers</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <form method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="filter" class="form-control" placeholder="Search suppliers..." value="<?= esc($filter) ?>">
            <button class="btn btn-primary">Search</button>
        </div>
    </form>

    <a href="<?= site_url('suppliers/create') ?>" class="btn btn-success mb-3">Add Supplier</a>

    <?php if (!empty($suppliers)): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Email</th>
                    <th>Address</th>
                    <th>Town</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($suppliers as $supplier): ?>
                    <tr>
                        <td><?= esc($supplier['name']) ?></td>
                        <td><?= esc($supplier['phone']) ?></td>
                        <td><?= esc($supplier['email']) ?></td>
                        <td><?= esc($supplier['address']) ?></td>
                        <td><?= esc($supplier['town']) ?></td>
                        <td>
                            <a href="<?= site_url('suppliers/edit/' . $supplier['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="<?= site_url('suppliers/delete/' . $supplier['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Are you sure?')">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No suppliers found.</div>
    <?php endif ?>
</div>

<?= $this->endSection() ?>
