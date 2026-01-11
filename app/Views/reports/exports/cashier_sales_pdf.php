<!DOCTYPE html>
<html>
<head>
    <title>Cashier Sales Report</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background: #eee; }
    </style>
</head>
<body>
    <h2>Cashier Sales Report</h2>
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

</body>
</html>
