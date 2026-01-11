<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<?php
$filterItem = $filters['item'] ?? '';
$filterCashier = $filters['cashier'] ?? '';
$filterFrom = $filters['from'] ?? '';
$filterTo = $filters['to'] ?? '';
$filterCustomer = $filters['customer'] ?? ''; // ✅ new
?>

<div class="container mt-4">
    <h2 class="mb-4">🧾 Sales Order History</h2>

    <form method="get" class="row g-3 mb-4 border p-3 rounded shadow-sm bg-light">
        <div class="col-md-3">
            <input type="text" name="item" class="form-control" value="<?= esc($filterItem) ?>" placeholder="Filter by item name">
        </div>
        <div class="col-md-3">
            <input type="text" name="cashier" class="form-control" value="<?= esc($filterCashier) ?>" placeholder="Filter by cashier">
        </div>
        <div class="col-md-3">
       <input type="text" name="customer" class="form-control" value="<?= esc($filterCustomer) ?>" placeholder="Filter by customer"> <!-- ✅ new -->
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

    <?php if (!empty($salesOrders)): ?>
        <?php foreach ($salesOrders as $orderId => $orderItems): ?>
            <div class="card mb-4 shadow-sm">
           
                <div class="card-header bg-dark text-white d-flex justify-content-between align-items-center">
    <strong>Order #<?= esc($orderId) ?></strong>
    <div>
        <a href="<?= base_url('salesorder/receipt/' . $orderId) ?>" target="_blank" class="btn btn-sm btn-warning">
            🧾 Generate Receipt
        </a>
        <a href="<?= base_url('salesorder/orderPdf/' . $orderId) ?>" target="_blank" class="btn btn-sm btn-success">
            📄 Generate Order PDF
        </a>
    </div>
</div>


                <div class="card-body">
                    <!-- Customer Details -->
                    <?php $firstItem = reset($orderItems); ?>
                    <?php if (!empty($firstItem->customer_name)): ?>
                        <div class="mb-3 p-2 border rounded bg-light">
                            <h6 class="mb-1">👤 Customer Details</h6>
                            <p class="mb-0"><strong>Name:</strong> <?= esc($firstItem->customer_name) ?></p>
                            <p class="mb-0"><strong>Email:</strong> <?= esc($firstItem->customer_email) ?></p>
                            <p class="mb-0"><strong>Phone:</strong> <?= esc($firstItem->customer_phone) ?></p>
                            <p class="mb-0"><strong>Address:</strong> <?= esc($firstItem->customer_address) ?></p>
                        </div>
                    <?php else: ?>
                        <div class="mb-3 p-2 border rounded bg-light">
                            <em>No customer details linked.</em>
                        </div>
                    <?php endif; ?>
                </div>


                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-striped mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Item Name</th>
                                    <th>Quantity</th>
                                    <th>Price</th>
                                    <th>Cashier</th>
                                    <th>Date</th>
                                </tr>
                            </thead>


                            <tbody>
      <?php
          $totalItems = 0;
          $totalPrice = 0;
      ?>
      <?php foreach ($orderItems as $item): ?>
          <?php
              $totalItems += $item->quantity;
              $totalPrice += ($item->quantity * $item->sellingPrice);
          ?>
          <tr>
              <td><?= esc($item->item_name) ?></td>
              <td><?= esc($item->quantity) ?></td>
              <td>Kshs <?= number_format($item->sellingPrice, 2) ?></td>
              <td><?= esc($item->cashier) ?></td>

              <?php
    $dt = new DateTime($item->created_at, new DateTimeZone('UTC')); // assuming DB is UTC
    $dt->setTimezone(new DateTimeZone('Africa/Nairobi'));
?>
<td><?= $dt->format('Y-m-d H:i') ?></td>
          </tr>
      <?php endforeach; ?>
  </tbody>
  <tfoot class="table-light">
      <tr>


          <th colspan="1">TOTAL</th>
          <th><?= $totalItems ?> items</th>
          <th colspan="3">Kshs <?= number_format($totalPrice, 2) ?></th>
      </tr>
  </tfoot>

                        </table>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>

        <?php if (isset($pager)): ?>
            <div class="d-flex justify-content-center">
                <?= $pager->links() ?>
            </div>
        <?php endif; ?>
    <?php else: ?>
        <div class="alert alert-warning">No sales orders found.</div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>
