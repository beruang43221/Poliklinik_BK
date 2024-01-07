<?php
include '../../../koneksi.php';

// Ambil Id dari parameter GET
$poliId = isset($_GET['poli_id']) ? $_GET['poli_id'] : null;

$query = "SELECT a.nama as nama_dokter,
                 b.hari as hari,
                 b.id as id_jp,
                 b.jam_mulai as jam_mulai,
                 b.jam_selesai as jam_selesai
         FROM dokter as a 
         INNER JOIN jadwal_periksa as b ON a.id = b.id_dokter
         WHERE a.id_poli = $poliId";

$result = mysqli_query($mysqli, $query);

// Jika tidak ada jadwal yang ditemukan, mencetak pesan "Tidak Ada Jadwal" dalam elemen <option>
if (mysqli_num_rows($result) === 0) {
    echo '<option> Tidak Ada Jadwal</option>'; 
} else {
    // Mencetak opsi pilihan dalam format yang diinginkan, dengan menggunakan data yang ditemukan dalam hasil query
    while ($jd = mysqli_fetch_assoc($result)) {
        echo '<option value="' . $jd['id_jp'] . '"> Dokter ' . $jd['nama_dokter'] . ' | ' . $jd['hari'] . ' | ' . $jd['jam_mulai'] . ' - ' . $jd['jam_selesai'] . '</option>'; 
    }
}
?>