<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container my-3">
    <h3 class="mb-3">Items</h3>

    <!-- Search form -->
    <form method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="search" value="<?= esc($search ?? '') ?>" class="form-control" placeholder="Search items...">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <?php if (!empty($items)): ?>
        <div class="row g-3">
            <?php foreach ($items as $item): ?>
                <div class="col-12">
                    <div class="card shadow-sm border-0 rounded-3">
                        <div class="card-body">
                            <h5 class="card-title"><?= esc($item['name']) ?></h5>
                            <p class="card-text">
                                <strong>Price:</strong> <?= esc($item['unit_price']) ?><br>
                                <strong>Category:</strong> <?= esc($item['category'] ?? 'N/A') ?><br>
                                <strong>Created:</strong> <?= date('M d, Y', strtotime($item['created_at'])) ?>
                            </p>
                            <div class="d-flex justify-content-between mt-2">
                                <a href="<?= site_url('items/edit/'.$item['id']) ?>" class="btn btn-sm btn-warning">
                                    <i class="bi bi-pencil-square"></i> Edit
                                </a>
                                <form action="<?= site_url('items/delete/'.$item['id']) ?>" method="post" onsubmit="return confirm('Are you sure?');">
                                    <?= csrf_field() ?>
                                    <button type="submit" class="btn btn-sm btn-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        

        <!-- Pagination -->
        <div class="mt-3">
            <?= $pager->links('items', 'bootstrap_full') ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info">No items found.</div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
