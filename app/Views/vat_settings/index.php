<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container">
    <h2 class="my-4">VAT Settings</h2>

    <!-- Flash Messages -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- Filter Form -->
    <form method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="filter" class="form-control" placeholder="Filter by VAT Name..." value="<?= esc($filter) ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <!-- Add Button -->
    <a href="<?= site_url('vat_settings/create') ?>" class="btn btn-success mb-3">Add VAT</a>

    <!-- VAT Table -->
    <?php if (!empty($vats)): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>VAT Name</th>
                    <th>Percentage</th>
                    <th>VAT Code</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($vats as $index => $vat): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= esc($vat['vat_name']) ?></td>
                        <td><?= esc($vat['vat_perc']) ?>%</td>
                        <td><?= esc($vat['vat_code']) ?></td>
                        <td>
                            <!-- Edit Button -->
                            <a href="<?= site_url('vat_settings/edit/' . $vat['id']) ?>" class="btn btn-warning btn-sm">Edit</a>

                            <!-- Delete Button -->
                            <form action="<?= site_url('vat_settings/delete/' . $vat['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Are you sure you want to delete this VAT setting?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No VAT settings found.</div>
    <?php endif ?>
</div>

<?= $this->endSection() ?>
