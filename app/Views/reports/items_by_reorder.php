<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <h3 class="mb-4">Items by Reorder Levels</h3>

    <?php if (session()->getFlashdata('message')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
    <?php endif; ?>

    <!-- Filter Section -->
	<form method="get" action="<?= site_url('reorder-report') ?>" class="mb-4 row g-3">
		<div class="col-md-4">
			<input type="text" name="search" class="form-control" placeholder="Search by item name or number" value="<?= esc($search ?? '') ?>">
		</div>
		<div class="col-md-3">
			<select name="category_id" class="form-select">
				<option value="">All Categories</option>
				<?php foreach ($categories as $cat): ?>
					<option value="<?= $cat['id'] ?>" <?= ($category_id == $cat['id']) ? 'selected' : '' ?>>
						<?= esc($cat['category_name']) ?>
					</option>
				<?php endforeach ?>
			</select>
		</div>
		<div class="col-md-2 d-flex gap-2">
			<button type="submit" class="btn btn-primary w-100">Filter</button>
			<a href="<?= site_url('reorder-report') ?>" class="btn btn-outline-secondary w-100">Reset</a>
		</div>
		<div class="col-md-3 d-flex gap-2 justify-content-end">
			<?php $params = http_build_query(['search' => $search ?? '', 'category_id' => $category_id ?? '']); ?>
			<a href="<?= site_url("reorder-report/export/pdf?$params") ?>" class="btn btn-danger">
				<i class="bi bi-file-earmark-pdf-fill"></i> PDF
			</a>
			<a href="<?= site_url("reorder-report/export/word?$params") ?>" class="btn btn-secondary">
				<i class="bi bi-file-earmark-word-fill"></i> Word
			</a>
			<a href="<?= site_url("reorder-report/export/excel?$params") ?>" class="btn btn-success">
				<i class="bi bi-file-earmark-excel-fill"></i> Excel
			</a>
		</div>
	</form>

    <!-- Table Section -->
    <div class="table-responsive">
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>#</th>
                    <th>Item Number</th>
                    <th>Item Name</th>
                    <th>Category</th>
                    <th>Current Quantity</th>
                    <th>Reorder Level</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($items)): ?>
                    <tr>
                        <td colspan="7" class="text-center">No items found at or below reorder levels.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($items as $index => $item): ?>
                        <tr>
                            <td><?= $index + 1 ?></td>
                            <td><?= esc($item['item_no']) ?></td>
                            <td><?= esc($item['name']) ?></td>
                            <td><?= esc($item['category_name']) ?></td>
                            <td><?= esc($item['quantity']) ?></td>
                            <td><?= esc($item['reorder_level_quantity']) ?></td>
                            <td>
                                <?php if ($item['quantity'] <= $item['reorder_level_quantity']): ?>
                                    <span class="badge bg-danger">Reorder Needed</span>
                                <?php else: ?>
                                    <span class="badge bg-success">Sufficient</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?= $this->endSection() ?>
