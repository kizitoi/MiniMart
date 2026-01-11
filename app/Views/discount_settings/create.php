<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>➕ Add Discount Setting</h2>
    <form method="post" action="<?= site_url('discount_settings/store') ?>" class="row g-3 mt-3">
        <div class="col-md-6">
            <label>Discount Name</label>
            <input type="text" name="discount_name" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label>Discount Amount</label>
            <input type="number" step="0.01" name="discount_amount" class="form-control" required>
        </div>
        <div class="col-md-3">
            <label>Enabled</label><br>
            <input type="checkbox" name="enabled" value="1" checked> Active
        </div>
        <div class="col-md-6">
            <label>Minimum Shopping Amount</label>
            <input type="number" step="0.01" name="min_shopping_amount" class="form-control">
        </div>
        <div class="col-md-6">
            <label>Maximum Shopping Amount</label>
            <input type="number" step="0.01" name="maximum_shopping_amount" class="form-control">
        </div>
        <div class="col-12">
            <button class="btn btn-success">💾 Save</button>
            <a href="<?= site_url('discount_settings') ?>" class="btn btn-secondary">🔙 Back</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>
