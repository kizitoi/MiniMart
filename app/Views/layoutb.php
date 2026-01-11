<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <title>Nairobi MD Ltd.</title>

  <link rel="icon" type="image/png" href="https://nairobimetaldetectors.com/favicon.png" />

  <!-- Google Maps Places API -->
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyA3puSMIyMv6Ht-5Ksar-2nonEaZEcOURU&libraries=places" async defer></script>

  <!-- ✅ Bootstrap 5 only -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

  <!-- AdminLTE -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>

  <style>
    .main-sidebar {
      background-color: #002B5B !important;
    }

    .main-sidebar .nav-sidebar > .nav-item > .nav-link.active,
    .main-sidebar .nav-sidebar > .nav-item > .nav-link:hover {
      background-color: #0056b3 !important;
      color: #fff !important;
    }

    .brand-link {
      font-size: 1.2rem;
      font-weight: bold;
      text-align: center;
      background-color: #001F3F;
      color: #fff;
      padding: 1rem 0;
    }

    .nav-sidebar .nav-link {
      color: #d1e9ff !important;
    }

    .btn-custom-blue {
      background-color: #145A32;
      border: 2px solid #ffffff;
      color: white;
    }

    .btn-custom-blue:hover {
      background-color: #0e4024;
      color: white;
    }
  </style>
</head>


<body class="hold-transition sidebar-mini">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light shadow-sm border-bottom py-2 px-3">
    <div class="container-fluid d-flex justify-content-between align-items-center">

      <!-- Left Section: Sidebar toggle + Logo + Company Name -->
      <div class="d-flex align-items-center">
        <a class="nav-link me-3" data-widget="pushmenu" href="#" role="button">
          <i class="fas fa-bars fa-lg text-secondary"></i>
        </a>

        <img src="https://nairobimetaldetectors.net/logo.jpg" alt="Company Logo" width="200" height="90"   shadow-sm me-2" style="object-fit: cover;">

        <div class="d-none d-md-block">
          <h5 class="mb-0 fw-semibold text-primary"><?= esc($companyName ?? 'Nairobi Metal Detectors Ltd') ?></h5>
          <small class="text-muted">Welcome, <?= esc($username) ?>!</small>
        </div>
      </div>

      <!-- Right Section: Actions -->
      <div class="d-flex align-items-center">
        <a href="<?= site_url('logout') ?>" class="btn btn-outline-danger me-2">
          <i class="fas fa-sign-out-alt"></i> Logout
        </a>

        <button type="button" class="btn btn-outline-info position-relative" data-bs-toggle="modal" data-bs-target="#profileModal" title="Profile">
          <?php if (!empty($user['profile_image'])): ?>
            <img src="<?= base_url('view-image/' . esc($user['user_id']) . '/profile') ?>" width="40" height="40" class="rounded-circle shadow-sm" style="object-fit: cover;">
          <?php else: ?>
            <i class="fas fa-user-circle fa-lg"></i>
          <?php endif; ?>
        </button>
      </div>

    </div>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <div class="brand-link">Nairobi MD Ltd.</div>

    <!-- Sidebar -->
    <div class="sidebar">
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="info">
          <a href="<?= site_url('profile') ?>" class="d-block text-white">
            <?= session()->get('username') ?> (Profile)
          </a>
        </div>
      </div>

      <div style="max-height: 80vh; overflow-y: auto; padding-right: 5px;">
        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column">
                 <li class="nav-item mb-2">
              <a href="<?= site_url('items/express-sale') ?>" class="btn btn-block btn-custom-blue text-left">
                <i class="fa fa-money text-white"></i>
                <span class="d-none d-sm-inline ml-2">Express sales</span>
              </a>

              <a href="<?= site_url('officer/officer') ?>" class="btn btn-block btn-custom-blue text-left">
                <i class="fa fa-money text-white"></i>
                <span class="d-none d-sm-inline ml-2">Sell By Category</span>
              </a>

            </li>

            <?php if (!empty($header_links)): ?>
              <?php foreach ($header_links as $link): ?>
                <?php if ($link['isbutton'] == '1'): ?>
                  <li class="nav-item mb-2">
                    <a href="<?= site_url($link['link']) ?>" class="btn btn-block text-left <?= ($link['link'] == 'incidents') ? 'btn-primary' : 'btn-secondary' ?>">
                      <i class="<?= esc($link['icon']) ?>"></i>
                      <span class="d-none d-sm-inline ml-2"><?= esc($link['label']) ?></span>
                    </a>
                  </li>
                <?php endif; ?>
              <?php endforeach; ?>
            <?php endif; ?>

            <li class="nav-item mb-2">
  <a href="<?= site_url('system_documentation') ?>" class="btn btn-block btn-custom-blue text-left">
  <i class="fa fa-clock text-white"></i>
   <span class="d-none d-sm-inline ml-2">System Documentation</span>
   </a>
          </ul>
        </nav>
      </div>
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper -->
  <div class="content-wrapper">
    <?php // include('header.php');?>
    <?= $this->renderSection('content') ?>
  </div>

  <!-- Footer -->
  <footer class="main-footer">
  <?php include('footer.php');?>
</footer>
</div>

<!-- Scripts -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!--<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>-->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>


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

</body>
</html>
