<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Edit Numbering Setting</h2>

    <form method="post" action="/numbering_settings/update/<?= $numbering['id'] ?>">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="<?= esc($numbering['name']) ?>" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Apply To</label>
            <select name="apply_to" class="form-control" required>
                <option value="Category" <?= ($numbering['apply_to'] == 'Category') ? 'selected' : '' ?>>Category</option>
                <option value="Items" <?= ($numbering['apply_to'] == 'Items') ? 'selected' : '' ?>>Items</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Prefix</label>
            <input type="text" name="prefix" value="<?= esc($numbering['prefix']) ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label>Start</label>
            <input type="number" name="start" value="<?= esc($numbering['start']) ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label>Last Used</label>
            <input type="number" name="last_used" value="<?= esc($numbering['last_used']) ?>" class="form-control">
        </div>
        <div class="mb-3">
            <label><input type="checkbox" name="auto_increment" value="1" <?= ($numbering['auto_increment']) ? 'checked' : '' ?>> Auto Increment</label>
        </div>
        <div class="mb-3">
            <label><input type="checkbox" name="allow_manual_entry" value="1" <?= ($numbering['allow_manual_entry']) ? 'checked' : '' ?>> Allow Manual Entry</label>
        </div>
        <button type="submit" class="btn btn-primary">Update</button>
    </form>
</div>
<?= $this->endSection() ?>
