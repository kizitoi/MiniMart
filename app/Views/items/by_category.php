<?= $this->extend('layout') ?>


<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='items/express-sale')
      { ?>
<?= $this->section('content') ?>

<div class="container mt-4">
    <h2 class="mb-4">Items in <?= esc($category_name ?? 'Selected Category') ?></h2>

    <!-- Search Bar -->
    <form method="get" class="mb-4">
        <div class="input-group">
            <input type="text" name="filter" class="form-control" placeholder="Search items..." value="<?= esc($filter ?? '') ?>">
            <button class="btn btn-primary" type="submit">Search</button>
        </div>
    </form>

    <div>
        <a href="<?= site_url('officer/officer') ?>" class="btn btn-warning btn-sm">
            <i class="fa fa-arrow-left"></i> Back to Categories
        </a>
    </div>
    <hr>


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


    <?php if (!empty($items)): ?>




      <div class="row row-cols-2 row-cols-sm-3 row-cols-md-4 row-cols-lg-5 g-4">
      <?php foreach ($items as $item): ?>
          <div class="col">
              <div class="card h-100 border-0 shadow-sm hover-shadow text-center">
                  <a href="javascript:void(0);" class="text-decoration-none" onclick="showItemModal(
                      <?= esc($item['id']) ?>,
                      '<?= esc(addslashes($item['name'])) ?>',
                      <?= esc($item['unit_price']) ?>,
                      <?= esc($item['category_id']) ?>,
                      <?= esc($item['shop_id']) ?>
                  )">
                      <?php if (!empty($item['photo'])): ?>
                          <img src="<?= base_url($item['photo']) ?>" class="card-img-top" alt="Item Image" style="height: 140px; object-fit: cover;">
                      <?php else: ?>
                          <div class="card-img-top d-flex align-items-center justify-content-center bg-light" style="height: 140px;">
                              <span class="text-muted">No Image</span>
                          </div>
                      <?php endif; ?>

                          <div class="card-body p-3 text-center">
                          <!-- Name -->
                          <div class="mb-1">
                              <h6 class="card-title mb-1 fw-bold text-primary"><?= esc($item['name']) ?></h6>
                          </div>
<br>
                          <!-- Item No (below name) -->
                        <div class="mb-1">
                            <small class="text-muted d-block"><?= esc($item['item_no']) ?></small>
                        </div>

                          <!-- Qty | Reorder Level -->
                          <div class="mb-1">
                              <small class="<?= ($item['quantity'] <= $item['reorder_level_quantity']) ? 'text-danger fw-bold' : 'text-muted' ?>">
                                  Qty: <?= esc($item['quantity']) ?>
                              </small>
                              <small class="text-muted"> | Reorder Lvl: <?= esc($item['reorder_level_quantity']) ?></small>
                          </div>

                          <!-- Price -->
                          <div class="mb-1">
                              <strong class="text-success">KES <?= number_format($item['unit_price'], 2) ?></strong>
                          </div>

                          <!-- Units (optional) -->
                          <?php if (!empty($item['units'])): ?>
                              <div>
                                  <small class="text-muted"><?= esc($item['units']) ?></small>
                              </div>
                          <?php endif; ?>
                      </div>
                  </a>
              </div>
          </div>
      <?php endforeach ?>
  </div>




    <?php else: ?>
        <div class="alert alert-info">No items found for this category.</div>
    <?php endif; ?>
</div>

<!-- Modal for Adding Item to Order -->
<div class="modal fade" id="itemModal" tabindex="-1" aria-labelledby="itemModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="addToOrderForm">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="itemModalLabel">Add Item to Order</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p><strong>Name:</strong> <span id="modalItemName"></span></p>
                    <p><strong>Price (KES):</strong> <span id="modalItemPrice"></span></p>
                    <div class="mb-3">
                        <label for="quantityInput" class="form-label">Quantity</label>
                        <input type="number" class="form-control" id="quantityInput" min="1" value="1" required>
                    </div>
                    <p><strong>Total Cost (KES):</strong> <span id="modalTotalCost">0.00</span></p>
                    <input type="hidden" id="modalItemId">
                    <input type="hidden" id="modalItemPriceVal">
                    <input type="hidden" id="modalItemCategoryId">
                    <input type="hidden" id="modalItemShopId">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Add to Order</button>
                </div>
            </div>
        </form>
    </div>
</div>



<script>
function showItemModal(id, name, price, categoryId, shopId) {
    document.getElementById('modalItemId').value = id;
    document.getElementById('modalItemName').innerText = name;
    document.getElementById('modalItemPrice').innerText = parseFloat(price).toFixed(2);
    document.getElementById('modalItemPriceVal').value = price;
    document.getElementById('modalItemCategoryId').value = categoryId;
    document.getElementById('modalItemShopId').value = shopId;
    document.getElementById('quantityInput').value = 1;
    document.getElementById('modalTotalCost').innerText = parseFloat(price).toFixed(2);

    new bootstrap.Modal(document.getElementById('itemModal')).show();
}

document.getElementById('quantityInput').addEventListener('input', function () {
    let price = parseFloat(document.getElementById('modalItemPriceVal').value);
    let qty = parseInt(this.value) || 1;
    document.getElementById('modalTotalCost').innerText = (price * qty).toFixed(2);
});

document.getElementById('addToOrderForm').addEventListener('submit', function (e) {
    e.preventDefault();
    const data = {
        item_id: document.getElementById('modalItemId').value,
        quantity: document.getElementById('quantityInput').value,
        price: document.getElementById('modalItemPriceVal').value,
        category_id: document.getElementById('modalItemCategoryId').value,
        shop_id: document.getElementById('modalItemShopId').value,
    };

    fetch("<?= site_url('sales/add') ?>", {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.status === 'success') {
          //  alert("Item added to order.");
            bootstrap.Modal.getInstance(document.getElementById('itemModal')).hide();
        } else {
            alert("Error: " + res.message);
        }
    })
    .catch(() => alert("Failed to add item."));
});
</script>
<!-- Include this before closing </body> -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<?= $this->endSection() ?>

</div>


<?php } ?>
<?php endforeach; ?>
<?php endif; ?>
