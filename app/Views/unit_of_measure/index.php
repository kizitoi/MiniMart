<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <h2 class="my-4">Unit of Measure</h2>

    <!-- Filter Form -->
    <form method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="filter" class="form-control" placeholder="Filter by Unit Name..." value="<?= esc($filter) ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <!-- Add Button -->
    <a href="<?= site_url('unit_of_measure/create') ?>" class="btn btn-success mb-3">Add Unit</a>

    <!-- Units Table -->
    <?php if (!empty($units)): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Unit Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($units as $index => $unit): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= esc($unit['unit_name']) ?></td>
                        <td>
                            <!-- Edit Button -->
                            <a href="<?= site_url('unit_of_measure/edit/' . $unit['id']) ?>" class="btn btn-warning btn-sm">Edit</a>

                            <!-- Delete Button -->
                            <form action="<?= site_url('unit_of_measure/delete/'.$unit['id']) ?>" method="post" style="display:inline;" onsubmit="return confirm('Are you sure?');">
                                <?= csrf_field() ?>
                                <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                            </form>
                        </td>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-info">No units found.</div>
    <?php endif ?>
</div>

<?= $this->endSection() ?>
