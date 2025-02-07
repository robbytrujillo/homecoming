<?php
$host = "localhost";
$user = "root";
$password = "";
$database = "homecoming";  // Ganti sesuai database Anda

$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

if (isset($_GET['nama_siswa'])) {
    $nama_siswa = $_GET['nama_siswa'];
    $query = "SELECT nama_siswa, nomor_induk, kelas FROM siswa WHERE nama_siswa LIKE '%$nama_siswa%' LIMIT 5";
    $result = $conn->query($query);

    $data = [];
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    echo json_encode($data);
}

$conn->close();
?>
