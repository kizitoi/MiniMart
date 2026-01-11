<div class="content-header bg-light border-bottom py-3 mb-4 shadow-sm">
  <div class="container-fluid">

    <div class="row align-items-center">
      <div class="col-md-8 d-flex align-items-center">
        <!-- Logo -->
        <img src="https://nairobimetaldetectors.net/logo.jpg" alt="Company Logo" width="100" height="100" class="rounded-circle me-3 shadow-sm" style="object-fit: cover;">

        <div>
          <h1 class="h4 mb-1 text-primary"><?= esc($companyName ?? 'nairobimetaldetectors POS') ?></h1>
          <p class="mb-0 text-muted">Welcome, <?= esc($username) ?>!</p>
        </div>
      </div>

      <div class="col-md-4 text-end">
        <a href="<?= site_url('logout') ?>" class="btn btn-outline-danger me-2">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>

        <!--<button type="button" class="btn btn-outline-info" data-toggle="modal" data-target="#profileModal" title="Profile">-->
              <button type="button" class="btn btn-outline-info" data-bs-toggle="modal" data-bs-target="#profileModal" title="Profile">
          <?php if (!empty($user['profile_image'])): ?>
            <img src="<?= base_url('view-image/' . esc($user['user_id']) . '/profile') ?>" width="40" height="40" class="rounded-circle shadow-sm" style="object-fit: cover;">
          <?php else: ?>

            <i class="fas fa-user-circle fa-lg"></i>
          <?php endif; ?>
        </button>


      </div>
    </div>

    <hr class="mt-3">
  </div>
</div>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">

      <div class="modal-header">
        <h5 class="modal-title" id="profileModalLabel">User Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>

      <div class="modal-body text-center">
        <?php if (!empty($user['profile_image'])): ?>
          <small class="d-block mt-1 mb-3">
            <img src="<?= base_url('view-image/' . esc($user['user_id']) . '/profile') ?>" width="100" class="img-thumbnail rounded-circle shadow-sm">
          </small>
        <?php endif; ?>

        <h2><?= esc($name) ?></h2>
        <h4 class="text-muted"><?= esc($username) ?></h4>
        <p><?= esc($email) ?></p>
        <p><?= esc($mobile) ?></p>
      </div>

      <div class="modal-footer justify-content-between">
        <a href="<?= site_url('profile') ?>" class="btn btn-primary">Edit Profile</a>
        <button type="button" class="btn btn-primary text-white" data-bs-dismiss="modal">Close</button>
      </div>

    </div>
  </div>
</div>
