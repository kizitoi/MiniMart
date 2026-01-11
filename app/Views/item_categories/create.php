<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Add Item Category</h2>

    <form action="<?= site_url('/item_categories/store') ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="category_name" class="form-label">Category Name</label>
            <input type="text" name="category_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="shop_id" class="form-label">Select Shop</label>
            <select name="shop_id" class="form-select" required>
                <option value="">-- Select Shop --</option>
                <?php foreach ($shops as $shop): ?>
                    <option value="<?= esc($shop['id']) ?>"><?= esc($shop['name']) ?></option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="category_image" class="form-label">Category Image</label>
            <input type="file" name="category_image" class="form-control" accept="image/*">
        </div>

        <button type="submit" class="btn btn-primary">Save</button>
        <a href="<?= site_url('item_categories') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection() ?>
