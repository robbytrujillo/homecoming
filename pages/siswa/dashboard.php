<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'siswa') {
    header('Location: ../../login.php');
    exit;
}
require '../../includes/db.php';

// Ambil data siswa yang login
$siswa_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM siswa WHERE id = ?");
$stmt->execute([$siswa_id]);
$siswa = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">

    <!-- Library untuk QR Code -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light container">
        <!-- <a class="navbar-brand" href="#">Aplikasi Pesantren</a> -->
        <img src="../../assets/homecoming-logo.png" style="width: 100px; margin-left: 0.5%; margin-top: 1%">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="dashboard.php"><b>Dashboard</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data_perijinan.php">Data Perijinan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data_kedatangan.php">Data Kedatangan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="form_perijinan_laptop.php">Perijinan Laptop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2 class="mt-5 mb-3">Dashboard Siswa</h2>
        <div class="row">
            <div class="col-md-4">
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Data Perijinan</h5>
                        <p class="card-text">Lihat data perijinan siswa.</p>
                        <a href="data_perijinan.php" class="btn btn-primary">Lihat Data</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Data Kedatangan</h5>
                        <p class="card-text">Lihat data kedatangan siswa.</p>
                        <a href="data_kedatangan.php" class="btn btn-success">Lihat Data</a>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light mb-3">
                    <div class="card-body">
                        <h5 class="card-title">Perijinan Laptop</h5>
                        <p class="card-text">Ajukan perijinan membawa laptop.</p>
                        <a href="form_perijinan_laptop.php" class="btn btn-danger">Ajukan</a>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-6">
                <!-- Card Profil Siswa -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">Profil Siswa</h5>
                    </div>
                    <div class="card-body">
                        <p><strong>Nomor Induk:</strong> <?php echo $siswa['nomor_induk']; ?></p>
                        <p><strong>Nama Siswa:</strong> <?php echo $siswa['nama_siswa']; ?></p>
                        <p><strong>Kelas:</strong> <?php echo $siswa['kelas']; ?></p>
                        <p><strong>Nama Orang Tua:</strong> <?php echo $siswa['nama_orang_tua']; ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <!-- Card QR Code -->
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">QR Code Profil</h5>
                    </div>
                    <div class="card-body text-center">
                        <!-- Tempat untuk menampilkan QR Code -->
                        <div id="qrcode"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <?php include '../../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Script untuk Generate QR Code -->
    <script>
    // Data yang akan dienkripsi ke QR Code
    const dataSiswa = {
        nomor_induk: "<?php echo $siswa['nomor_induk']; ?>",
        nama_siswa: "<?php echo $siswa['nama_siswa']; ?>",
        kelas: "<?php echo $siswa['kelas']; ?>",
        nama_orang_tua: "<?php echo $siswa['nama_orang_tua']; ?>"
    };

    // Konversi data ke format JSON
    const dataString = JSON.stringify(dataSiswa);

    // Generate QR Code
    const qrcode = new QRCode(document.getElementById("qrcode"), {
        text: dataString,
        width: 160,
        height: 160
    });
    </script>
</body>
</html>