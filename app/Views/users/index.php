<?= $this->extend('layout') ?>

<?php if (!empty($header_links)): ?>
   <?php foreach ($header_links as $link): ?>
     <?php if ($link['link']=='users')
      { ?>


        <?= $this->section('content') ?>

        <div class="container py-4">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2>🧑‍💼 User Management</h2>
                <a href="<?= site_url('register') ?>" class="btn btn-primary" target="_blank">
                    <i class="bi bi-person-plus"></i> Add New User
                </a>
            </div>

            <form class="row g-3 mb-4" method="get">
          <div class="col-md-3">
              <label for="role_id" class="form-label">Filter by Role</label>
              <select name="role_id" id="role_id" class="form-select">
                  <option value="">-- All Roles --</option>
                  <?php foreach ($roles as $role): ?>
                      <option value="<?= $role['id'] ?>" <?= $filterRole == $role['id'] ? 'selected' : '' ?>>
                          <?= esc($role['name']) ?>
                      </option>
                  <?php endforeach; ?>
              </select>
          </div>

          <div class="col-md-3">
              <label for="shop_id" class="form-label">Filter by Shop</label>
              <select name="shop_id" id="shop_id" class="form-select">
                  <option value="">-- All Shops --</option>
                  <?php foreach ($shops as $shop): ?>
                      <option value="<?= $shop['id'] ?>" <?= $filterShop == $shop['id'] ? 'selected' : '' ?>>
                          <?= esc($shop['name']) ?>
                      </option>
                  <?php endforeach; ?>
              </select>
          </div>

          <div class="col-md-4">
              <label for="search" class="form-label">Search</label>
              <input type="text" name="search" id="search" value="<?= esc($search) ?>" class="form-control" placeholder="Search by username or email">
          </div>

          <div class="col-md-2 d-flex align-items-end">
              <button type="submit" class="btn btn-outline-primary w-100"><i class="bi bi-filter"></i> Filter</button>
          </div>
      </form>


            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success"><?= session()->getFlashdata('success') ?></div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger"><?= session()->getFlashdata('error') ?></div>
            <?php endif; ?>

            <div class="table-responsive">
                <table class="table table-striped table-bordered align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>👤 Username</th>
                            <th>📧 Email</th>
                            <th>🔐 Role</th>
                            <th>🏪 Shop</th>
                            <th class="text-center">⚙️ Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td><?= esc($user['username']) ?></td>
                                <td><?= esc($user['email']) ?></td>
                                <td><?= esc($user['role_name']) ?></td>
                                <td><?= esc($user['shop_name']) ?></td>
                                <td class="text-center">
                                    <a href="<?= site_url('users/form/' . $user['user_id']) ?>" class="btn btn-sm btn-warning me-1">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="<?= site_url('users/sendPasswordReset/' . $user['user_id']) ?>" class="btn btn-sm btn-danger">
                                        <i class="bi bi-key"></i> Reset Password
                                    </a>
                                    <a href="<?= site_url('notifications/' . $user['user_id']) ?>" class="btn btn-sm btn-info">
                                        <i class="fas fa-bell"></i> Notifications
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($users)): ?>
                            <tr>
                                <td colspan="5" class="text-center text-muted">No users found.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?= $this->endSection() ?>


<?php } ?>
<?php endforeach; ?>
<?php endif; ?>
