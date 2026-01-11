<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <h2 class="my-4">Edit Unit of Measure</h2>

    <form method="post" action="<?= site_url('unit_of_measure/update/' . $unit['id']) ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label for="unit_name" class="form-label">Unit Name</label>
            <input type="text" name="unit_name" id="unit_name" class="form-control" value="<?= esc($unit['unit_name']) ?>" required>
        </div>

        <button type="submit" class="btn btn-primary">Update Unit</button>
        <a href="<?= site_url('unit_of_measure') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection() ?>
