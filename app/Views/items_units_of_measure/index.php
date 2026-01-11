<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container">
    <h2 class="my-4">Units of Measure for Item #<?= esc($itemId) ?></h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif ?>

    <!-- Add Button -->
    <a href="<?= site_url('items_units_of_measure/create/' . $itemId) ?>" class="btn btn-success mb-3">Add Unit</a>

    <?php if (!empty($units)): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Unit Name</th>
                    <th>Unit Value</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($units as $unit): ?>
                    <tr>
                        <td><?= esc($unit['id']) ?></td>
                        <td><?= esc($unit['unit_name']) ?></td>
                        <td><?= esc($unit['unit_value']) ?></td>
                        <td>
                            <a href="<?= site_url('items_units_of_measure/edit/' . $unit['id']) ?>" class="btn btn-warning btn-sm">Edit</a>
                            <form action="<?= site_url('items_units_of_measure/delete/' . $unit['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No units found for this item.</div>
    <?php endif ?>
</div>

<?= $this->endSection() ?>
