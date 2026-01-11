<?= $this->extend('layout') ?>
<?= $this->section('content') ?>
<div class="container mt-4">
    <h2>Create Numbering Setting</h2>

    <form method="post" action="/numbering_settings/store">
        <div class="mb-3">
            <label>Name</label>
            <input type="text" name="name" value="" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Apply To</label>
            <select name="apply_to" class="form-control" required>
                <option value="Category">Category</option>
                <option value="Items">Items</option>
            </select>
        </div>
        <div class="mb-3">
            <label>Prefix</label>
            <input type="text" name="prefix" value="" class="form-control">
        </div>
        <div class="mb-3">
            <label>Start</label>
            <input type="number" name="start" value="" class="form-control">
        </div>
        <div class="mb-3">
            <label>Last Used</label>
            <input type="number" name="last_used" value="" class="form-control">
        </div>
        <div class="mb-3">
            <label><input type="checkbox" name="auto_increment" value="1"> Auto Increment</label>
        </div>
        <div class="mb-3">
            <label><input type="checkbox" name="allow_manual_entry" value="1"> Allow Manual Entry</label>
        </div>
        <button type="submit" class="btn btn-primary">Create</button>
    </form>
</div>
<?= $this->endSection() ?>
