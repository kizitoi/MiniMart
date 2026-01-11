<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <h2>System Documentation</h2>

    <form method="get" class="mb-3">
        <input type="text" name="search" value="<?= esc($search) ?>" placeholder="Search by title" class="form-control" style="width:300px; display:inline-block;">
        <button type="submit" class="btn btn-secondary">Search</button>
    </form>

    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Title</th>
                <th>Download</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($docs as $doc): ?>
            <tr>
                <td><?= $doc['id'] ?></td>
                <td><?= esc($doc['title']) ?></td>
                <td>
                    <a href="<?= site_url('system_documentation/download/'.$doc['id']) ?>" class="btn btn-sm btn-primary">Download</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <?= $pager->links() ?>
</div>

<?= $this->endSection() ?>
