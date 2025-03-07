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

// Proses tambah perijinan laptop
if (isset($_POST['tambah'])) {
    $nomor_induk = $siswa['nomor_induk'];
    $nama_siswa = $siswa['nama_siswa'];
    $kelas = $siswa['kelas'];
    $tanggal_pengambilan = $_POST['tanggal_pengambilan'];
    $perijinan = $_POST['perijinan'];
    // $tanggal_pengembalian = $_POST['tanggal_pengembalian'];
    $alasan_membawa_laptop = $_POST['alasan_membawa_laptop'];
    // $persetujuan = $_POST['persetujuan'];

    // Simpan data perijinan laptop
    $stmt = $pdo->prepare("INSERT INTO perijinan_laptop (nomor_induk, nama_siswa, kelas, tanggal_pengambilan, perijinan, alasan_membawa_laptop) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nomor_induk, $nama_siswa, $kelas, $tanggal_pengambilan, $perijinan, $alasan_membawa_laptop]);
    echo "<script>alert('Perijinan laptop berhasil diajukan!'); window.location='data-perijinan-laptop.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Perijinan Laptop - Siswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light container sticky-top">
        <!-- <a class="navbar-brand" href="#">Aplikasi Pesantren</a> -->
        <img src="../../assets/homecoming-logo.png" style="width: 150px; margin-left: 0%; margin-top: 0%">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="data-perijinan.php">Data Perijinan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data-kedatangan.php">Data Kedatangan</a>
                </li> -->
                <li class="nav-item active">
                    <a style="color: #28A745" class="nav-link" href="form-perijinan-laptop.php"><b>Input Perijinan Laptop</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4 mb-5 rounded">
        <div class="row">
            <div class="col-md-6 offset-md-3">
                <div class="card">
                    <div class="card-header">
                            <h3 class="text-center">Perijinan Membawa Laptop</h3>
                    </div>
                    <div class="card-body">
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="nomor_induk">Nomor Induk Siswa</label>
                                <input type="text" class="form-control" id="nomor_induk" name="nomor_induk" value="<?php echo $siswa['nomor_induk']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="nama_siswa">Nama Siswa</label>
                                <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" value="<?php echo $siswa['nama_siswa']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="kelas">Kelas</label>
                                <input type="text" class="form-control" id="kelas" name="kelas" value="<?php echo $siswa['kelas']; ?>" readonly>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_pengambilan">Tanggal Pengambilan</label>
                                <input type="date" class="form-control" id="tanggal_pengambilan" name="tanggal_pengambilan" required>
                            </div>
                            <div class="form-group">
                                <label for="perijinan">Perijinan</label>
                                <select class="form-control" id="perijinan" name="perijinan" required>
                                    <?php
                                    // Ambil data pimpinan dari database
                                    $stmt = $pdo->query("SELECT * FROM pimpinan");
                                    while ($row = $stmt->fetch()) {
                                        echo "<option value='{$row['nama_pimpinan']}'>{$row['nama_pimpinan']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <!-- <div class="form-group">
                                <label for="tanggal_pengembalian">Tanggal Pengembalian</label>
                                <input type="date" class="form-control" id="tanggal_pengembalian" name="tanggal_pengembalian" disabled>
                            </div> -->
                            <div class="form-group">
                                <label for="alasan_membawa_laptop">Alasan Ijin Membawa Laptop</label>
                                <textarea class="form-control" id="alasan_membawa_laptop" name="alasan_membawa_laptop" rows="3" required></textarea>
                            </div>
                            <!-- <div class="form-group">
                                <label for="persetujuan">Persetujuan</label>
                                <select class="form-control" id="persetujuan" name="persetujuan" required>
                                    <option value="Belum">Belum</option>
                                    <option value="Sudah">Sudah</option> -->
                                    <?php
                                    // Ambil data pimpinan dari database untuk persetujuan
                                    // $stmt = $pdo->query("SELECT * FROM pimpinan");
                                    // while ($row = $stmt->fetch()) {
                                    //     echo "<option value='{$row['nama_pimpinan']}'>{$row['nama_pimpinan']}</option>";
                                    // }
                                    ?>
                                <!-- </select>
                            </div> -->
                            <button type="submit" name="tambah" class="btn btn-success rounded-pill">Ajukan Perijinan</button>
                            <a href="data-perijinan-laptop.php" type="button" name="data_perijinan_laptop" class="btn btn-info rounded-pill">Lihat Data</a>
                            <a href="dashboard.php" type="button" name="kembali ke dashboard" class="btn btn-danger rounded-pill">Kembali</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!-- <h2>Form Perijinan Laptop</h2> -->
       
    </div>
    <br>

    <!-- Footer -->
    <?php include '../../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>