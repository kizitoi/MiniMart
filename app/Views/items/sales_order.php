<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="card shadow border-0">
        <div class="card-body">
            <h3 class="text-primary mb-4">🧾 Current Order</h3>

            <?php if (!$order): ?>
                <div class="alert alert-info">No active order found.</div>
            <?php else: ?>
                <div class="table-responsive mb-4">
                    <table class="table table-striped table-hover table-bordered text-center align-middle">
                        <thead class="table-dark">
                            <tr>
                                <th>Item</th>
                                <th>VAT Code</th>
                                <th>@Price (KES)</th>
                                <th>Qty</th>
                                <th>Total (KES)</th>
                                <th>🗑 Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($items as $item): ?>
                            <tr>
                                <td><?= esc($item->item_name) ?></td>
                                <td><?= esc($item->vat_code) ?></td>
                                <td><?= number_format($item->sellingPrice, 2) ?></td>
                                <td><?= esc($item->quantity) ?></td>
                                <td><?= number_format($item->line_total, 2) ?></td>
                                <td>
                                    <form action="<?= site_url('salesorder/remove_item') ?>" method="post">
                                        <?= csrf_field() ?>
                                        <input type="hidden" name="item_id" value="<?= esc($item->id) ?>">
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach ?>
                        </tbody>
                        <tfoot class="fw-bold">
                            <tr class="table-light">
                                <td colspan="4" class="text-end">Total VAT:</td>
                                <td colspan="2"><?= number_format($totalVAT, 2) ?></td>
                            </tr>
                            <tr class="table-secondary">
                                <td colspan="4" class="text-end">Subtotal:</td>
                                <td colspan="2"><?= number_format($grandTotal, 2) ?></td>
                            </tr>
                            <?php if (!empty($appliedDiscount)): ?>
                            <tr class="table-info">
                                <td colspan="4" class="text-end">Discount (<?= esc($appliedDiscount['discount_name']) ?>):</td>
                                <td colspan="2">-<?= number_format($appliedDiscount['discount_amount'], 2) ?></td>
                            </tr>
                            <tr class="table-success">
                                <td colspan="4" class="text-end">Total After Discount:</td>
                                <td colspan="2"><?= number_format($grandTotal - $appliedDiscount['discount_amount'], 2) ?></td>
                            </tr>
                            <?php endif ?>
                        </tfoot>
                    </table>
                </div>

                <form method="post" action="<?= site_url('salesorder/close') ?>">
                    <?= csrf_field() ?>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">💰 Amount Paid (KES)</label>
                            <input type="number" step="0.01" name="amount_paid" id="amount_paid" class="form-control form-control-lg" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">🧲 Change (KES)</label>
                            <input type="text" name="change" id="change" class="form-control form-control-lg bg-light" readonly>
                        </div>
                    </div>

                    <div class="row g-3 mb-3">
                        <div class="col-md-6">
                            <label class="form-label">💳 Payment Method</label>
                            <select id="payment_method" class="form-select form-select-lg" required>
                                <option value="">-- Select Payment Method --</option>
                                <option value="Cash">Cash</option>
                                <option value="Mobile Money">Mobile Money</option>
                                <option value="Mpesa">Mpesa</option>
                                <option value="Card">Card</option>
                                <option value="Credit" id="credit_option">Credit</option>
                                <option value="Bank Transfer">Bank Transfer</option>
                            </select>
                            <input type="hidden" name="payment_method" id="forced_payment_method">
                        </div>

                        <div class="col-md-6">
                            <label class="form-label">👤 Select Customer (optional)</label>
                            <select name="customer_id" class="form-select form-select-lg">
                                <option value="">-- Optional: Select Customer --</option>
                                <?php foreach ($customers as $customer): ?>
                                    <option value="<?= esc($customer['customer_id']) ?>">
                                        <?= esc($customer['name']) ?> (<?= esc($customer['phone']) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div id="mpesa_fields" class="row g-3 mt-2" style="display: none;">
                        <div class="col-md-6">
                            <label class="form-label">📱 Mobile Number</label>
                            <input type="text" id="mpesa_phone" name="mpesa_phone" class="form-control form-control-lg" placeholder="e.g. 254712345678">
                        </div>
                        <div class="col-md-6 d-flex align-items-end">
                            <button type="button" id="stk_btn" class="btn btn-primary btn-lg w-100">
                                🚀 Send STK Push
                            </button>
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-4">
                        <a href="<?= site_url('items/express_sale') ?>" class="btn btn-outline-secondary btn-lg">
                            ⬅ Exit
                        </a>

                        <button type="submit" id="closeOrderBtn" class="btn btn-success btn-lg">
                            ✅ Pay & Close Order
                        </button>
                    </div>
                </form>
            <?php endif ?>
        </div>
    </div>
</div>

<script>
function updatePaymentBehavior() {
    const paid = parseFloat(document.getElementById('amount_paid').value) || 0;
    const total = <?= isset($grandTotal) ? (isset($appliedDiscount) ? ($grandTotal - $appliedDiscount['discount_amount']) : $grandTotal) : 0 ?>;
    const change = paid - total;

    const changeField = document.getElementById('change');
    const paymentSelect = document.getElementById('payment_method');
    const forcedPaymentInput = document.getElementById('forced_payment_method');
    const customerSelect = document.querySelector('select[name="customer_id"]');
    const submitBtn = document.getElementById('closeOrderBtn');

    changeField.value = change.toFixed(2);
    changeField.classList.toggle('text-danger', change !== 0);

    if (change < 0) {
        paymentSelect.value = 'Credit';
        paymentSelect.setAttribute('disabled', true);
        customerSelect.setAttribute('required', true);
        forcedPaymentInput.value = 'Credit';

        if (!customerSelect.value) {
            submitBtn.setAttribute('disabled', true);
        } else {
            submitBtn.removeAttribute('disabled');
        }
    } else {
        paymentSelect.removeAttribute('disabled');

        if (paymentSelect.value === 'Credit') {
            paymentSelect.value = '';
            forcedPaymentInput.value = '';
        } else {
            forcedPaymentInput.value = paymentSelect.value;
        }

        customerSelect.removeAttribute('required');
        submitBtn.removeAttribute('disabled');
        changeField.classList.remove('text-danger');
    }
}

// Initial binding
updatePaymentBehavior();
document.getElementById('amount_paid').addEventListener('input', updatePaymentBehavior);
document.querySelector('select[name="customer_id"]').addEventListener('change', updatePaymentBehavior);
document.getElementById('payment_method').addEventListener('change', function () {
    document.getElementById('forced_payment_method').value = this.value;
    const mpesaSection = document.getElementById('mpesa_fields');
    mpesaSection.style.display = (this.value === 'Mpesa') ? 'flex' : 'none';
});
</script>




<script>
/*document.getElementById('amount_paid').addEventListener('input', function () {
    const paid = parseFloat(this.value) || 0;
    const total = <?//= isset($grandTotal) ? (isset($appliedDiscount) ? ($grandTotal - $appliedDiscount['discount_amount']) : $grandTotal) : 0 ?>;
    const change = paid - total;
    document.getElementById('change').value = change >= 0 ? change.toFixed(2) : '0.00';
});*/

document.getElementById('payment_method').addEventListener('change', function () {
    const mpesaSection = document.getElementById('mpesa_fields');
    mpesaSection.style.display = (this.value === 'Mpesa') ? 'flex' : 'none';
});

document.getElementById('stk_btn').addEventListener('click', function () {
    const phone = document.getElementById('mpesa_phone').value;
    const amount = parseFloat(document.getElementById('amount_paid').value);

    if (!phone || !amount) {
        alert('Please enter both phone number and amount.');
        return;
    }

    fetch('<?= site_url("salesorder/close") ?>', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: new URLSearchParams({
            amount_paid: amount,
            payment_method: 'Mpesa',
            mpesa_phone: phone,
            '<?= csrf_token() ?>': '<?= csrf_hash() ?>'
        })
    }).then(response => {
    const modal = new bootstrap.Modal(document.getElementById('paymentStatusModal'));
    modal.show();

    // Simulate polling for payment status every 5 seconds
    const phone = document.getElementById('mpesa_phone').value;
    let attempts = 0;

    const pollPayment = setInterval(() => {
        fetch('<?= site_url("api/check_payment_status") ?>?phone=' + encodeURIComponent(phone))
            .then(res => res.json())
            .then(data => {
                if (data.status === 'success') {
                    clearInterval(pollPayment);

                    // Hide waiting, show success
                    document.getElementById('paymentStatusContent').style.display = 'none';
                    document.getElementById('paymentSuccessContent').style.display = 'block';

                    document.getElementById('paidPhone').textContent = data.phone;
                    document.getElementById('paidName').textContent = data.name;
                    document.getElementById('paidAmount').textContent = data.amount;
                }
            });

        attempts++;
        if (attempts >= 12) { // 1 minute timeout
            clearInterval(pollPayment);
            document.getElementById('paymentStatusContent').innerHTML = `<div class="alert alert-danger">❌ Payment not confirmed. Try again.</div>`;
        }
    }, 5000);
}).catch(err => {
        alert('Failed to initiate STK push');
    });
});
</script>


<!-- Payment Status Modal -->
<div class="modal fade" id="paymentStatusModal" tabindex="-1" aria-labelledby="paymentStatusLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content shadow">
      <div class="modal-header bg-primary text-white">
        <h5 class="modal-title" id="paymentStatusLabel">🔄 M-Pesa Payment Status</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body text-center">
        <div id="paymentStatusContent">
          <div class="spinner-border text-success mb-3" role="status"></div>
          <p class="mb-0">Waiting for payment confirmation...</p>
        </div>
        <div id="paymentSuccessContent" style="display: none;">
          <div class="alert alert-success">
            ✅ Payment Received!
          </div>
          <p><strong>Phone:</strong> <span id="paidPhone"></span></p>
          <p><strong>Name:</strong> <span id="paidName"></span></p>
          <p><strong>Amount:</strong> KES <span id="paidAmount"></span></p>
        </div>
      </div>
    </div>
  </div>
</div>





<?= $this->endSection() ?>
