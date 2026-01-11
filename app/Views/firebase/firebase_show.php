<?= $this->extend('layout') ?>

<?= $this->section('content') ?>
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Institution Dashboard</h1>
                <p>Welcome, <?= esc($username) ?>!</p>
            </div>
            <div class="col-sm-6 text-right">
                <a href="<?= site_url('logout') ?>" class="btn btn-danger">Logout</a>
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#profileModal">
                    <img src="<?= esc($profile_image) ?>" alt="Profile Image" class="rounded-circle" width="30">
                </button>
            </div>
        </div>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <!-- Your content here -->
          <h1><?= esc($business['name']) ?></h1>
    <p><strong>Location:</strong> <?= esc($business['location']) ?></p>
    <p><strong>Phone:</strong> <?= esc($business['phone']) ?></p>
    <p><strong>Category:</strong> <?= esc($business['category']) ?></p>
    <p><strong>About:</strong> <?= esc($business['aboutBusiness']) ?></p>
    <a href="<?= base_url('firebase') ?>" class="btn btn-primary">Back to Businesses</a>
 
    </div>
</div>

<!-- Profile Modal -->
<div class="modal fade" id="profileModal" tabindex="-1" role="dialog" aria-labelledby="profileModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="profileModalLabel">User Profile</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body text-center">
        <img src="<?= esc($profile_image) ?>" alt="Profile Image" class="rounded-circle mb-3" width="100">
        <h4><?= esc($username) ?></h4>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
