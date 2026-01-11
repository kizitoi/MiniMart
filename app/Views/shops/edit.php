<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Edit Shop</h2>

    <form method="post" action="<?= site_url('shops/update/' . $shop['id']) ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="name" class="form-label">Shop Name</label>
            <input type="text" name="name" id="name" class="form-control" value="<?= esc($shop['name']) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Shop</button>
        <a href="<?= site_url('shops') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection() ?>
