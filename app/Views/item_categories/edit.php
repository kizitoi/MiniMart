<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Edit Item Category</h2>

    <form action="<?= site_url('/item_categories/update/' . $category['id']) ?>" method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="mb-3">
            <label for="category_name" class="form-label">Category Name</label>
            <input type="text" name="category_name" class="form-control" value="<?= esc($category['category_name']) ?>" required>
        </div>

        <div class="mb-3">
            <label for="shop_id" class="form-label">Select Shop</label>
            <select name="shop_id" class="form-select" required>
                <?php foreach ($shops as $shop): ?>
                    <option value="<?= esc($shop['id']) ?>" <?= ($shop['id'] == $category['shop_id']) ? 'selected' : '' ?>>
                        <?= esc($shop['name']) ?>
                    </option>
                <?php endforeach ?>
            </select>
        </div>

        <div class="mb-3">
            <label for="category_image" class="form-label">Category Image</label>
            <input type="file" name="category_image" class="form-control" accept="image/*">
            <?php if (!empty($category['category_image'])): ?>
                <div class="mt-2">
                    <img src="<?= base_url('uploads/category_images/' . $category['category_image']) ?>" style="max-width: 150px;">
                </div>
            <?php endif; ?>
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="<?= site_url('item_categories') ?>" class="btn btn-secondary">Cancel</a>
    </form>
</div>

<?= $this->endSection() ?>
