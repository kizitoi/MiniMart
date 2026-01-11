<?= $this->extend('layout') ?>

<?php if (!empty($header_links)): ?>
  <?php foreach ($header_links as $link): ?>
    <?php if ($link['link'] == 'customers'): ?>

<?= $this->section('content') ?>

<div class="container py-5">
  <div class="card shadow-sm border-0">
    <div class="card-body">

      <div class="d-flex justify-content-between align-items-center mb-4">
        <h3 class="text-primary mb-0">
          <i class="fas fa-users me-2"></i> Customer List
        </h3>

        <?php if ($link['can_add'] == '1'): ?>
          <a href="<?= site_url('customers/form') ?>" class="btn btn-success">
            <i class="fas fa-user-plus me-1"></i> Add Customer
          </a>
        <?php endif; ?>
      </div>

      <?php if (session()->getFlashdata('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
          <?= session()->getFlashdata('success') ?>
          <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
      <?php endif; ?>

      <div class="table-responsive">
        <table class="table table-hover table-bordered align-middle shadow-sm">
          <thead class="table-primary">
            <tr>
              <th><i class="fas fa-user"></i> Name</th>
              <th><i class="fas fa-envelope"></i> Email</th>
              <th><i class="fas fa-phone-alt"></i> Phone</th>
              <th><i class="fas fa-map-marker-alt"></i> Address</th>
              <th><i class="fas fa-map-cart-alt"></i> Amount To clear</th>
            <!--  <th><i class="fas fa-map-money-alt"></i> Paid </th>
              <th><i class="fas fa-map-wallet-alt"></i> Balance</th>-->
              <th class="text-center"><i class="fas fa-cogs"></i> Actions</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($customers as $customer): ?>
              <tr>
                <td><?= esc($customer['name']) ?></td>
                <td><?= esc($customer['email']) ?></td>
                <td><?= esc($customer['phone']) ?></td>
                <td><?= esc($customer['address']) ?></td>
                <td>
                    <a href="<?= site_url('customer_accounts/'.$customer['customer_id']) ?>"
                       class="btn btn-sm btn-outline-info me-1"
                       style="color: <?= $customer['total_balance'] < 0 ? 'red' : 'green' ?>;">
                        <?= number_format($customer['total_balance'], 2) ?> KES
                    </a>
                </td>


                <!--<td><?//= number_format($customer['total_paid'], 2) ?> KES</td>
                <td><?//= number_format($customer['total_balance'], 2) ?> KES</td>-->

                <td class="text-center">
                  <?php if ($link['can_edit'] == '1'): ?>
                    <a href="<?= site_url('customers/form/'.$customer['customer_id']) ?>" class="btn btn-sm btn-outline-warning me-1">
                      <i class="fas fa-edit"></i>
                    </a>
                    <a href="<?= site_url('customer_accounts/'.$customer['customer_id']) ?>" class="btn btn-sm btn-outline-info me-1">
                      <i class="fas fa-wallet"></i>
                    </a>
                  <?php endif; ?>

                  <?php if ($link['can_delete'] == '1'): ?>
                    <a href="<?= site_url('customers/delete/'.$customer['customer_id']) ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this customer?')">
                      <i class="fas fa-trash-alt"></i>
                    </a>
                  <?php endif; ?>
                </td>
              </tr>
            <?php endforeach; ?>

            <?php if (empty($customers)): ?>
              <tr>
                <td colspan="5" class="text-center text-muted">No customers found.</td>
              </tr>
            <?php endif; ?>
          </tbody>
        </table>
      </div>

    </div>
  </div>
</div>

<?= $this->endSection() ?>

    <?php endif; ?>
  <?php endforeach; ?>
<?php endif; ?>
