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
        $nomor_induk = $data[0];
        $nama_siswa = $data[1];
        $kelas = $data[2];
        $nama_orang_tua = $data[3];

        // Insert data ke database
        $stmt = $pdo->prepare("INSERT INTO siswa (nomor_induk, nama_siswa, kelas, nama_orang_tua) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nomor_induk, $nama_siswa, $kelas, $nama_orang_tua]);
    }

    fclose($handle);
    header('Location: data_siswa.php');
    exit;
}
?>