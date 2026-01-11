<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h3 class="text-primary mb-4">📒 M-Pesa Transactions</h3>

            <?php if (!empty($transactions)): ?>
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-center">
                    <thead class="table-dark">
                        <tr>
                            <th>Date</th>
                            <th>Phone</th>
                            <th>Amount</th>
                            <th>Receipt No</th>
                            <th>Result</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($transactions as $t): ?>
                        <tr>
                            <td><?= date('d-M-Y H:i', strtotime($t['TransactionDate'])) ?></td>
                            <td><?= esc($t['PhoneNumber']) ?></td>
                            <td><strong>KES <?= number_format($t['Amount'], 2) ?></strong></td>
                            <td><?= esc($t['MpesaReceiptNumber']) ?></td>
                            <td><?= esc($t['ResultDesc']) ?></td>
                            <td>
                                <?php if ($t['ResultCode'] == 0): ?>
                                    <span class="badge bg-success">✅ Success</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">❌ Failed</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
                <div class="alert alert-info">No M-Pesa transactions recorded yet.</div>
            <?php endif ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
