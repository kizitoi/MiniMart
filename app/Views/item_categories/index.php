<?= $this->extend('layout') ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2>Item Categories</h2>

    <div class="mb-3">
      <div class="mb-3">
      <form method="get" action="<?= site_url('item_categories') ?>" class="row g-2 align-items-end">
          <div class="col-auto">
              <label for="shop_id" class="form-label">Filter by Shop:</label>
              <select name="shop_id" id="shop_id" class="form-select">
                  <option value="">-- All Shops --</option>
                  <?php foreach ($shops as $shop): ?>
                      <option value="<?= esc($shop['id']) ?>" <?= ($selected_shop == $shop['id']) ? 'selected' : '' ?>>
                          <?= esc($shop['name']) ?>
                      </option>
                  <?php endforeach ?>
              </select>
          </div>
          <div class="col-auto">
              <label for="search" class="form-label">Search Category:</label>
              <input type="text" name="search" class="form-control" value="<?= esc($search) ?>" placeholder="Enter category name">
          </div>
          <div class="col-auto">
              <button type="submit" class="btn btn-primary">Apply</button>
              <a href="<?= site_url('item_categories') ?>" class="btn btn-secondary">Reset</a>
          </div>
          <div class="col-auto">
            <a href="<?= site_url('item_categories/export/pdf') ?>" class="btn btn-danger">Export PDF</a>
            <a href="<?= site_url('item_categories/export/word') ?>" class="btn btn-info text-white">Export Word</a>
            <a href="<?= site_url('item_categories/export/excel') ?>" class="btn btn-success">Export Excel</a>
          </div>
      </form>
  </div>

    </div>

    <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif ?>

    <table class="table table-bordered table-striped align-middle">
        <thead>
            <tr>
                <th>ID</th>
                <th>Image</th>
                <th>Category Name</th>
                <th>Shop</th>
                <th>Item Count</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
        <?php if (!empty($categories)): ?>
            <?php foreach ($categories as $category): ?>
                <tr>
                    <td><?= esc($category['id']) ?></td>
                    <td>
                        <?php if (!empty($category['category_image'])): ?>
                            <img src="<?= base_url('uploads/category_images/' . $category['category_image']) ?>"
                                 alt="Category Image"
                                 class="img-thumbnail"
                                 style="width: 60px; height: 60px; object-fit: cover;">
                        <?php else: ?>
                            <span class="text-muted">No image</span>
                        <?php endif; ?>
                    </td>
                    <td><?= esc($category['category_name']) ?></td>
                    <td><?= esc($category['shop_name']) ?></td>
                    <td><?= esc($category['item_count']) ?></td>
                    <td>
                        <a href="<?= site_url('item_categories/edit/' . $category['id']) ?>" class="btn btn-sm btn-warning">Edit</a>
                        <a href="<?= site_url('item_categories/delete/' . $category['id']) ?>" class="btn btn-sm btn-danger" onclick="return confirm('Delete this category?')">Delete</a>
                    </td>
                </tr>
            <?php endforeach ?>
        <?php else: ?>
            <tr>
                <td colspan="5">No item categories found.</td>
            </tr>
        <?php endif ?>
        </tbody>
    </table>

    <a href="<?= site_url('item_categories/create') ?>" class="btn btn-primary">Add New Category</a>
</div>

<?= $this->endSection() ?>
