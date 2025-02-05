<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'siswa') {
    header('Location: ../../login.php');
    exit;
}
require '../../includes/db.php';

// Ambil data perijinan siswa yang login
$siswa_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM perijinan WHERE nomor_induk = (SELECT nomor_induk FROM siswa WHERE id = ?)");
$stmt->execute([$siswa_id]);
$perijinan = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Perijinan - Siswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light container">
        <!-- <a class="navbar-brand" href="#">Aplikasi Pesantren</a> -->
        <img src="../../assets/homecoming-logo.png" style="width: 100px; margin-left: 1%; margin-top: 1%">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item active">
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
        <h2 class="mt-5 mb-3">Data Perijinan</h2>

        <!-- Input Pencarian -->
        <div class="form-group">
            <input type="text" id="searchInput" class="form-control" style="width: 200px; margin-left: 82%; margin-top: 1%" placeholder="Cari Data Tabel"><i class="fas fa-search" style="position: absolute"></i>
        </div>

        <table class="table table-bordered" id="dataTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Induk</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Keperluan</th>
                    <th>Tanggal Pulang</th>
                    <th>Petugas</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($perijinan as $key => $row): ?>
                <tr>
                    <td><?php echo $key + 1; ?></td>
                    <td><?php echo $row['nomor_induk']; ?></td>
                    <td><?php echo $row['nama_siswa']; ?></td>
                    <td><?php echo $row['kelas']; ?></td>
                    <td><?php echo $row['keperluan']; ?></td>
                    <td><?php echo $row['tanggal_pulang']; ?></td>
                    <td><?php echo $row['petugas']; ?></td>
                    <td><?php echo $row['keterangan']; ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <?php include '../../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    
    <!-- Script Pencarian -->
    <script>
    $(document).ready(function() {
        // Fungsi pencarian
        $("#searchInput").on("keyup", function() {
            var value = $(this).val().toLowerCase(); // Ambil nilai input dan ubah ke lowercase
            $("#dataTable tbody tr").filter(function() {
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1); // Tampilkan/sembunyikan baris yang sesuai
            });
        });
    });
    </script>

    

</body>
</html>