<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <h2 class="my-4">New Item Check-In</h2>

    <form method="post" action="<?= site_url('item_check_in/store') ?>">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="item_id" class="form-label">Item</label>
            <select name="item_id" class="form-control" required>
                <?php foreach ($items as $item): ?>
                    <option value="<?= $item['id'] ?>"><?= esc($item['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="supplier_id" class="form-label">Supplier</label>
            <select name="supplier_id" class="form-control" required>
                <?php foreach ($suppliers as $supplier): ?>
                    <option value="<?= $supplier['id'] ?>"><?= esc($supplier['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="check_in_quantity" class="form-label">Quantity</label>
            <input type="number" name="check_in_quantity" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="unit_price" class="form-label">Unit Price</label>
            <input type="number" step="0.01" name="unit_price" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="date" class="form-label">Date</label>
            <input type="date" name="date" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="time" class="form-label">Time</label>
            <input type="time" name="time" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-success">Check In</button>
        <a href="<?= site_url('item_check_in') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection() ?>
