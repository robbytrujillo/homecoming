<?php
require '../includes/db.php'; // Pastikan path sesuai

if (isset($_GET['nama_siswa'])) {
    $nama_siswa = $_GET['nama_siswa'];

    $stmt = $pdo->prepare("SELECT nomor_induk, kelas, nama_orang_tua FROM siswa WHERE nama_siswa = ?");
    $stmt->execute([$nama_siswa]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    header('Content-Type: application/json');
    echo json_encode($data ?: ["error" => "Data tidak ditemukan"]);
    exit;
}
?>
