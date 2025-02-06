<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'petugas') {
    header('Location: ../../login.php');
    exit;
}
require '../../includes/db.php';

// Ambil data petugas yang login
$petugas_id = $_SESSION['user_id'];
$stmtp = $pdo->prepare("SELECT * FROM petugas WHERE id = ?");
$stmtp->execute([$petugas_id]);
$petugas = $stmtp->fetch();

// Proses tambah kedatangan
if (isset($_POST['tambah'])) {
    $nomor_induk = $_POST['nomor_induk'];
    $nama_siswa = $_POST['nama_siswa'];
    $kelas = $_POST['kelas'];
    $nama_orang_tua = $_POST['nama_orang_tua'];
    $keperluan = $_POST['keperluan'];
    $tanggal_datang = $_POST['tanggal_datang'];
    $keterangan = $_POST['keterangan'];

    // Simpan data perijinan
    $stmtp = $pdo->prepare("INSERT INTO kedatangan (nomor_induk, nama_siswa, kelas, nama_orang_tua, keperluan, tanggal_datang, petugas, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtp->execute([$nomor_induk, $nama_siswa, $kelas, $nama_orang_tua, $keperluan, $tanggal_datang, $petugas['nama_petugas'], $keterangan]);
    echo "<script>alert('Data kedatangan berhasil ditambahkan!'); window.location='form_kedatangan.php';</script>";
}

// Ambil Data Siswa
$stmt = $pdo->query("SELECT * FROM siswa");
$siswa = $stmt->fetchAll();
?>
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Kedatangan - Petugas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light container">
        <img src="../../assets/homecoming-logo.png" style="width: 100px; margin-left: 2%; margin-top: 1%">    
        <!-- <a class="navbar-brand" href="#">Aplikasi Pesantren</a> -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <!-- <li class="nav-item active">
                    <a class="nav-link" href="data_perijinan.php">Data Perijinan</a>
                </li> -->
                <li class="nav-item active">
                    <a style="color: blue;" class="nav-link" href="form_kedatangan.php"><b>Form Kedatangan</b></a>
                </li>
                <li class="nav-item ">
                    <a class="nav-link" href="data_kedatangan.php">Data kedatangan</a>
                </li>
                <!-- <li class="nav-item active">
                    <a class="nav-link" href="form_perijinan.php">Form Perpulangan</a>
                </li> -->
                
                <li class="nav-item">
                    <a class="nav-link" href="../../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <div class="row">
            <div class="col-md-6 offset-md-3">                
                <div class="card">
                    <div class="card-header">
                        <h3 class="text-center">Form Kedatangan</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="nomor_induk">Nomor Induk Siswa</label>
                                <input type="text" class="form-control" id="nomor_induk" name="nomor_induk" required>
                            </div>
                            <div class="form-group">
                                <label for="nama_siswa">Nama Siswa</label>
                                <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" required>
                            </div>
                            <div class="form-group">
                                <label for="kelas">Kelas</label>
                                <input type="text" class="form-control" id="kelas" name="kelas" required>
                            </div>
                            <div class="form-group">
                                <label for="nama_orang_tua">Nama Orang Tua</label>
                                <input type="text" class="form-control" id="nama_orang_tua" name="nama_orang_tua" required>
                            </div>
                            <div class="form-group">
                                <label for="keperluan">Keperluan</label>
                                <select class="form-control" id="keperluan" name="keperluan" required>
                                    <option value="kedatangan">Kedatangan</option>
                                    <option value="ijin">Ijin</option>
                                    <!-- <option value="kedatangan">Kedatangan</option> -->
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_datang">Tanggal Kedatangan</label>
                                <input type="date" class="form-control" id="tanggal_datang" name="tanggal_datang" required>
                            </div>
                            <div class="form-group">
                                <label for="petugas">Petugas</label>
                                <input type="text" class="form-control" id="petugas" name="petugas" required>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                            </div>
                            <button type="submit" name="tambah" class="btn btn-success">Submit</button>
                            <a href="data_kedatangan.php">Data Kedatangan</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>        
    </div>

    <!-- Footer -->
    <?php include '../../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>