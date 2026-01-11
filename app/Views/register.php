<!DOCTYPE html>
<html>
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title> NMD Point Of Sale  System</title>
  <link rel="icon" type="image/png" href="https://nairobimetaldetectors.net/images/favicon.png">
  <!-- Bootstrap CSS -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/css/bootstrap.min.css">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
  <!-- Custom Styles -->
  <style>
      body {
          background-image: url('/images/bg.jpg');
          background-size: cover;
          background-repeat: no-repeat;
          background-attachment: fixed;
          width: 100%;
      }
      .register-box {
          max-width: 400px;
          margin: 5% auto;
          padding: 20px;
          width: 100%;
      }
      @media (max-width: 768px) {
          .register-box {
              margin-top: 10%;
              width: 90%;
          }
          .card-body {
              padding: 15px;
          }
      }
      .login-logo img {
          max-width: 100%;
          height: auto;
      }
      .btn {
          width: 100%;
      }
  </style>
</head>
<body class="hold-transition register-page">
<div class="register-box">
    <div class="register-logo">
    </div>
    <div class="card">
        <div align="center" class="card-body register-card-body">
              <img src="<?= site_url('/logo.jpg') ?>" alt="Logo" class="mb-3" style="max-height:120px;">
            <b>Register</b>
            <p class="login-box-msg">Register a new User</p>

            <?php if (session()->getFlashdata('errors')): ?>
                <div class="alert alert-danger">
                    <ul>
                        <?php foreach (session()->getFlashdata('errors') as $error): ?>
                            <li><?= esc($error) ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form action="register/save" method="post" id="registerForm">
                <div class="input-group mb-3">
                    <input type="text" name="username" class="form-control" placeholder="Username" value="<?= old('username') ?>" required id="username">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-user"></span>
                        </div>
                    </div>
                </div>
                <div id="username-error" class="text-danger"></div>

                <div class="input-group mb-3">
                    <input type="email" name="email" class="form-control" placeholder="Email" value="<?= old('email') ?>" required id="email">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div id="email-error" class="text-danger"></div>

                <div class="input-group mb-3">
                    <input type="tel" name="mobile" class="form-control" placeholder="Mobile" value="<?= old('mobile') ?>" required id="mobile">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-mobile"></span>
                        </div>
                    </div>
                </div>
                <div id="mobile-error" class="text-danger"></div>

                <div class="input-group mb-3">
                    <input type="password" name="password" class="form-control" placeholder="Password" required>
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>

                <div class="input-group mb-3">
                    <select name="role_id" class="form-control" required readonly>
                        <?php foreach ($roles as $role): ?>
                            <?php //if($role['id'] != 2 && $role['id'] != 3 && $role['id'] != 5)

                            if($role['id'] == 4 )
                            { ?>
                                <option value="<?= $role['id'] ?>" <?= old('role_id') == $role['id'] ? 'selected' : '' ?>><?= $role['name'] ?></option>
                            <?php
                           } ?>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="row">
                    <!--<div class="col-8">

                    </div>-->
                  <!--  <div class="col-4"> -->
                        <button type="submit" class="btn btn-primary btn-block" id="registerBtn">Register</button>

                  <!--  </div>-->
                </div>

  <div class="row">
</br>

  </div>


            </form>
        </div>  <a href="login" class="text-center">I already have an account</a>
    </div>
</div>

<!-- jQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/4.6.0/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="https://cdn.jsdelivr.net/npm/admin-lte@3.1/dist/js/adminlte.min.js"></script>

<script>
$(document).ready(function() {
    function checkInput(input, url, errorElement) {
        var value = $(input).val();
        $.ajax({
            url: url,
            method: 'POST',
            data: { [input.slice(1)]: value },
            dataType: 'json',
            success: function(response) {
                if (response.exists) {
                    $(errorElement).text($(input).attr('placeholder') + ' already exists');
                    $('#registerBtn').prop('disabled', true);
                } else {
                    $(errorElement).text('');
                    if ($('#username-error').text() === '' && $('#email-error').text() === '' && $('#mobile-error').text() === '') {
                        $('#registerBtn').prop('disabled', false);
                    }
                }
            }
        });
    }

    $('#username').on('input', function() {
        checkInput('#username', 'register/checkUsername', '#username-error');
    });

    $('#email').on('input', function() {
        checkInput('#email', 'register/checkEmail', '#email-error');
    });

    $('#mobile').on('input', function() {
        checkInput('#mobile', 'register/checkMobile', '#mobile-error');
    });

    $('#registerForm').on('submit', function(e) {
        if ($('#username-error').text() !== '' || $('#email-error').text() !== '' || $('#mobile-error').text() !== '') {
            e.preventDefault();
        }
    });
});
</script>
</body>
</html>
