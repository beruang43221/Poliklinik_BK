<?php
session_start();
include_once("../koneksi.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nama = $_POST['nama'];
  $alamat = $_POST['alamat'];
  $no_ktp = $_POST['no_ktp'];
  $no_hp = $_POST['no_hp'];

  try {
    $query_cek_pasien = "SELECT id, nama, no_rm FROM pasien WHERE no_ktp = :no_ktp";
    $stmt_cek_pasien = $pdo->prepare($query_cek_pasien);
    $stmt_cek_pasien->bindParam(':no_ktp', $no_ktp);
    $stmt_cek_pasien->execute();

    if ($stmt_cek_pasien->rowCount() > 0) {
      $row = $stmt_cek_pasien->fetch();

      if ($row['nama'] != $nama) {
        echo "<script>alert(`Nama Pasien Tidak Sesuai Dengan KTP yang Terdaftar.`);</script>";
        echo "<meta http-equiv='refresh' content='0; url= register.php'>";
        die();
      }
      $_SESSION['signup'] = true;
      $_SESSION['id'] = true;
      $_SESSION['username'] = $nama;
      $_SESSION['no_rm'] = $row['no_rm'];
      $_SESSION['akses'] = 'pasien';

      echo "<meta http-equiv='refresh' content='0; url= ../menu/pasien'>";
      die();
    }

    $queryGetRm = "SELECT MAX(SUBSTRING(no_rm, 8)) as last_queue_number FROM pasien";
    $stmtGetRm = $pdo->query($queryGetRm);
    $rowRM = $stmtGetRm->fetch();
    $lastQueueNumber = $rowRM['last_queue_number'];

    $lastQueueNumber = $lastQueueNumber ? $lastQueueNumber : 0;

    $tahun_bulan = date("Ym");

    $newQueueNumber = $lastQueueNumber + 1;

    $no_rm = $tahun_bulan . "-" . str_pad($newQueueNumber, 3, '0', STR_PAD_LEFT);

    $query = "INSERT INTO pasien (nama, alamat, no_ktp, no_hp, no_rm) VALUES (:nama, :alamat, :no_ktp, :no_hp, :no_rm)";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nama', $nama);
    $stmt->bindParam(':alamat', $alamat);
    $stmt->bindParam(':no_ktp', $no_ktp);
    $stmt->bindParam(':no_hp', $no_hp);
    $stmt->bindParam(':no_rm', $no_rm);

    if ($stmt->execute()) {
      $_SESSION['signup'] = true;
      $_SESSION['id'] = $pdo->lastInsertId();
      $_SESSION['username'] = $nama;
      $_SESSION['no_rm'] = $no_rm;
      $_SESSION['akses'] = 'pasien';

      echo "<meta http-equiv='refresh' content='0; url= ../menu/pasien'>";
      die();
    } else {
      echo "Error: " . $query . "<br>" . $stmt->errorInfo();
    }
  } catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
  }
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
<body class="hold-transition register-page">
<div class="register-box">
  <div class="card card-outline card-primary">
    <div class="card-header text-center">
      <a href="../../index2.html" class="h1"><b>Poli</b>Klinik</a>
    </div>
    <div class="card-body">
      <p class="login-box-msg">Register a new account</p>

      <form action="" method="post">
        <div class="input-group mb-3">
          <input type="text" class="form-control" required placeholder="Full name" name="nama">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-user"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="text" class="form-control" required placeholder="Alamat" name="alamat">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-map-marker"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="number" class="form-control" required placeholder="No KTP" name="no_ktp">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-address-book"></span>
            </div>
          </div>
        </div>
        <div class="input-group mb-3">
          <input type="number" class="form-control" required placeholder="No HP" name="no_hp">
          <div class="input-group-append">
            <div class="input-group-text">
              <span class="fas fa-phone-square"></span>
            </div>
          </div>
        </div>
        <div class="row">
          <div class="col-8">
            <div class="icheck-primary">
              <input type="checkbox" id="agreeTerms" name="terms" value="agree">
              <label for="agreeTerms">
               I agree to the <a href="#">terms</a>
              </label>
            </div>
          </div>
          <!-- /.col -->
          <div class="col-4">
            <button type="submit" class="btn btn-primary btn-block">Register</button>
          </div>
          <!-- /.col -->
        </div>
      </form>

      <a href="login-pasien.php" class="text-center">I already have a account</a>
    </div>
    <!-- /.form-box -->
  </div><!-- /.card -->
</div>
<!-- /.register-box -->

<!-- jQuery -->
<script src="../../plugins/jquery/jquery.min.js"></script>
<!-- Bootstrap 4 -->
<script src="../../plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<!-- AdminLTE App -->
<script src="../../dist/js/adminlte.min.js"></script>
</body>
</html>
