<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Inventory Report</h2>

    <form method="get" action="<?= site_url('inventory-report') ?>" class="mb-4 row g-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="Search by item name or number" value="<?= esc($filters['search'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <select name="category_id" class="form-select">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= ($filters['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= esc($cat['category_name']) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>
        <div class="col-md-2 d-flex gap-2">
            <button type="submit" class="btn btn-primary w-100">Filter</button>
            <a href="<?= site_url('inventory-report') ?>" class="btn btn-outline-secondary w-100">Reset</a>
        </div>
        <div class="col-md-3 d-flex gap-2 justify-content-end">
            <?php $params = http_build_query(['search' => $filters['search'] ?? '', 'category_id' => $filters['category_id'] ?? '']); ?>
            <a href="<?= site_url("inventory-report/export/pdf?$params") ?>" class="btn btn-danger">
                <i class="bi bi-file-earmark-pdf-fill"></i> PDF
            </a>
            <a href="<?= site_url("inventory-report/export/word?$params") ?>" class="btn btn-secondary">
                <i class="bi bi-file-earmark-word-fill"></i> Word
            </a>
            <a href="<?= site_url("inventory-report/export/excel?$params") ?>" class="btn btn-success">
                <i class="bi bi-file-earmark-excel-fill"></i> Excel
            </a>
        </div>
    </form>


    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Item No</th>
                <th>Item Name</th>
                <th>Description</th>
                <th>Item Category</th>
                <th>Quantity</th>
                <th>Reorder Qty</th>
                <th>Unit Price</th>
            </tr>
        </thead>
        <tbody>
            <?php $i = 1; foreach ($items as $item): ?>
              <tr>
                <td><?= esc($i++) ?></td>
                <td><?= esc($item['item_no']) ?></td>
                <td><?= esc($item['name']) ?></td>
                <td><?= esc($item['description']) ?></td>
                <td><?= esc($item['category_name']) ?></td>
                <td><?= esc($item['quantity']) ?></td>
                <td><?= esc($item['reorder_level_quantity']) ?></td>
                <td><?= esc($item['unit_price']) ?></td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</div>

<?= $this->endSection() ?>
