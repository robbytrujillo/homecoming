<?php
require '../../includes/db.php'; // Pastikan path ini benar

if (isset($_GET['nama_siswa'])) {
    $nama_siswa = "%" . $_GET['nama_siswa'] . "%";

    try {
        $stmt = $pdo->prepare("SELECT nomor_induk, nama_siswa, kelas, nama_orang_tua FROM siswa WHERE nama_siswa LIKE ?");
        $stmt->execute([$nama_siswa]);
        $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

        header('Content-Type: application/json'); // Pastikan respons dalam format JSON

        if ($result) {
            echo json_encode(["status" => "success", "data" => $result]);
        } else {
            echo json_encode(["status" => "error", "message" => "Tidak ada data ditemukan"]);
        }
    } catch (PDOException $e) {
        echo json_encode(["status" => "error", "message" => $e->getMessage()]);
    }
}
?>
