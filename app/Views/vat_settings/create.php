<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container">
    <h2 class="my-4">Add VAT Setting</h2>

    <form method="post" action="<?= site_url('vat_settings/store') ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="vat_name" class="form-label">VAT Name</label>
            <input type="text" name="vat_name" id="vat_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="vat_perc" class="form-label">VAT Percentage</label>
            <input type="number" step="0.01" name="vat_perc" id="vat_perc" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="vat_code" class="form-label">VAT Code</label>
            <input type="text" name="vat_code" id="vat_code" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Save VAT</button>
        <a href="<?= site_url('vat_settings') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection() ?>
