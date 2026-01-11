<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <h2 class="mb-4">Numbering Settings</h2>

    <!-- Alerts -->
    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif ?>
    <?php if (session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif ?>

    <!-- Filter Form -->
    <form method="get" class="mb-3 d-flex gap-2">
        <input type="text" name="filter" class="form-control" placeholder="Search..." value="<?= esc($filter) ?>">
        <button class="btn btn-primary">Search</button>
    </form>

    <a href="/numbering_settings/create" class="btn btn-success mb-3">Add New</a>

    <table class="table table-bordered table-hover">
        <thead>
            <tr>
                <th>Name</th>
                <th>Apply To</th>
                <th>Prefix</th>
                <th>Start</th>
                <th>Last Used</th>
                <th>Auto Increment</th>
                <th>Manual Entry</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($numberings as $n): ?>
            <tr>
                <td><?= esc($n['name']) ?></td>
                <td><?= esc($n['apply_to']) ?></td>
                <td><?= esc($n['prefix']) ?></td>
                <td><?= esc($n['start']) ?></td>
                <td><?= esc($n['last_used']) ?></td>
                <td><?= $n['auto_increment'] ? 'Yes' : 'No' ?></td>
                <td><?= $n['allow_manual_entry'] ? 'Yes' : 'No' ?></td>
                <td>
                    <a href="/numbering_settings/edit/<?= $n['id'] ?>" class="btn btn-warning btn-sm">Edit</a>
                    <a href="/numbering_settings/delete/<?= $n['id'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Delete this?')">Delete</a>
                </td>
            </tr>
        <?php endforeach ?>
        </tbody>
    </table>
</div>
<?= $this->endSection() ?>
