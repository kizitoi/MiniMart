<?= $this->extend('layout') ?>

<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']!='items')
      { ?>

header('Location: <?= base_url('officer/officer') ?>, true, 301);
exit;


<?PHP } ?>
      <?= $this->section('content') ?>

      <div class="container py-4">
        <div class="d-flex justify-content-between align-items-center mb-4">
          <h2 class="mb-0">📦 Item Management</h2>
          <div>
            <a href="<?= site_url('items/create') ?>" class="btn btn-success">
              <i class="fa fa-plus"></i> Add Item
            </a>
          </div>
        </div>

        <!-- Alerts -->
        <?php if (session()->getFlashdata('success')): ?>
          <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
        <?php endif; ?>
        <?php if (session()->getFlashdata('error')): ?>
          <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
        <?php endif; ?>

        <!-- Filter Buttons -->
        <div class="mb-3 d-flex flex-wrap gap-2">
          <a href="<?= site_url('items') . '?reorder_only=1&filter=' . esc($filter ?? '') ?>"
             class="btn btn-outline-danger <?= isset($reorderOnly) && $reorderOnly ? 'active' : '' ?>">
            <i class="fa fa-exclamation-triangle"></i> Reorder Items
          </a>
          <?php if (isset($reorderOnly) && $reorderOnly): ?>
            <a href="<?= site_url('items') . '?filter=' . esc($filter ?? '') ?>" class="btn btn-secondary">
              Show All
            </a>
          <?php endif; ?>
        </div>

        <!-- Filter Form -->
        <form method="get" class="mb-4">
          <div class="row g-2">
            <div class="col-md-4">
              <input type="text" name="filter" class="form-control" placeholder="Search by item name..."
                     value="<?= esc($filter ?? '') ?>">
            </div>
            <div class="col-md-4">
        <select name="category_id" class="form-select" onchange="this.form.submit()">
          <option value="">-- Filter by Category --</option>
          <?php
            // Sort categories alphabetically by category_name
            usort($categories, function ($a, $b) {
              return strcmp($a['category_name'], $b['category_name']);
            });

            foreach ($categories as $cat): ?>
            <option value="<?= $cat['id'] ?>" <?= isset($categoryId) && $categoryId == $cat['id'] ? 'selected' : '' ?>>
              <?= esc($cat['category_name']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

            <div class="col-md-2">
              <button class="btn btn-primary w-100"><i class="fa fa-search"></i> Search</button>
            </div>
            <div class="col-md-2">
              <a href="<?= site_url('items') ?>" class="btn btn-secondary w-100">Reset</a>
            </div>
          </div>
        </form>

        <!-- Export & Barcode -->
        <div class="d-flex flex-wrap gap-2 mb-4">
          <a href="<?= site_url('items/export/pdf?filter=' . urlencode($filter ?? '')) ?>" class="btn btn-danger">
            <i class="fa fa-file-pdf-o"></i> PDF
          </a>
          <a href="<?= site_url('items/export/word?filter=' . urlencode($filter ?? '')) ?>" class="btn btn-primary">
            <i class="fa fa-file-word-o"></i> Word
          </a>
          <a href="<?= site_url('items/export/excel?filter=' . urlencode($filter ?? '')) ?>" class="btn btn-success">
            <i class="fa fa-file-excel-o"></i> Excel
          </a>
          <button type="button" class="btn btn-dark" data-bs-toggle="modal" data-bs-target="#barcodeModal">
            <i class="fa fa-barcode"></i> Generate Barcodes
          </button>
        </div>


        <!-- Barcode Modal -->
        <!-- Barcode Modal -->
<div class="modal fade" id="barcodeModal" tabindex="-1" aria-labelledby="barcodeModalLabel" aria-hidden="true">
<div class="modal-dialog modal-lg">
<form method="post" action="<?= site_url('items/generate_barcodes') ?>" target="_blank">
  <?= csrf_field() ?>
  <div class="modal-content">
    <div class="modal-header">
      <h5 class="modal-title">Generate Barcodes</h5>
      <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
    </div>
    <div class="modal-body">

      <!-- Barcode Content Type -->
      <div class="mb-3">
        <label class="form-label">Barcode Content</label>


        <div class="form-check">
          <input class="form-check-input" type="radio" name="barcode_type" value="item_no" id="barcodeItemNo" checked>
          <label class="form-check-label" for="barcodeItemNo">Item Number</label>
        </div>


      </div>

      <!-- Item Range Selection -->
      <div class="row mb-4">
        <div class="col-md-6">
          <label for="start_item">Start Item</label>
          <select class="form-select" name="start_item" id="start_item" required>
            <?php foreach ($items as $item): ?>
              <option value="<?= $item['id'] ?>"><?= $item['item_no'] ?> - <?= esc($item['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="col-md-6">
          <label for="end_item">End Item</label>
          <select class="form-select" name="end_item" id="end_item" required>
            <?php foreach ($items as $item): ?>
              <option value="<?= $item['id'] ?>"><?= $item['item_no'] ?> - <?= esc($item['name']) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
      </div>

      <!-- Layout Selection -->
      <div class="mb-3">
        <label for="layout_style">Label Layout Style</label>
        <select class="form-select" name="layout_style" id="layout_style" onchange="toggleCustomSizeFields(this.value)">
          <option value="grid_avery_3x10">Avery 5160 - 3x10 Grid</option>
          <option value="flow">Flow (One per row)</option>
          <option value="custom">Custom Size</option>
        </select>
      </div>

      <!-- Label Size Selection -->
      <div class="mb-3">
        <label for="label_size">Preset Label Size</label>
        <select class="form-select" name="label_size" id="label_size">
          <option value="small">Small (50×25mm)</option>
          <option value="medium">Medium (70×35mm)</option>
          <option value="large">Large (100×50mm)</option>
        </select>
      </div>

      <!-- Saved Custom Sizes -->
      <div class="mb-3">
        <label for="saved_size">Saved Label Sizes</label>
        <select class="form-select" name="saved_size" id="saved_size" onchange="applySavedSize(this)">
          <option value="">-- Select Saved Size --</option>
          <?php foreach ($saved_sizes as $size): ?>
            <option value="<?= $size['width_mm'] ?>x<?= $size['height_mm'] ?>">
              <?= esc($size['name']) ?> (<?= $size['width_mm'] ?>mm × <?= $size['height_mm'] ?>mm)
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <!-- Custom Size Fields -->
      <div class="row mb-4" id="customSizeFields" style="display: none;">
        <div class="col-md-6">
          <label for="custom_width">Label Width (mm)</label>
          <input type="number" name="custom_width" id="custom_width" class="form-control" min="20" step="1">
        </div>
        <div class="col-md-6">
          <label for="custom_height">Label Height (mm)</label>
          <input type="number" name="custom_height" id="custom_height" class="form-control" min="10" step="1">
        </div>
      </div>

      <!-- Save Custom Size -->
      <div class="border-top pt-3">
        <form method="post" action="<?= site_url('items/save_label_size') ?>">
          <?= csrf_field() ?>
          <label class="form-label">Save This Custom Size</label>
          <input type="text" name="name" class="form-control mb-2" placeholder="e.g., Warehouse Label">
          <input type="hidden" name="width" id="save_width">
          <input type="hidden" name="height" id="save_height">
          <button type="submit" class="btn btn-outline-primary btn-sm" onclick="copySizeToHidden()">Save Size</button>
        </form>
      </div>

    </div>
    <div class="modal-footer">
      <button type="submit" class="btn btn-primary">Generate PDF</button>
    </div>
  </div>
</form>
</div>
</div>

<!-- Scripts -->
<script>
function toggleCustomSizeFields(value) {
const customFields = document.getElementById('customSizeFields');
customFields.style.display = (value === 'custom') ? 'flex' : 'none';
}

function applySavedSize(select) {
const value = select.value;
if (value.includes('x')) {
const [w, h] = value.split('x');
document.getElementById('custom_width').value = w;
document.getElementById('custom_height').value = h;
}
}

function copySizeToHidden() {
document.getElementById('save_width').value = document.getElementById('custom_width').value;
document.getElementById('save_height').value = document.getElementById('custom_height').value;
}
</script>


        <!-- Items Table -->
        <?php if (!empty($items)): ?>


          <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
              <thead class="table-light">
                <tr>
                  <th>#</th>
                  <th>Image</th>
                  <th>Item No</th>
                  <th>Name</th>
                  <th>Category</th>
                  <th>Quantity</th>
                  <th>Reorder Qty</th>
                  <th>Unit Price</th>
                  <th>Actions</th>
                </tr>
              </thead>
              <tbody>
                <?php foreach ($items as $index => $item): ?>
                  <tr class="<?= $item['quantity'] <= $item['reorder_level_quantity'] ? 'bg-danger text-white fw-bold' : '' ?>">
                    <td><?= $index + 1 ?></td>
                    <td>
                      <?php if (!empty($item['photo'])): ?>
                        <img src="<?= base_url($item['photo']) ?>" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
                      <?php else: ?>
                        <span class="text-muted">No Image</span>
                      <?php endif; ?>
                    </td>
                    <td>
                      <?= esc($item['item_no']) ?>
                      <?php if ($item['quantity'] <= $item['reorder_level_quantity']): ?>
                        <i class="fa fa-exclamation-triangle ms-1 text-warning" title="Reorder Needed"></i>
                      <?php endif; ?>
                    </td>
                    <td><?= esc($item['name']) ?></td>
                    <td><?= esc($item['category_name']) ?></td>
                    <td><?= esc($item['quantity']) ?></td>
                    <td><?= esc($item['reorder_level_quantity']) ?></td>
                    <td><?= number_format($item['unit_price'], 2) ?></td>
                    <td>
                      <div class="btn-group btn-group-sm" role="group">
                        <a href="<?= site_url('items/edit/' . $item['id']) ?>" class="btn btn-warning">Edit</a>
                        <form action="<?= site_url('items/delete/' . $item['id']) ?>" method="post" onsubmit="return confirm('Are you sure?');">
                          <?= csrf_field() ?>
                          <button type="submit" class="btn btn-danger">Delete</button>
                        </form>
                        <a href="<?= site_url('items_units_of_measure/' . $item['id']) ?>" class="btn btn-info">Units</a>
                      </div>
                    </td>
                  </tr>
                <?php endforeach ?>
              </tbody>
            </table>
          </div>
          <!-- Pagination -->
          <div class="mt-4">
            <?= $pager->links('default', 'short_pagination') ?>
          </div>
        <?php else: ?>
          <div class="alert alert-info">No items found.</div>
        <?php endif; ?>


      </div>

      <?= $this->endSection() ?>
