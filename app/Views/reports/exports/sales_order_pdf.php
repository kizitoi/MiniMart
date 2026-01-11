<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Sales Order Report</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        h2 { text-align: center; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #333; padding: 6px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h2>Sales Order Report</h2>

    <?php if (!empty($salesOrders)): ?>
        <?php foreach ($salesOrders as $orderId => $orderItems): ?>
            <h4>Order #<?= esc($orderId) ?></h4>
            <table>
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Price</th>
                        <th>Cashier</th>
                        <th>Payment Method</th>
                        <th>Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orderItems as $item): ?>
                        <tr>
                            <td><?= esc($item->item_name) ?></td>
                            <td><?= esc($item->quantity) ?></td>
                            <td>Kshs<?= number_format($item->sellingPrice, 2) ?></td>
                            <td><?= esc($item->cashier) ?></td>
                            <td><?= esc($item->payment_method ?? '-') ?></td>
                            <td><?= date('Y-m-d H:i', strtotime($item->created_at)) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endforeach; ?>
    <?php else: ?>
        <p>No sales orders found.</p>
    <?php endif; ?>
</body>
</html>
