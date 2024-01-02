<?php
$databaseHost = 'localhost:3308';
$databaseName = 'poli';
$databaseUsername = 'root';
$databasePassword = '';

try {
    $pdo = new PDO("mysql:host=$databaseHost;dbname=$databaseName;charset=utf8mb4", $databaseUsername, $databasePassword);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Koneksi database gagal: " . $e->getMessage());
}