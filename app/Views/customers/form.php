<?= $this->extend('layout') ?>

<?php if (!empty($header_links)): ?>
  <?php foreach ($header_links as $link): ?>
    <?php if ($link['link'] == 'customers'): ?>
      <?php if ($link['can_add'] == '1' || $link['can_edit'] == '1'): ?>

<?= $this->section('content') ?>

<div class="container py-5">
  <div class="card shadow-sm border-0">
    <div class="card-body">

      <h3 class="text-primary mb-4">
        <i class="fas fa-user-edit me-2"></i> <?= isset($customer) ? 'Edit' : 'Add' ?> Customer
      </h3>

      <form method="post" action="<?= site_url('customers/save') ?>">
        <?= csrf_field() ?>

        <?php if (isset($customer)): ?>
          <input type="hidden" name="customer_id" value="<?= $customer['customer_id'] ?>">
        <?php endif; ?>

        <div class="mb-3">
          <label class="form-label"><i class="fas fa-user me-1"></i> Name <span class="text-danger">*</span></label>
          <input type="text" name="name" class="form-control" placeholder="Customer name" value="<?= esc($customer['name'] ?? '') ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label"><i class="fas fa-envelope me-1"></i> Email</label>
          <input type="email" name="email" class="form-control" placeholder="example@mail.com" value="<?= esc($customer['email'] ?? '') ?>">
        </div>

        <div class="mb-3">
          <label class="form-label"><i class="fas fa-phone me-1"></i> Phone</label>
          <input type="text" name="phone" class="form-control" placeholder="07XX XXX XXX" value="<?= esc($customer['phone'] ?? '') ?>">
        </div>

        <div class="mb-4">
          <label class="form-label"><i class="fas fa-map-marker-alt me-1"></i> Address</label>
          <textarea name="address" class="form-control" rows="3" placeholder="Physical or postal address"><?= esc($customer['address'] ?? '') ?></textarea>
        </div>



        <div class="text-end">
          <button type="submit" class="btn btn-success px-4">
            <i class="fas fa-save me-1"></i> Save
          </button>
        </div>
      </form>

    </div>
  </div>
</div>

<?= $this->endSection() ?>

      <?php endif; ?>
    <?php endif; ?>
  <?php endforeach; ?>
<?php endif; ?>
