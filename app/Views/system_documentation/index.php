<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <h2>System Documentation</h2>

    <a href="<?= site_url('system_documentation/add') ?>" class="btn btn-primary mb-3">Add Document</a>

    <form method="get" class="mb-3">
        <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Search by title" class="form-control" style="width:300px; display:inline-block;">
        <button type="submit" class="btn btn-secondary">Search</button>
    </form>

    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>File</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($docs as $doc): ?>
            <tr>
                <td><?= $doc['id'] ?></td>
                <td><?= esc($doc['title']) ?></td>
                <td><?= esc($doc['file_name']) ?></td>
                <td>
                    <a href="<?= site_url('system_documentation/edit/'.$doc['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                    <a href="<?= site_url('system_documentation/delete/'.$doc['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?')">Delete</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?= $pager->links() ?>
</div>

<?= $this->endSection() ?>
