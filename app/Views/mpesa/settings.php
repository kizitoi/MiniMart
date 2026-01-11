<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h3 class="text-primary mb-4">🔧 M-Pesa Integration Settings</h3>

            <?php if (session()->getFlashdata('message')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('message') ?></div>
            <?php endif; ?>

            <?php if ($settings): ?>
            <table class="table table-bordered table-striped">
                <tr><th>Paybill</th><td><?= esc($settings['paybill']) ?></td></tr>
                <tr><th>Till Number</th><td><?= esc($settings['till_number']) ?></td></tr>
                <tr><th>Shortcode</th><td><?= esc($settings['shortcode']) ?></td></tr>
                <tr><th>Consumer Key</th><td><?= esc($settings['consumer_key']) ?></td></tr>
                <tr><th>Consumer Secret</th><td><?= esc($settings['consumer_secret']) ?></td></tr>
                <tr><th>Passkey</th><td><?= esc($settings['passkey']) ?></td></tr>
                <tr><th>Active</th><td><?= $settings['active'] ? '✅ Yes' : '❌ No' ?></td></tr>
            </table>
            <a href="<?= site_url('mpesa_settings/edit/' . $settings['id']) ?>" class="btn btn-primary mt-3">✏️ Edit Settings</a>
            <?php else: ?>
                <div class="alert alert-warning">No M-Pesa settings found. Please configure.</div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
