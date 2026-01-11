<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Cashier Sales Report</h2>

    <form class="row mb-3" method="get">
        <div class="col-md-3">
            <select name="cashier" class="form-select">
                <option value="">-- Select Cashier --</option>
                <?php foreach ($cashiers as $c): ?>
                    <option value="<?= esc($c->username) ?>" <?= ($filters['cashier'] ?? '') === $c->username ? 'selected' : '' ?>>
                        <?= esc($c->username) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-3">
            <input type="date" name="start_date" class="form-control" value="<?= esc($filters['startDate'] ?? '') ?>">
        </div>
        <div class="col-md-3">
            <input type="date" name="end_date" class="form-control" value="<?= esc($filters['endDate'] ?? '') ?>">
        </div>
        <div class="col-md-3 d-flex gap-2">
            <button class="btn btn-primary" type="submit">Filter</button>
            <a href="<?= base_url('cashier-sales-report') ?>" class="btn btn-secondary">Reset</a>
        </div>
    </form>

    <?php
    $exportQuery = http_build_query([
        'cashier' => $filters['cashier'] ?? '',
        'start_date' => $filters['startDate'] ?? '',
        'end_date' => $filters['endDate'] ?? '',
    ]);
    ?>

    <div class="mb-3">
        <a href="<?= base_url('cashier-sales-report/export/pdf?' . $exportQuery) ?>" class="btn btn-outline-danger">
            <i class="bi bi-file-earmark-pdf"></i> Export PDF
        </a>
        <a href="<?= base_url('cashier-sales-report/export/word?' . $exportQuery) ?>" class="btn btn-outline-primary">
            <i class="bi bi-file-earmark-word"></i> Export Word
        </a>
        <a href="<?= base_url('cashier-sales-report/export/excel?' . $exportQuery) ?>" class="btn btn-outline-success">
            <i class="bi bi-file-earmark-excel"></i> Export Excel
        </a>
    </div>

	<table class="table table-bordered table-striped">
		<thead class="table-dark">
			<tr>
				<th>#</th>
				<th>Cashier</th>
				<th>Item</th>
				<th>Qty</th>
				<th>Unit Price</th>
				<th>Total Cost</th>
				<th>Discount</th>
				<th>Date</th>
			</tr>
		</thead>
		<tbody>
			<?php $i = 1; foreach ($sales as $row): ?>
			<tr>
				<td><?= $i++ ?></td>
				<td><?= esc($row->cashier_name) ?></td>
				<td><?= esc($row->item_name) ?></td>
				<td><?= esc($row->quantity) ?></td>
				<td><?= esc($row->sellingPrice) ?></td>
				<td><?= esc($row->total_cost) ?></td>
				<td><?= esc($row->total_discount) ?></td>
				<td><?= esc($row->created_at) ?></td>
			</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

</div>

<?= $this->endSection() ?>
