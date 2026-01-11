<?= $this->extend('layout') ?>

<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='items/express-sale')
      { ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <!-- Shop & Search Filter -->
    <form method="get" action="<?= site_url('officer/officer') ?>" class="row row-cols-1 row-cols-md-auto g-3 align-items-end mb-4">

        <div class="col">
            <label for="search" class="form-label fw-bold">Search Category:</label>
            <input type="text" name="search" id="search" value="<?= esc($search ?? '') ?>" class="form-control" placeholder="Search..." onkeydown="if(event.key==='Enter') this.form.submit()">
        </div>
    </form>



    <?php if (empty($existing_order)): ?>
        <form method="post" action="<?= site_url('orders/create') ?>" class="mb-3">
            <?= csrf_field() ?>
            <button class="btn btn-primary">
                <i class="fa fa-plus"></i> Create New Order
            </button>
        </form>
    <?php else: ?>
        <div class="alert alert-info mb-3">
            You already have an open order (ID: <?= esc($existing_order->id) ?>). Please complete or close it before creating a new one.
        </div>
    <?php endif; ?>

    <?php if (!empty($existing_order)): ?>
        <a href="<?= site_url('salesorder/current') ?>" class="btn btn-success mb-3">
            🧾 View Current Order
        </a>
    <?php endif; ?>



    <?php if (!empty($categories)): ?>
        <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 row-cols-lg-4 g-4">
            <?php foreach ($categories as $category): ?>
                <div class="col">
                    <a href="<?= site_url('items/by_category/' . $category['id']) ?>" class="text-decoration-none text-dark">
                        <div class="card h-100 shadow-sm border-0 hover-shadow">
                            <?php if (!empty($category['category_image'])): ?>
                                <img src="<?= base_url('uploads/category_images/' . $category['category_image']) ?>"
                                     alt="Category Image"
                                     class="card-img-top"
                                     style="height: 160px; object-fit: cover;">
                            <?php else: ?>
                                <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 160px;">
                                    <span class="text-muted">No Image</span>
                                </div>
                            <?php endif; ?>
                            <div class="card-body text-center">
                              <h5 class="card-title mb-1"><?= esc($category['category_name']) ?></h5>
                              <small class="text-muted"><?= esc($category['item_count']) ?> item<?= $category['item_count'] == 1 ? '' : 's' ?></small>
                            </div>
                        </div>
                    </a>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="alert alert-info mt-4">No item categories found.</div>
    <?php endif; ?>
</div>

<?= $this->endSection() ?>

<?php } ?>
<?php endforeach; ?>
<?php endif; ?>
