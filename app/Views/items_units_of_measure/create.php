<?= $this->extend('layout') ?>

<?= $this->section('content') ?>

<div class="container">
    <h2 class="my-4">Add Unit of Measure</h2>

    <form method="post" action="<?= site_url('items_units_of_measure/store') ?>">
        <?= csrf_field() ?>
        <input type="hidden" name="item_id" value="<?= esc($itemId) ?>">

        <div class="mb-3">
            <label for="unit_id" class="form-label">Unit</label>
            <select name="unit_id" id="unit_id" class="form-control" required>
                <option value="">Select Unit</option>
                <?php foreach ($units as $unit): ?>
                    <option value="<?= $unit['id'] ?>"><?= esc($unit['unit_name']) ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="unit_value" class="form-label">Unit Value</label>
            <input type="text" name="unit_value" id="unit_value" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?= site_url('items_units_of_measure/' . $itemId) ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection() ?>
