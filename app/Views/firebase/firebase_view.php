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


   <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>#</th>
                <th>Business Name</th>
                <th>Contact Name</th>
                <th>Phone</th>
                <th>Location</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $startNumber = ($currentPage - 1) * $perPage + 1;
            foreach ($businesses as $index => $business): ?>
            <tr>
                <td><?= $startNumber + $index ?></td>
                <td><?= esc($business['name']) ?></td>
                <td><?= esc($business['contactNames']) ?></td>
                <td><?= esc($business['phone']) ?></td>
                <td><?= esc($business['location']) ?></td>
                <td>
                    <a href="<?= site_url('firebase/show/' . esc($business['id'])) ?>" class="btn btn-primary btn-sm">View</a>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="d-flex justify-content-between">
        <a href="<?= site_url('admin/dashboard') ?>" class="btn btn-secondary">Back to Dashboard</a>
        <?= $pager ?>
    </div>








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
        <p><?= esc($email) ?></p>
        <p><?= esc($mobile) ?></p>
      </div>
      <div class="modal-footer justify-content-between">
        <a href="<?= site_url('profile/edit') ?>" class="btn btn-primary">Edit Profile</a>
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
      </div>
    </div>
  </div>
</div>

<?= $this->endSection() ?>
