<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Available Reports</h2>

    <form method="get" action="<?= site_url('reports') ?>" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" value="<?= esc($search) ?>" class="form-control" placeholder="Filter by report name">
            <button type="submit" class="btn btn-primary">Search</button>
        </div>
    </form>

    <?php if (!empty($reports)): ?>
        <table class="table table-bordered table-striped">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Report Name</th>
                    <th>Created At</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reports as $index => $report): ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td>
                            <a href="<?= site_url('reports/view/' . $report['id']) ?>">
                                <?= esc($report['report_name']) ?>
                            </a>
                        </td>
                        <td><?= date('Y-m-d H:i', strtotime($report['created_at'])) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <div class="alert alert-warning">No reports found.</div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
