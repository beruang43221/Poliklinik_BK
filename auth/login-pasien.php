<?php
session_start();
require_once ('../koneksi.php');

if (isset($_SESSION['login'])) {
  header('Location: ../auth/validation.php');
  exit();
}
?>


<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Poliklinik</title>

  <!-- Google Font: Source Sans Pro -->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
  <!-- Font Awesome -->
  <link rel="stylesheet" href="../assets/AdminLTE-3.2.0/plugins/fontawesome-free/css/all.min.css">
  <!-- icheck bootstrap -->
  <link rel="stylesheet" href="../assets/AdminLTE-3.2.0/plugins/icheck-bootstrap/icheck-bootstrap.min.css">
  <!-- Theme style -->
  <link rel="stylesheet" href="../assets/AdminLTE-3.2.0/dist/css/adminlte.min.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
  <!-- /.login-logo -->
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="index.php" class="h1"><b>Poli</b>klinik</a>
    </div>
    <div class="card-body">
    
      <p class="login-box-msg">Sign in</p>
      <?php if (isset($_SESSION['error'])): ?>
        <p style="color:red; font-size: italic; margin-bottom: 1rem;"><?php echo $_SESSION['error']; ?></p>
        <?php unset($_SESSION['error']); ?>
      <?php endif; ?>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" name="nama" class="form-control" placeholder="Username | Case Sensitive">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-envelope"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="password" name="alamat" class="form-control" placeholder="Password | Case Sensitive">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-lock"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="remember">
              <label for="remember">
                Remember Me
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" name="submit" class="btn btn-primary btn-block">Sign In</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <!-- /.social-auth-links -->

      <p class="mb-0">
        <a href="register.php" class="text-center">Register a new account</a>
      </p>
    </div>
    <!-- /.card-body -->
  </div>
  <!-- /.card -->
</div>
<!-- /.login-box -->

<!-- jQuery -->
<script src="assets/AdminLTE-3.2.0/plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="assets/AdminLTE-3.2.0/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="assets/AdminLTE-3.2.0/dist/js/adminlte.min.js"></script>
</body>
</html>


<?php

if (isset($_POST['submit'])) {
  $username = stripslashes($_POST['nama']);
  $password = $_POST['alamat'];

  if ($username == 'admin' && $password == 'admin') {
    // Jika login sebagai admin berhasil
    $_SESSION['login'] = true;
    $_SESSION['id'] = null;
    $_SESSION['username'] = 'admin';
    $_SESSION['akses'] = 'admin';
    header('Location: ../auth/validation.php');
    exit;
  } else {
    // Melakukan query ke database
    $sql = "SELECT * FROM pasien WHERE nama = '$username'";
    $result = mysqli_query($mysqli, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
      // Jika username ditemukan dalam tabel pasien
      $row = mysqli_fetch_assoc($result);
      if ($password == $row['alamat']) {
        // Jika password cocok
        $_SESSION['login'] = true;
        $_SESSION['id'] = $row['id'];
        $_SESSION['username'] = $row['nama'];
        $_SESSION['no_rm'] = $row['no_rm'];
        $_SESSION['akses'] = 'pasien';
        header('Location: ../auth/validation.php');
        exit;
      }
    }
  }

  // Jika username atau password tidak cocok
  $_SESSION['error'] = 'Username dan Password Tidak Cocok';
  echo "<meta http-equiv='refresh' content='0;' >";
  die();
}
?>
