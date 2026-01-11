<input type="hidden" name="token" value="<?= esc($_GET['token'] ?? '') ?>">

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>nairobimetaldetectors POS</title>

    <link rel="icon" type="https://nairobimetaldetectors.net/images/favicon.png" href="favicon.png" />

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
        }
        .login-box {
            width: 100%;
            max-width: 360px;
            margin: 7% auto;
        }
        @media (max-width: 768px) {
            .login-box {
                padding: 20px;
                box-shadow: none;
            }
            .card {
                border: none;
                box-shadow: none;
            }
        }
        .login-logo img {
            max-width: 100%;
            height: auto;
        }
    </style>
</head>
<body class="hold-transition login-page">
  <div class="container py-5">
  <div class="row justify-content-center">
    <div class="col-md-6 col-lg-5">
      <div class="card shadow-lg border-0">
        <div class="card-header bg-primary text-white text-center">
                <img src='https://nairobimetaldetectors.net/logo.jpg' alt="Logo" width="100%" height ="350px"> <br>
                <h4 class="mb-0"><i class="fa fa-lock me-2"></i>Reset Your Password</h4>
        </div>
        <div class="card-body p-4">
          <form action="<?= site_url('reset-password') ?>" method="post">

            <?= csrf_field() ?>

            <div class="mb-3">
              <label for="password" class="form-label">New Password</label>
              <input type="password" class="form-control" name="password" id="password" placeholder="Enter new password" required>
            </div>

            <div class="mb-3">
              <label for="confirm_password" class="form-label">Confirm New Password</label>
              <input type="password" class="form-control" name="confirm_password" id="confirm_password" placeholder="Repeat new password" required>
            </div>

            <div class="d-grid">
              <button type="submit" class="btn btn-primary">
                <i class="fa fa-key me-1"></i>    Reset Password
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>


</div>
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>
</body>
</html>
