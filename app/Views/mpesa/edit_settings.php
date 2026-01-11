<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-5">
    <div class="card shadow-sm border-0">
        <div class="card-body">
            <h3 class="text-primary mb-4">✏️ Edit M-Pesa Settings</h3>

            <form action="<?= site_url('mpesa_settings/update/' . $settings['id']) ?>" method="post">
                <?= csrf_field() ?>
                <div class="mb-3">
                    <label for="paybill" class="form-label">Paybill</label>
                    <input type="text" name="paybill" class="form-control" value="<?= esc($settings['paybill']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="till_number" class="form-label">Till Number</label>
                    <input type="text" name="till_number" class="form-control" value="<?= esc($settings['till_number']) ?>">
                </div>
                <div class="mb-3">
                    <label for="shortcode" class="form-label">Shortcode</label>
                    <input type="text" name="shortcode" class="form-control" value="<?= esc($settings['shortcode']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="consumer_key" class="form-label">Consumer Key</label>
                    <input type="text" name="consumer_key" class="form-control" value="<?= esc($settings['consumer_key']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="consumer_secret" class="form-label">Consumer Secret</label>
                    <input type="text" name="consumer_secret" class="form-control" value="<?= esc($settings['consumer_secret']) ?>" required>
                </div>
                <div class="mb-3">
                    <label for="passkey" class="form-label">Passkey</label>
                    <input type="text" name="passkey" class="form-control" value="<?= esc($settings['passkey']) ?>" required>
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" name="active" value="1" <?= $settings['active'] ? 'checked' : '' ?>>
                    <label class="form-check-label">Activate M-Pesa</label>
                </div>
                <button type="submit" class="btn btn-success">💾 Save Changes</button>
                <a href="<?= site_url('mpesa_settings') ?>" class="btn btn-secondary">🔙 Cancel</a>
            </form>
        </div>
    </div>
</div>

<?= $this->endSection() ?>
