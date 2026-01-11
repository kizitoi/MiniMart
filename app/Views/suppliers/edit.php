<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container">
    <h2 class="my-4">Edit Supplier</h2>

    <form method="post" action="<?= site_url('suppliers/update/' . $supplier['id']) ?>">
        <?= csrf_field() ?>
        <div class="mb-3">
            <label class="form-label">Name</label>
            <input type="text" name="name" class="form-control" value="<?= esc($supplier['name']) ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Phone</label>
            <input type="text" name="phone" class="form-control" value="<?= esc($supplier['phone']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Email</label>
            <input type="email" name="email" class="form-control" value="<?= esc($supplier['email']) ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Address</label>
            <textarea name="address" class="form-control"><?= esc($supplier['address']) ?></textarea>
        </div>
        <div class="mb-3">
            <label class="form-label">Town</label>
            <select name="town" class="form-control" required>
                <?php foreach ($towns as $town): ?>
                    <option value="<?= esc($town['name']) ?>" <?= $supplier['town'] == $town['name'] ? 'selected' : '' ?>>
                        <?= esc($town['name']) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= site_url('suppliers') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection() ?>
