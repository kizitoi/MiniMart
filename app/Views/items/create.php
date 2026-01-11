<?= $this->extend('layout') ?>



<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='items' )
      { ?>


<?= $this->section('content') ?>

<div class="container">
    <h2 class="my-4">Add Item</h2>

    <form method="post" action="<?= site_url('items/store') ?>" enctype="multipart/form-data">
        <?= csrf_field() ?>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="category_id" class="form-label">Category</label>
                <select name="category_id" id="category_id" class="form-control" required>
                    <option value="">Select Category</option>
                    <?php foreach ($categories as $category): ?>
                        <option value="<?= esc($category['id']) ?>"><?= esc($category['category_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="col-md-6">
                <label for="item_no" class="form-label">Item No</label>
                <input type="text" name="item_no" id="item_no" class="form-control" value="<?= esc($item_no) ?>" readonly>
            </div>
        </div>

        <div class="mb-3">
            <label for="name" class="form-label">Item Name</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="description" class="form-label">Description</label>
            <textarea name="description" id="description" class="form-control" rows="3"></textarea>
        </div>

 <div class="mb-3">
            <label for="scanned_code" class="form-label">Scanned Code</label>
            <textarea name="scanned_code" id="scanned_code" class="form-control" rows="3"></textarea>
        </div>

        <div class="row mb-3">
            <div class="col-md-4">
                <label for="quantity" class="form-label">Quantity</label>
                <input type="number" name="quantity" id="quantity" class="form-control" required>
            </div>
            <div class="col-md-4">
                <label for="unit_price" class="form-label">Unit Price</label>
                <input type="number" name="unit_price" id="unit_price" class="form-control" step="0.01" required>
            </div>
            <div class="col-md-4">
                <label for="reorder_level_quantity" class="form-label">Reorder Level</label>
                <input type="number" name="reorder_level_quantity" id="reorder_level_quantity" class="form-control" step="0.01" required>
            </div>
        </div>

        <div class="row mb-3">
            <div class="col-md-6">
                <label for="vatable" class="form-label">Vatable</label>
                <select name="vatable" id="vatable" class="form-control">
                    <option value="1">Yes</option>
                    <option value="0">No</option>
                </select>
            </div>
            <div class="col-md-6">
                <label for="vat_id" class="form-label">VAT</label>
                <select name="vat_id" id="vat_id" class="form-control" required>
                    <option value="">Select VAT</option>
                    <?php foreach ($vat_settings as $vat): ?>
                        <option value="<?= esc($vat['id']) ?>"><?= esc($vat['vat_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <div class="mb-3">
            <label for="photo" class="form-label">Item Photo</label>
            <input type="file" name="photo" id="photo" class="form-control" accept="image/*">
        </div>

        <div class="mb-3">
            <button type="submit" class="btn btn-primary">Save Item</button>
            <a href="<?= site_url('items') ?>" class="btn btn-secondary">Cancel</a>
        </div>
    </form>
</div>

<?= $this->endSection() ?>

<?php } ?>
<?php endforeach; ?>
<?php endif; ?>
