<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'siswa') {
    header('Location: ../../login.php');
    exit;
}
require '../../includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Perijinan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light container">
        <img src="../../assets/pudamah-logo.png" style="width: 100px; margin-left: 2%; margin-top: 1%">
        <!-- <a class="navbar-brand" href="#">Aplikasi Pesantren</a> -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Form Perijinan</a>
                </li>
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
                        <h3 class="text-center">Form Perijinan Pulang</h3>
                    </div>
                    <div class="card-body">
                        <form action="submit_perijinan.php" method="POST">
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
                                    <option value="perpulangan">Perpulangan</option>
                                    <option value="penjengukan">Penjengukan</option>
                                    <option value="kedatangan">Kedatangan</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_pulang">Tanggal Perpulangan</label>
                                <input type="date" class="form-control" id="tanggal_pulang" name="tanggal_pulang" required>
                            </div>
                            <div class="form-group">
                                <label for="tanggal_datang">Tanggal Kedatangan</label>
                                <input type="date" class="form-control" id="tanggal_datang" name="tanggal_datang">
                            </div>
                            <div class="form-group">
                                <label for="petugas">Petugas</label>
                                <input type="text" class="form-control" id="petugas" name="petugas" required>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Submit</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3"></div>

     <!-- Footer -->
     <?php include '../../includes/footer.php'; ?>
</body>
</html>