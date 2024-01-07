<?php
function daftarPoli($data, $mysqli)
{
    try {
        $id_pasien = $data["id_pasien"];
        $id_jadwal = $data["id_jadwal"];
        $keluhan = $data["keluhan"];
        $no_antrian = getLatestNoAntrian($id_jadwal, $mysqli) + 1;

        $query = "INSERT INTO daftar_poli VALUES (NULL, ?, ?, ?, ?)";
        $stmt = mysqli_prepare($mysqli, $query);
        mysqli_stmt_bind_param($stmt, "iiii", $id_pasien, $id_jadwal, $keluhan, $no_antrian);

        if (mysqli_stmt_execute($stmt)) {
            return mysqli_stmt_affected_rows($stmt);
        } else {
            echo "Error updating record:" . mysqli_stmt_error($stmt);
            return -1;
        }
    } catch (\Exception $e) {
        var_dump($e->getMessage());
    }
}

function getLatestNoAntrian($id_jadwal, $mysqli)
{
    $latestNoAntrianQuery = "SELECT MAX(no_antrian) as max_no_antrian FROM daftar_poli WHERE id_jadwal = ?";
    $stmt = mysqli_prepare($mysqli, $latestNoAntrianQuery);
    mysqli_stmt_bind_param($stmt, "i", $id_jadwal);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $max_no_antrian);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);

    return $max_no_antrian ? $max_no_antrian : 0;
}
?>