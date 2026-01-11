<!DOCTYPE html>
<html>
<head>
  <style>
    body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
    th, td { border: 1px solid #333; padding: 8px; text-align: center; }
    th { background-color: #f2f2f2; }
    footer { position: fixed; bottom: 0; width: 100%; text-align: center; font-size: 10px; }
    .header { text-align: center; margin-bottom: 10px; }
  </style>
</head>
<body>

  <div class="header">
    <img src="https://nairobimetaldetectors.net/logo.jpg" alt="Company Logo">
    <h2><?= esc($company->name) ?></h2>
    <p><?= esc($company->address ?? '') ?> | Phone: <?= esc($company->phone ?? '') ?></p>
    <h3>Account Statement for <?= esc($customer->name) ?></h3>
    <p>Period: <?= date('d M Y', strtotime($start)) ?> to <?= date('d M Y', strtotime($end)) ?></p>
  </div>

  <table>
    <thead>
      <tr>
        <th>Date</th>
        <th>Type</th>
        <th>Description</th>
        <th>Method</th>
        <th>Amount</th>
        <th>Balance</th>
      <!--  <th>By</th>-->
      </tr>
    </thead>
    <tbody>
      <?php if (empty($entries)): ?>
        <tr><td colspan="7">No transactions found in this period.</td></tr>
      <?php else: ?>
        <?php foreach ($entries as $e): ?>
          <tr>
            <td><?= date('d M Y H:i', strtotime($e->created_at)) ?></td>
            <td><?= ucfirst($e->type) ?></td>
            <td><?= esc($e->description) ?></td>
            <td><?= esc($e->method) ?></td>
          <!--  <td><?//= number_format($e->amount, 2) ?></td>-->

            <td style="color: <?= $e->type === 'debit' ? 'red' : 'black' ?>;">
                <?= number_format($e->type === 'debit' ? -1 * $e->amount : $e->amount, 2) ?>
            </td>


            <td><?= number_format($e->balance, 2) ?></td>
          <!--  <td><?//= esc($e->username ?? 'System') ?></td>-->
          </tr>
        <?php endforeach; ?>
      <?php endif; ?>
    </tbody>
  </table>

  <footer>
    Page {PAGE_NUM} of {PAGE_COUNT}
  </footer>

</body>
</html>
