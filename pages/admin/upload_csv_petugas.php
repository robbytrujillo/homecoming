<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../../login.php');
    exit;
}
require '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($file, 'r');

    // Skip header row
    fgetcsv($handle, 1000, ',');

    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        $nama_petugas = $data[0];
        $nip = $data[1];
        $jabatan = $data[2];
        $mapel = $data[3];

        // Insert data ke database
        $stmt = $pdo->prepare("INSERT INTO petugas (nama_petugas, nip, jabatan, mapel) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama_petugas, $nip, $jabatan, $mapel]);
    }

    fclose($handle);
    header('Location: data_petugas.php');
    exit;
}
?>