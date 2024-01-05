<?php
include '../../koneksi.php';
require 'daftar_poli.php';

if (isset($_SESSION['login'])) {
  $_SESSION['login'] = true;
} else {
  echo "<meta http-equiv='refresh' content='0; url= ../../auth/login-pasien.php'>";
  die();
}

$id_pasien = $_SESSION['id'];
$no_rm = $_SESSION['no_rm'];
$nama = $_SESSION['username'];
$akses = $_SESSION['akses'];

$url = $_SERVER['REQUEST_URI'];
$url = explode("/", $url);
$id_poli = $url[count($url) - 1];

if ($akses != 'pasien') {
  echo "<meta http-equiv='refresh' content='0; url= ../../auth/login-pasien.php'>";
  die();
}

if (isset($_POST['submit'])) {
  if (isset($_POST['id_jadwal']) && $_POST['id_jadwal'] == "900") {
    echo "
      <script>
        alert('Jadwal Tidak Boleh Kosong!');
      </script>
    ";
    echo "<meta http-equiv='refresh' content='0'>";
    exit;
  }

  if (daftarPoli($_POST) > 0) {
    echo "
      <script>
        alert('Berhasil Mendaftar Poli');
      </script>
    ";
  } else {
    echo "
      <script>
        alert('Gagal Mendaftar Poli');
      </script>
    ";
  }
}
?>

<!-- Content Header (Page header) -->
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Daftar Poli Klinik</h1>
            </div><!-- /.col -->
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="dashboard.php?page=home">Home</a></li>
                    <li class="breadcrumb-item active">Poli Klinik</li>
                </ol>
            </div><!-- /.col -->
        </div><!-- /.row -->
    </div><!-- /.container-fluid -->
</div>
<!-- /.content-header -->
<!-- Main content -->
<div class="content">
    <div class="container-fluid">
        <div class="row">
            <section class="col-lg-5 connectedSortable">
                <!-- Form Daftar Poliklinik-->
                <div class="card">
                    <div class="card-header bg-gradient-primary">
                        <h3 class="card-title">
                            Daftar Poliklinik
                        </h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="post">
                            <div class="form-group">
                                <input type="hidden" class="form-control"  name="id_pasien" value="<?= $id_pasien ?>" required> </input>
                            </div>
                            <div class="form-group">
                                <label for="no_antrian">Nomor Rekam Medis</label>
                                <input type="text" class="form-control" id="no_rm" name="no_rm" value="<?= $no_rm ?>"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="id_poli">Pilih Poli</label>
                                <select class="form-control" id="inputPoli" required>
                                    <option value="" disabled selected>Open Menu</option>
                                    <?php 
                                    $data = $pdo->prepare("SELECT * FROM poli");
                                    $data->execute();
                                    if ($data->rowCount() == 0) {
                                      echo "<option>Tidak Ada Poli</option>";
                                    } else {
                                      while($d = $data->fetch()) {
                                        ?>
                                        <option value="<?php echo $d['id']; ?>"><?php echo $d['nama_poli']; ?></option>
                                        <?php
                                      }
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="inputJadwal">Pilih Jadwal</label>
                                <select class="form-control" id="inputJadwal" name="id_jadwal" required>
                                    <option value="900">Pilih Jadwal</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="keluhan">Keluhan</label>
                                <input type="text" class="form-control" id="keluhan" name="keluhan" required>
                            </div>

                            <button type="submit" class="btn btn-primary" name="submit">Tambah</button>
                        </form>
                    </div>
                </div>

            </section>
            <section class="col-lg-7 connectedSortable">

                <!-- Riawayat Daftar Poli -->
                <div class="card ">
                    <div class="card-header border-0 bg-gradient-primary">
                        <h2 class="card-title">
                            Riwayat Daftar Poli
                        </h2>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive p-0">
                            <table class="table align-items-center mb-0 table-hover">
                                <thead class="bg-grey1">
                                    <tr>
                                        <th class="text-center">No.</th>
                                        <th class="text-center">Poli</th>
                                        <th class="text-center">Dokter</th>
                                        <th class="text-center">Hari</th>
                                        <th class="text-center">Mulai</th>
                                        <th class="text-center">Selesai</th>
                                        <th class="text-center">Antrian</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $poli = $pdo->prepare("SELECT d.nama_poli as poli_nama,
                                                                  c.nama as dokter_nama,
                                                                  b.hari as jadwal_hari,
                                                                  b.jam_mulai as jadwal_mulai,
                                                                  b.jam_selesai as jadwal_selesai,
                                                                  a.no_antrian as antrian,
                                                                  a.id as poli_id
                                                                  
                                                                  FROM daftar_poli as a 
                                                                  
                                                                  INNER JOIN jadwal_periksa as b 
                                                                    ON a.id_jadwal = b.id
                                                                  INNER JOIN dokter as c 
                                                                    ON b.id_dokter = c.id
                                                                  INNER JOIN poli as d 
                                                                    ON c.id_poli = d.id
                                                                  WHERE a.id_pasien = $id_pasien
                                                                  ORDER BY a.id desc");

                                    $poli->execute();
                                    $no = 0;
                                    if ($poli->rowCount() == 0) {
                                      echo "Tidak Ada Data";
                                    } else {
                                      while($p = $poli->fetch()) {
                                    ?>
                                    
                                    <tr>
                                      <th scope="row">
                                        <?php
                                          ++$no;
                                          if ($no == 1) {
                                            echo "<span class='badge badge-info'>New</span>";
                                          } else {
                                            echo $no;
                                          }
                                        ?>
                                      </th>
                                      <td><?= $p['poli_nama']?></td>
                                      <td><?= $p['dokter_nama']?></td>
                                      <td><?= $p['jadwal_hari']?></td>
                                      <td><?= $p['jadwal_mulai']?></td>
                                      <td><?= $p['jadwal_selesai']?></td>
                                      <td><?= $p['antrian']?></td>
                                    </tr>
                                    <?php
                                      }
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>


            </section>
        </div>
        <!-- /.row -->
    </div><!-- /.container-fluid -->
    <!-- Modal Edit Data Obat -->
    <div id="seg-modal">

    </div>
    <!-- Modal Tambah Data Obat -->
    <div class="modal fade" id="addModal" tabindex="-1" role="dialog" aria-labelledby="addModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addModalLabel">Tambah Data Poli</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <!-- Form tambah data obat disini -->
                    <form action="pages/tambahPoli.php" method="post">
                        <div class="form-group">
                            <label for="nama_poli">Nama Poli</label>
                            <input type="text" class="form-control" id="nama_poli" name="nama_poli" required>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input type="text" class="form-control" id="keterangan" name="keterangan" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Tambah</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- Tambahkan tag script untuk jQuery sebelum script Anda -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <script>
        $(document).ready(function () {
            $('.edit-btn').on('click', function () {
                var dataId = $(this).data('obatid');
                $('#seg-modal').load('pages/editPoli.php?id=' + dataId, function () {
                    $('#editModal').modal('show');
                });
            });
        });
    </script>
    <!-- script pilih jadwal -->
    <script>
      document.getElementById('inputPoli').addEventListener('change', function() {
        var poliId = this.value;
        loadJadwal(poliId);
      });


      function loadJadwal(poliId) {
        // Buat Objek XMLHttpRequest
        var xhr = new XMLHttpRequest();

        //Konfigurasi Permintaan Ajax
        xhr.open('GET', 'http://localhost/Capstone_BK/menu/pasien/pages/get_jadwal.php?poli_id=' + poliId, true);

        xhr.setRequestHeader('Content-Type', 'text/html');

        xhr.onload = function(){
          if (xhr.status == 200) {
            document.getElementById('inputJadwal').innerHTML = xhr.responseText;
          }
        };

        xhr.send();
      }
    </script>


</div>