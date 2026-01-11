<style>
    body { font-family: Arial, sans-serif; font-size: 12px; }
    h2 { border-bottom: 1px solid #ccc; }
    .section { margin-bottom: 20px; }
    .logo { float: right; width: 100px; }
</style>

<h2>Incident Report</h2>

<div class="section">
    <strong>To:</strong><br>
    <?= esc($customer['name']) ?><br>
    <?= esc($customer['phone']) ?><br>
    <?= esc($customer['email']) ?>
</div>

<div class="section">
    <strong>Company:</strong><br>
    <?= esc($company['name']) ?><br>
    <?= esc($company['phone']) ?><br>
    <img class="logo" src="<?= base_url($company['logo']) ?>">
</div>

<div class="section">
    <strong>Incident:</strong><br>
    Date: <?= esc($incident['incident_date']) ?><br>
    Time: <?= esc($incident['time_hour']) ?> :  <?= esc($incident['time_minute']) ?><br>
    Location: <?= esc($incident['location']) ?><br>
</div>

<div class="section"><strong>Findings:</strong><br>
    <ul>
        <?php foreach ($findings as $f): ?>
            <li><?= esc($f['description']) ?></li>
        <?php endforeach; ?>
    </ul>
</div>

<!-- Repeat for observations, actions, etc. -->

<div class="section"><strong>Pictures:</strong><br>
    <?php foreach ($pictures as $pic): ?>
        <img src="<?= base_url($pic['image_path']) ?>" width="150">
    <?php endforeach; ?>
</div>
