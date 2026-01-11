<!DOCTYPE html>
<html>
<head>
    <title>Incident Report</title>
    <style>
        body { font-family: Arial; }
        .header { display: flex; justify-content: space-between; }
        .section { margin-top: 20px; }
        .pictures img { width: 200px; height: auto; margin: 10px; }
    </style>
</head>
<body>

<div class="header">
    <div>
        <h3>Customer Info</h3>
        <p><strong>Name:</strong> <?= esc($customer['name']) ?></p>
        <p><strong>Contact:</strong> <?= esc($customer['phone']) ?>  <?= esc($customer['email']) ?></p>
        <p><strong>Address:</strong> <?= esc($customer['address']) ?></p>
    </div>
    <div>
        <h3>Company Info</h3>
        <p><strong>Name:</strong> <?= esc($company['name']) ?></p>
        <p><strong>Email:</strong> <?= esc($company['email']) ?></p>
        <p><strong>Phone:</strong> <?= esc($company['phone']) ?></p>
        <?php if ($company['logo']): ?>
            <img src="<?= base_url('uploads/logos/' . $company['logo']) ?>" width="100">
        <?php endif; ?>
    </div>
</div>

<hr>
<div class="section">
    <h3>Incident Details</h3>
    <p><strong>Ref No:</strong> <?= esc($incident['reference_no']) ?></p>
    <p><strong>Case File:</strong> <?= esc($incident['case_file_no']) ?></p>
    <p><strong>Date:</strong> <?= esc($incident['incident_date']) ?>   <?= str_pad(esc($incident['time_hour']), 2, '0', STR_PAD_LEFT) ?>    <?= str_pad(esc($incident['time_minute']), 2, '0', STR_PAD_LEFT) ?> </p>
    <p><strong>Officer:</strong> <?= esc($incident['officer_name']) ?></p>
</div>




<div class="section">
    <h3>Findings</h3>
    <ul>
        <?php foreach ($findings as $f): ?>
            <li><?= esc($f['description']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="section">
    <h3>Observations</h3>
    <ul>
        <?php foreach ($observations as $o): ?>
            <li><?= esc($o['description']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="section">
    <h3>Actions Taken</h3>
    <ul>
        <?php foreach ($actions as $a): ?>
            <li><strong><?= esc($a['action_date']) ?> <?= esc($a['action_time']) ?>:</strong> <?= esc($a['description']) ?> (<?= esc($a['officer_involved']) ?>)</li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="section">
    <h3>Conclusions & Recommendations</h3>
    <ul>
        <?php foreach ($conclusions as $c): ?>
            <li><?= esc($c['conclusion']) ?> <br><em><?= esc($c['recommendation']) ?></em></li>
        <?php endforeach; ?>
    </ul>
</div>

<div class="section pictures">
    <h3>Incident Pictures</h3>
    <?php foreach ($pictures as $p): ?>
        <div>
          <!--  <img src="<?//= base_url('uploads/incidents/' . $p['image_path']) ?>" alt="Pic">-->
            <img src="<?= base_url('uploads/incidents/' . $p['image_path']) ?>" width="200">

            <p><strong><?= esc($p['description']) ?></strong></p>
        </div>
    <?php endforeach; ?>
</div>

</body>
</html>
