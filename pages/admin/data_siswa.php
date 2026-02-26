<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../../login.php');
    exit;
}
require '../../includes/db.php';

// Tambah Data Siswa
if (isset($_POST['tambah'])) {
    $nomor_induk = $_POST['nomor_induk'];
    $nama_siswa = $_POST['nama_siswa'];
    $kelas = $_POST['kelas'];
    $alamat = $_POST['alamat'];
    $nama_orang_tua = $_POST['nama_orang_tua'];

    $stmt = $pdo->prepare("INSERT INTO siswa (nomor_induk, nama_siswa, kelas, alamat, nama_orang_tua) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$nomor_induk, $nama_siswa, $kelas, $alamat, $nama_orang_tua]);
    header('Location: data_siswa.php');
    exit;
}

// Edit Data Siswa
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nomor_induk = $_POST['nomor_induk'];
    $nama_siswa = $_POST['nama_siswa'];
    $kelas = $_POST['kelas'];
    $alamat = $_POST['alamat'];
    $nama_orang_tua = $_POST['nama_orang_tua'];

    $stmt = $pdo->prepare("UPDATE siswa SET nomor_induk = ?, nama_siswa = ?, kelas = ?, alamat = ?, nama_orang_tua = ? WHERE id = ?");
    $stmt->execute([$nomor_induk, $nama_siswa, $kelas, $alamat, $nama_orang_tua, $id]);
    header('Location: data_siswa.php');
    exit;
}

// Hapus Data Siswa
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM siswa WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: data_siswa.php');
    exit;
}

// Ambil Data Siswa
$stmt = $pdo->query("SELECT * FROM siswa");
$siswa = $stmt->fetchAll();

// pagination
$batas = 10;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

// Hitung total data
$stmt = $pdo->prepare("SELECT COUNT(*) FROM siswa");
$jumlah_data = $stmt->fetchColumn();
$total_halaman = ceil($jumlah_data / $batas);

// Ambil Data Siswa Dengan Pagination
$stmt = $pdo->prepare("SELECT * FROM siswa LIMIT :offset, :batas");
$stmt->bindValue(':offset', $halaman_awal, PDO::PARAM_INT);
$stmt->bindValue(':batas', $batas, PDO::PARAM_INT);
$stmt->execute();
$siswa = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Siswa - Admin</title>
    <link rel="icon" type="image/x-icon" href="../../assets/img/ihbs-logo.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light container sticky-top">
        <img src="../../assets/homecoming-logo.png" style="width: 150px; margin-left: 0%; margin-top: 0.5%">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav"
            aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item active">
                    <a style="color: #28A745;" class="nav-link font-weight-bold" href="data_siswa.php">Data Siswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-3 mb-6">
        <h2 class="mt-3 mb-3 text-center">Data Siswa</h2>
        <button class="btn btn-primary rounded-pill" data-toggle="modal" data-target="#tambahSiswaModal">Tambah
            Siswa</button>
        <button class="btn btn-success rounded-pill" data-toggle="modal" data-target="#uploadCSVModal">Upload
            CSV</button>
        <a href="template_siswa.csv" class="btn btn-secondary rounded-pill" download>Template CSV</a>
        <a href="export-siswa.php" class="btn btn-info rounded-pill">Print</a>

        <!-- Input Pencarian -->
        <div class="form-group">
            <input type="text" id="searchInput" class="form-control"
                style="width: 200px; margin-left: 82%; margin-top: 1%" placeholder="Cari Data Tabel"><i
                class="fas fa-search" style="position: absolute"></i>
        </div>

        <table class="table table-bordered rounded-pill" id="dataTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Induk</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>Alamat</th>
                    <th>Nama Orang Tua</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $nomor = $halaman_awal + 1;
                
                // foreach ($siswa as $key => $row): 
                foreach ($siswa as $row):
                
                ?>
                <tr>
                    <!-- <td><?php echo $key + 1; ?></td>
                    <td><?php echo $row['nomor_induk']; ?></td>
                    <td><?php echo $row['nama_siswa']; ?></td>
                    <td><?php echo $row['kelas']; ?></td>
                    <td><?php echo $row['alamat']; ?></td>
                    <td><?php echo $row['nama_orang_tua']; ?></td> -->

                    <td><?= $nomor++; ?></td>
                    <td><?= htmlspecialchars($row['nomor_induk']); ?></td>
                    <td><?= htmlspecialchars($row['nama_siswa']); ?></td>
                    <td><?= htmlspecialchars($row['kelas']); ?></td>
                    <td><?= htmlspecialchars($row['alamat']); ?></td>
                    <td><?= htmlspecialchars($row['nama_orang_tua']); ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm rounded-pill" data-toggle="modal"
                            data-target="#editSiswaModal<?php echo $row['id']; ?>"><i
                                class="bi bi-pencil-square"></i>Edit</button>
                        <a href="data_siswa.php?hapus=<?php echo $row['id']; ?>"
                            class="btn btn-danger btn-sm rounded-pill"
                            onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>

                <!-- Modal Edit Siswa -->
                <div class="modal fade" id="editSiswaModal<?php echo $row['id']; ?>" tabindex="-1"
                    aria-labelledby="editSiswaModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editSiswaModalLabel">Edit Siswa</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <div class="form-group">
                                        <label for="nomor_induk">Nomor Induk</label>
                                        <input type="text" class="form-control" id="nomor_induk" name="nomor_induk"
                                            value="<?php echo $row['nomor_induk']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_siswa">Nama Siswa</label>
                                        <input type="text" class="form-control" id="nama_siswa" name="nama_siswa"
                                            value="<?php echo $row['nama_siswa']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kelas">Kelas</label>
                                        <input type="text" class="form-control" id="kelas" name="kelas"
                                            value="<?php echo $row['kelas']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="alamat">Alamat</label>
                                        <input type="text" class="form-control" id="alamat" name="alamat"
                                            value="<?php echo $row['alamat']; ?>">
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_orang_tua">Nama Orang Tua</label>
                                        <input type="text" class="form-control" id="nama_orang_tua"
                                            name="nama_orang_tua" value="<?php echo $row['nama_orang_tua']; ?>"
                                            required>
                                    </div>
                                    <button type="submit" name="edit" class="btn btn-primary">Simpan</button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </tbody>
        </table>

        <!-- Pagination -->
        <nav class="mb-5">
            <ul class="pagination">
                <li class="page-item <?= ($halaman <= 1) ? 'active' : ''; ?>">
                    <a class="page-link" href="?halaman=<?= $halaman - 1; ?>">Previous</a>
                </li>
                <?php for ($x = 1; $x <= $total_halaman; $x++): ?>
                <li class="page-item <?= ($halaman == $x) ? 'active' : ''; ?>">
                    <a class="page-link" href="?halaman=<?= $x; ?>"><?= $x; ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?= ($halaman >= $total_halaman) ? 'active' : ''; ?>">
                    <a class="page-link" href="?halaman=<?= $halaman + 1; ?>">Next</a>
                </li>
            </ul>

            <!-- <ul class="pagination">
                <li class="page-item"><a class="page-link" href="?halaman=1">1</a></li>
                <li class="page-item"><a class="page-link" href="?halaman=2">2</a></li>
            </ul> -->

        </nav>
    </div>

    <!-- Modal Tambah Siswa -->
    <div class="modal fade" id="tambahSiswaModal" tabindex="-1" aria-labelledby="tambahSiswaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahSiswaModalLabel">Tambah Siswa</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="nomor_induk">Nomor Induk</label>
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
                            <label for="alamat">Alamat</label>
                            <input type="text" class="form-control" id="alamat" name="alamat">
                        </div>
                        <div class="form-group">
                            <label for="nama_orang_tua">Nama Orang Tua</label>
                            <input type="text" class="form-control" id="nama_orang_tua" name="nama_orang_tua" required>
                        </div>
                        <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Upload CSV -->
    <div class="modal fade" id="uploadCSVModal" tabindex="-1" aria-labelledby="uploadCSVModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="uploadCSVModalLabel">Upload Data Siswa dari CSV</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="upload_csv.php" method="POST" enctype="multipart/form-data">
                        <div class="form-group">
                            <label for="csv_file">Pilih File CSV</label>
                            <input type="file" class="form-control-file" id="csv_file" name="csv_file" accept=".csv"
                                required>
                        </div>
                        <button type="submit" name="upload_csv" class="btn btn-primary">Upload</button>
                    </form>
                </div>
            </div>
        </div>
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
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -
                1); // Tampilkan/sembunyikan baris yang sesuai
            });
        });
    });
    </script>
</body>

</html>