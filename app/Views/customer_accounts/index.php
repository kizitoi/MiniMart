<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
  <div class="card shadow-sm border-0">
    <div class="card-body">
      <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="text-primary mb-0">
      📟 Account for <?= esc($customer['name']) ?>
      <?php if ($total_credit_balance < 0): ?>
        <small class="text-danger fw-bold ms-3">(Unpaid Credit: <?= number_format($total_credit_balance, 2) ?> KES)</small>
      <?php else: ?>
        <small class="text-success fw-bold ms-3">(No Pending Credit)</small>
      <?php endif; ?>
    </h2>

      <a href="https://nairobimetaldetectors.net/index.php/customers" class="btn btn-sm btn-danger" title="Close & Return">
          <i class="fas fa-times"></i> Close
      </a>
  </div>

      <!-- Sales Credit to Clear -->
      <h4 class="text-warning mb-3">🛮 Sales Credit to Clear</h4>
      <form method="post" action="<?= site_url("customer_accounts/markPaid") ?>" class="mb-5" onsubmit="return confirmMarkPaid()">
        <?= csrf_field() ?>
        <?php if (empty($credits)): ?>
          <div class="alert alert-info">No unpaid credit sales found for this customer.</div>
        <?php else: ?>
          <div class="list-group">
            <?php foreach($credits as $c): ?>
              <label class="list-group-item d-flex justify-content-between align-items-center">
                <div>
                  <input class="form-check-input me-1" type="checkbox" name="paid[]" value="<?= $c->id ?>">
                  <?= number_format($c->balance, 2) ?> KES — <?= date('d M Y', strtotime($c->created_at)) ?>
                </div>
                <span class="badge bg-<?= $c->credit_paid ? 'success' : 'danger' ?>">
                  <?= $c->credit_paid ? 'Paid' : 'Unpaid' ?>
                </span>
              </label>
            <?php endforeach; ?>
          </div>
          <button type="submit" class="btn btn-outline-primary mt-3">✅ Mark Selected as Paid</button>
          <p>When customer pays their Credit due, select and mark above all paid dues after recording the payment below and generating a receipt.</p>
        <?php endif; ?>
      </form>

      <script>
        function confirmMarkPaid() {
          const checked = document.querySelectorAll('input[name="paid[]"]:checked');
          if (checked.length === 0) {
            alert('Please select at least one unpaid credit to mark as paid.');
            return false;
          }
          return confirm('Are you sure you want to mark the selected credit sales as PAID?');
        }
      </script>


      <!-- New Transaction Form -->
      <h4 class="text-success mb-3">💳 Add New Transaction</h4>

    <p>  Step 1. Issue Credit equivalent to the amount received from customer.</p>
    <p>  Step 2. Issue Debit equivalent to the amount being cleared.</p>
    <p>  A settled account must have a balance of 0.</p>

      <form method="post" action="<?= site_url("customer_accounts/add/{$customer['customer_id']}") ?>" target="_blank" class="row g-3 mb-5">
        <?= csrf_field() ?>

        <div class="col-md-4">
          <label class="form-label">Transaction Type</label>
          <select name="type" class="form-select" required>
            <option value="">-- Select Type --</option>
            <option value="credit">Credit</option>
            <option value="debit">Debit</option>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Description</label>
          <select name="description_id" class="form-select" required>
            <option value="">-- Select Description --</option>
            <?php foreach($payment_descriptions as $pd): ?>
              <option value="<?= $pd['id'] ?>"><?= esc($pd['description']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Payment Method</label>
          <select name="payment_method_id" class="form-select" required>
            <option value="">-- Select Method --</option>
            <?php foreach($payment_methods as $pm): ?>
              <option value="<?= $pm['id'] ?>"><?= esc($pm['method']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>

        <div class="col-md-4">
          <label class="form-label">Amount (KES)</label>
          <input type="number" step="0.01" name="amount" class="form-control" placeholder="e.g. 500.00" required>
        </div>

        <div class="col-12 mt-2">
          <button type="submit" class="btn btn-success">📸 Submit & Print Receipt</button>
        </div>
      </form>

      <!-- Account Transactions Table -->
      <h4 class="mb-3">📜 Account Transaction History</h4>

    <!--  <div class="mb-3">
        <a href="<?//= site_url('customer_accounts/exportStatement/' . $customer['customer_id']) ?>" target="_blank" class="btn btn-outline-dark">🔷 Export Full Account Statement (PDF)</a>
      </div>-->

      <!-- Export Statement -->
  <!--  <h4 class="mb-3">🧾 Export Account Statement</h4>-->
    <form method="get" action="<?= site_url("customer_accounts/exportStatement/{$customer['customer_id']}") ?>" target="_blank" class="row g-3 mb-4">
      <div class="col-md-4">
        <label class="form-label">Start Date</label>
        <input type="date" name="start" class="form-control" required>
      </div>
      <div class="col-md-4">
        <label class="form-label">End Date</label>
        <input type="date" name="end" class="form-control" required>
      </div>
      <div class="col-md-4 d-flex align-items-end">
        <button type="submit" class="btn btn-outline-secondary w-100">📄 Export Statement</button>
      </div>
    </form>



    <div class="table-responsive mb-3">
  <table class="table table-bordered table-hover align-middle text-center">
    <thead class="table-dark">
      <tr>
        <th>Date</th>
        <th>Type</th>
        <th>Description</th>
        <th>Method</th>
        <th>Amount (KES)</th>
        <th>Balance (KES)</th>
      </tr>
    </thead>
    <tbody>
      <?php if (empty($entries)): ?>
        <tr><td colspan="6" class="text-muted">No transactions found.</td></tr>
      <?php else: ?>
        <?php foreach($entries as $e): ?>
          <tr>
            <td><?= date('d M Y, H:i', strtotime($e['created_at'])) ?></td>
            <td>
              <span class="badge bg-<?= $e['type'] === 'credit' ? 'success' : 'danger' ?>">
                <?= ucfirst($e['type']) ?>
              </span>
            </td>
            <td><?= esc($e['description'] ?? '-') ?></td>
            <td><?= esc($e['method'] ?? '-') ?></td>
            <td><?= number_format($e['type'] === 'debit' ? -1 * $e['amount'] : $e['amount'], 2) ?></td>
            <td><?= number_format($e['balance'], 2) ?></td>
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>
</div>

      <!-- Pagination -->
    <?php if (isset($pager)) : ?>
      <div class="mt-4">
        <?= $pager->links('default', 'short_pagination') ?>
      </div>
    <?php endif; ?>

    </div>
  </div>
</div>

<?= $this->endSection() ?>
