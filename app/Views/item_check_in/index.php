<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <h2 class="my-4">Item Check-In List</h2>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <a href="<?= site_url('item_check_in/create') ?>" class="btn btn-primary mb-3">New Check-In</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>Item Name</th>
                <th>Supplier Name</th>
                <th>Qty</th>
                <th>Unit Price</th>
                <th>Total</th>
                <th>Date</th>
                <th>Time</th>
                <th>User</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($check_ins as $row): ?>
                <tr>
                    <td><?= esc($row['id']) ?></td>
                    <td><?= esc($row['item_name']) ?></td>
                    <td><?= esc($row['supplier_name']) ?></td>
                    <td><?= esc($row['check_in_quantity']) ?></td>
                    <td><?= esc($row['unit_price']) ?></td>
                    <td><?= esc($row['total_price']) ?></td>
                    <td><?= esc($row['date']) ?></td>
                    <td><?= esc($row['time']) ?></td>
                    <td><?= esc($row['user_name']) ?></td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?= $this->endSection() ?>
