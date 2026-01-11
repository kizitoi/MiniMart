<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Items by Reorder Level</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; margin-top: 20px; }
        th, td { border: 1px solid #000; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
        h3 { text-align: center; margin-bottom: 10px; }
    </style>
</head>
<body>
    <h3>Items by Reorder Levels</h3>
    <table>
        <thead>
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
                    <td colspan="7" style="text-align:center;">No items found.</td>
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
                            <?= $item['quantity'] <= $item['reorder_level_quantity']
                                ? 'Reorder Needed'
                                : 'Sufficient' ?>
                        </td>
                    </tr>
                <?php endforeach ?>
            <?php endif; ?>
        </tbody>
    </table>
</body>
</html>
