<?= $this->extend('layout') ?>

<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='items' )
      { ?>
<?= $this->section('content') ?>

<div class="container">
    <h2 class="my-4">Items</h2>

    <!-- Alert -->
    <?php if(session()->getFlashdata('success')): ?>
        <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
    <?php endif; ?>
    <?php if(session()->getFlashdata('error')): ?>
        <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
    <?php endif; ?>

    <!-- Filter Form -->
    <!--<form method="get" class="mb-3">
        <div class="input-group">
            <input type="text" name="filter" class="form-control" placeholder="Filter by Item Name..." value="<?//=esc($filter ?? '') ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>-->

    <!-- Add Button -->
      <a href="<?= site_url('items/create') ?>" class="btn btn-success mb-3">Add Item</a>
      <a href="<?= site_url('items') . '?reorder_only=1&filter=' . esc($filter ?? '') ?>" class="btn btn-outline-danger mb-3 <?= isset($reorderOnly) && $reorderOnly ? 'active' : '' ?>">
    Show Reorder Level Items
      </a>
<?php if (isset($reorderOnly) && $reorderOnly): ?>
    <a href="<?= site_url('items') . '?filter=' . esc($filter ?? '') ?>" class="btn btn-secondary mb-3 ms-2">Show All Items</a>
<?php endif; ?>

<!-- Filter Form -->
<form method="get" class="mb-3">
    <div class="row g-2">
        <div class="col-md-4">
            <input type="text" name="filter" class="form-control" placeholder="Filter by Item Name..." value="<?= esc($filter ?? '') ?>">
        </div>
        <div class="col-md-4">
            <select name="category_id" class="form-select" onchange="this.form.submit()">
                <option value="">-- Filter by Category --</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= isset($categoryId) && $categoryId == $cat['id'] ? 'selected' : '' ?>>
                        <?= esc($cat['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="col-md-2">
            <button class="btn btn-primary w-100" type="submit">Search</button>
        </div>
        <div class="col-md-2">
            <a href="<?= site_url('items') ?>" class="btn btn-secondary w-100">Reset</a>
        </div>
    </div>
</form>


    <!-- Export Buttons -->
    <div class="mb-3">
        <a href="<?= site_url('items/export/pdf?filter=' . urlencode($filter ?? '')) ?>" class="btn btn-danger">Export PDF</a>
        <a href="<?= site_url('items/export/word?filter=' . urlencode($filter ?? '')) ?>" class="btn btn-primary">Export Word</a>
        <a href="<?= site_url('items/export/excel?filter=' . urlencode($filter ?? '')) ?>" class="btn btn-success">Export Excel</a>
    </div>
    <!-- Items Table -->
    <?php if (!empty($items)): ?>
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th><i class="fa fa-picture-o" aria-hidden="true"></i></th>
                    <th>Item No</th>
                    <th>Name</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Re-Order Qty</th>
                    <th>Unit Price</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $index => $item): ?>
                  <tr class="<?= $item['quantity'] <= $item['reorder_level_quantity'] ? 'text-white bg-danger  fw-bold' : '' ?>">

        <td><?= $index + 1 ?></td>

        <td>
            <?php if (!empty($item['photo'])): ?>
                <img src="<?= base_url($item['photo']) ?>" alt="Photo" class="img-thumbnail" style="width: 80px; height: 80px; object-fit: cover;">
            <?php else: ?>
                <span class="text-muted">No Image</span>
            <?php endif; ?>
        </td>

      <!--  <td><?//=esc($item['item_no']) ?></td>-->
        <td>
          <?php if ($item['quantity'] <= $item['reorder_level_quantity']): ?>
              <i class="fa fa-exclamation-triangle ms-1 text-yellow" title="Reorder Needed"></i>
          <?php endif; ?>
    <?= esc($item['item_no']) ?>

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

        <!-- Pagination -->
<div class="mt-4">
    <?= $pager->links('default', 'short_pagination') ?>
</div>
    <?php else: ?>
        <div class="alert alert-info">No items found.</div>
    <?php endif ?>
</div>

<?= $this->endSection() ?>

<?php } ?>
<?php endforeach; ?>
<?php endif; ?>
