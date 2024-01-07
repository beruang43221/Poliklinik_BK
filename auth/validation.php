<?php
session_start();

// Memeriksa apakah ada sesi login dan akses yang tersimpan
if (isset($_SESSION['login']) && isset($_SESSION['akses'])) {
  $akses = $_SESSION['akses'];

  // Redirect ke halaman sesuai dengan peran (akses) pengguna
  if ($akses === 'admin') {
    header('Location: ../menu/admin/');
    exit();
  } elseif ($akses === 'dokter') {
    header('Location: ../menu/dokter/');
    exit();
  } elseif ($akses === 'pasien') {
    header('Location: ../menu/pasien/');
    exit();
  } else {
    // Jika akses tidak dikenali, kembalikan ke halaman login atau halaman lain yang sesuai
    header('Location: ../.');
    exit();
  }
} else {
  // Jika tidak ada sesi, kembalikan ke halaman login atau halaman lain yang sesuai
  header('Location: ../.');
  exit();
}
?>