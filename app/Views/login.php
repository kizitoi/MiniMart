<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title><?= esc($companyName ?? 'Company') ?></title>

  <link rel="icon" type="image/png" href="<?= base_url('favicon.png') ?>" />

  <!-- AdminLTE CSS -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/css/adminlte.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">

  <style>
    body {
      background-image: url('bg.jpg'); /* Replace with your image path */
      background-size: cover;
      background-repeat: no-repeat;
      background-attachment: fixed;
      background-position: center;
    }
    .login-box {
      width: 100%;
      max-width: 380px;
      margin: 6% auto;
    }
    .login-logo img {
      max-width: 100%;
      height: auto;
    }
    .card {
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.2);
    }
    .btn-primary {
      border-radius: 8px;
      padding: 10px;
      font-weight: bold;
    }
    .mobile-login-btn {
      background: linear-gradient(135deg, #28a745, #218838);
      border: none;
      font-weight: bold;
      font-size: 16px;
      padding: 12px;
      border-radius: 8px;
      transition: all 0.3s ease-in-out;
      color: #fff !important;
    }
    .mobile-login-btn:hover {
      background: linear-gradient(135deg, #218838, #1e7e34);
      transform: translateY(-2px);
      box-shadow: 0 4px 10px rgba(0,0,0,0.2);
    }
    @media (max-width: 768px) {
      .login-box {
        margin: 12% auto;
        padding: 10px;
      }
      .card {
        border: none;
        box-shadow: none;
      }
    }
  </style>

  <script>
    // Detect if user is on mobile and redirect
    document.addEventListener("DOMContentLoaded", function () {
      const isMobile = /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent);
      if (isMobile) {
        window.location.href = "https://mobile.nairobimetaldetectors.net";
      }
    });
  </script>
</head>
<body class="hold-transition login-page">
  <div class="login-box">
    <div class="card">
      <div class="card-body login-card-body">
        <div class="login-logo mb-3">
          <img src="<?= site_url('/logo.jpg') ?>" alt="Logo" class="mb-3" style="max-height:120px;">
          <h4 class="font-weight-bold"><i class="fa fa-globe" aria-hidden="true"></i> Web Login</h4>
        </div>

        <p class="login-box-msg">Sign in to start your session</p>

        <?php if(session()->getFlashdata('msg')):?>
          <div class="alert alert-danger"><?= session()->getFlashdata('msg') ?></div>
        <?php endif;?>

        <form action="auth/authenticate" method="post">
          <div class="input-group mb-3">
            <input type="text" name="username" class="form-control" placeholder="Username" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-user"></span>
              </div>
            </div>
          </div>

          <div class="input-group mb-3">
            <input type="password" name="password" class="form-control" placeholder="Password" required>
            <div class="input-group-append">
              <div class="input-group-text">
                <span class="fas fa-lock"></span>
              </div>
            </div>
          </div>

          <div class="row mb-2">
            <div class="col-12">
              <button type="submit" class="btn btn-primary btn-block">
                <i class="fas fa-sign-in-alt mr-2"></i> Sign In
              </button>
            </div>
          </div>
        </form>

        <!-- Mobile Login Button (still visible on desktop if needed) -->
        <div class="row mt-2">
          <div class="col-12">
            <a href="https://mobile.nairobimetaldetectors.net"
               class="btn btn-success btn-block mobile-login-btn">
              <i class="fas fa-mobile-alt mr-2"></i> Login with Mobile App
            </a>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-12 text-center">
            <a href="register" class="text-center">Register a new user</a>
          </div>
        </div>
      </div>
    </div>
  </div>

  <!-- jQuery -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
  <!-- Bootstrap 4 -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
  <!-- AdminLTE App -->
  <script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
</body>
</html>
