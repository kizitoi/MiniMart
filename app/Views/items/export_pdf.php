<!DOCTYPE html>
<html>
<head>
    <style>
        table { width: 100%; border-collapse: collapse; font-size: 12px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
    </style>
</head>
<body>
<h3>Items Report</h3>
<table>
    <thead>
        <tr>
            <th>Item No</th>
            <th>Name</th>
            <th>Category</th>
            <th>Qty</th>
            <th>Reorder</th>
            <th>Price</th>
        </tr>
    </thead>
    <tbody>
    <?php foreach ($items as $item): ?>
        <tr>
            <td><?= esc($item['item_no']) ?></td>
            <td><?= esc($item['name']) ?></td>
            <td><?= esc($item['category_name']) ?></td>
            <td><?= esc($item['quantity']) ?></td>
            <td><?= esc($item['reorder_level_quantity']) ?></td>
            <td><?= number_format($item['unit_price'], 2) ?></td>
        </tr>
    <?php endforeach ?>
    </tbody>
</table>
</body>
</html>
