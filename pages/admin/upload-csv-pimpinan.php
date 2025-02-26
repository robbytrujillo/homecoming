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
        $nip = $data[0];
        $nama_pimpinan = $data[1];
        $jabatan = $data[2];

        // Insert data ke database
        $stmt = $pdo->prepare("INSERT INTO pimpinan (nip, nama_pimpinan, jabatan) VALUES (?, ?, ?)");
        $stmt->execute([$nip, $nama_pimpinan, $jabatan]);
    }

    fclose($handle);
    header('Location: data-pimpinan.php');
    exit;
}
?>