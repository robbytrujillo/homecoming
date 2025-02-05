<?php
require '../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nama_siswa = $_POST['nama_siswa'] ?? '';
    $nomor_induk = $_POST['nomor_induk'] ?? '';
    $kelas = $_POST['kelas'] ?? '';
    $nama_orang_tua = $_POST['nama_orang_tua'] ?? '';

    if ($nama_siswa && $nomor_induk && $kelas && $nama_orang_tua) {
        $stmt = $pdo->prepare("INSERT INTO perizinan (nama_siswa, nomor_induk, kelas, nama_orang_tua) VALUES (?, ?, ?, ?)");
        $stmt->execute([$nama_siswa, $nomor_induk, $kelas, $nama_orang_tua]);

        echo "Data perizinan berhasil disimpan!";
    } else {
        echo "Semua data harus diisi!";
    }
}
?>
