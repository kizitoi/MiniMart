<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 12px;
        }
        th, td {
            border: 1px solid #000;
            padding: 6px;
        }
        th {
            background-color: #ddd;
        }
    </style>
</head>
<body>
    <h3>Inventory Report</h3>
    <table>
        <thead>
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
                  <td><?= $i++ ?></td>
                  <td><?= is_object($item) ? esc($item->item_no) : esc($item['item_no']) ?></td>
                  <td><?= is_object($item) ? esc($item->name) : esc($item['name']) ?></td>
                  <td><?= is_object($item) ? esc($item->description) : esc($item['description']) ?></td>
                  <td><?= is_object($item) ? esc($item->category_name) : esc($item['category_name']) ?></td>
                  <td><?= is_object($item) ? esc($item->quantity) : esc($item['quantity']) ?></td>
                  <td><?= is_object($item) ? esc($item->reorder_level_quantity) : esc($item['reorder_level_quantity']) ?></td>
                  <td><?= is_object($item) ? esc($item->unit_price) : esc($item['unit_price']) ?></td>
              </tr>
          <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
