<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>🎯 Discount Settings</h2>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
    <?php endif; ?>

    <form method="get" class="row g-3 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" value="<?= esc($search) ?>" placeholder="Search discount name...">
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100">Search</button>
        </div>
        <div class="col-md-6 text-end">
            <a href="<?= site_url('discount_settings/create') ?>" class="btn btn-success">➕ Add New</a>
        </div>
    </form>

    <table class="table table-bordered table-striped shadow-sm">
        <thead class="table-light">
            <tr>
                <th>Name</th>
                <th>Amount</th>
                <th>Min Amount</th>
                <th>Max Amount</th>
                <th>Enabled</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($discounts as $discount): ?>
                <tr>
                    <td><?= esc($discount['discount_name']) ?></td>
                    <td><?= esc($discount['discount_amount']) ?></td>
                    <td><?= esc($discount['min_shopping_amount']) ?></td>
                    <td><?= esc($discount['maximum_shopping_amount']) ?></td>
                    <td><?= $discount['enabled'] ? '✅' : '❌' ?></td>
                    <td>
                        <a href="<?= site_url('discount_settings/edit/' . $discount['id']) ?>" class="btn btn-sm btn-primary">✏️ Edit</a>
                        <a href="<?= site_url('discount_settings/delete/' . $discount['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this discount?')">🗑️ Delete</a>
                    </td>
                </tr>
            <?php endforeach ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
