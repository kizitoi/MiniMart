<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php
$filterItem = $filters['item'] ?? '';
$filterCashier = $filters['cashier'] ?? '';
$filterFrom = $filters['from'] ?? '';
$filterTo = $filters['to'] ?? '';
?>

<div class="container mt-4">
    <h2 class="mb-4">📊 Sales Order Report</h2>

    <form method="get" class="row g-3 mb-4 border p-3 rounded shadow-sm bg-light">
        <div class="col-md-3">
            <input type="text" name="item" class="form-control" value="<?= esc($filterItem) ?>" placeholder="Filter by item name">
        </div>
        <div class="col-md-3">
            <select name="cashier" class="form-select" onchange="this.form.submit()">
                <option value="">All Cashiers</option>
                <?php foreach ($cashiers as $c): ?>
                    <option value="<?= esc($c['username']) ?>" <?= $filterCashier == $c['username'] ? 'selected' : '' ?>>
                        <?= esc(ucfirst($c['username'])) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <input type="date" name="from" class="form-control" value="<?= esc($filterFrom) ?>">
        </div>
        <div class="col-md-2">
            <input type="date" name="to" class="form-control" value="<?= esc($filterTo) ?>">
        </div>
        <div class="col-md-2">
            <button type="submit" class="btn btn-primary w-100">🔍 Filter</button>
        </div>
    </form>

    <div class="mb-3 d-flex justify-content-end gap-2">
        <a href="<?= base_url('sales-orders-report/pdf?' . http_build_query($_GET)) ?>" class="btn btn-danger btn-sm"> 
		<i class="fa fa-file-pdf-o"></i> PDF
		</a>
        <a href="<?= base_url('sales-orders-report/word?' . http_build_query($_GET)) ?>" class="btn btn-primary btn-sm"> <i class="fa fa-file-word-o"></i> Word
		</a>
        <a href="<?= base_url('sales-orders-report/excel?' . http_build_query($_GET)) ?>" class="btn btn-success btn-sm"> <i class="fa fa-file-excel-o"></i> Excel
		</a>
    </div>

	<?php if (!empty($salesOrders)): ?>
		<div class="card shadow-sm">
			<div class="card-body p-0">
				<div class="table-responsive">
					<table class="table table-striped table-bordered mb-0">
						<thead class="table-dark">
							<tr>
								<th>Order No</th>
								<th>Item Name</th>
								<th>Quantity</th>
								<th>Price</th>
								<th>Cashier</th>
								<th>Payment Method</th>
								<th>Date</th>
							</tr>
						</thead>
						<tbody>
							<?php 
							foreach ($salesOrders as $orderId => $orderItems): ?>
								<?php foreach ($orderItems as $index => $item): ?>
									<tr>
										<td><?= esc($orderId) ?></td>
										<td><?= esc($item->item_name) ?></td>
										<td><?= esc($item->quantity) ?></td>
										<td>Kshs<?= number_format($item->sellingPrice, 2) ?></td>
										<td><?= esc($item->cashier) ?></td>
										<td><?= esc($item->payment_method ?? '-') ?></td>
										<td><?= date('Y-m-d H:i', strtotime($item->created_at)) ?></td>
									</tr>
								<?php endforeach; ?>
							<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		</div>
	<?php else: ?>
		<div class="alert alert-warning">No sales orders found.</div>
	<?php endif; ?>

</div>

<?= $this->endSection() ?>
