<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'petugas') {
    header('Location: ../../login.php');
    exit;
}
require '../../includes/db.php';

// Edit Data Perijinan
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nomor_induk = $_POST['nomor_induk'];
    $nama_siswa = $_POST['nama_siswa'];
    $kelas = $_POST['kelas'];
    $tanggal_pengembalian = $_POST['tanggal_pengembalian'];
    $petugas = $_POST['petugas'];
    $keterangan = $_POST['keterangan'];

    // $stmt = $pdo->prepare("UPDATE pengembalian_laptop SET nomor_induk= ?, nama_siswa = ?, kelas = ?, tanggal_pengembalian = ?, petugas = ?, keterangan = ? WHERE id = ?");
    // $stmt->execute([$nomor_induk, $nama_petugas, $jabatan, $mapel, $id]);

     $stmt = $pdo->prepare("UPDATE pengembalian_laptop 
        SET nomor_induk=?, 
            nama_siswa=?, 
            kelas=?,  
            tanggal_pengembalian=?, 
            petugas=?, 
            keterangan=? 
        WHERE id=?");

    $stmt->execute([
        $nomor_induk,
        $nama_siswa,
        $kelas,
        $tanggal_pengembalian,
        $petugas,
        $keterangan,
        $id
    ]);

    header('Location: data-pengembalian-laptop.php');
    exit;
}

// Hapus Data Perijinan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM pengembalian_laptop WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: data-pengembalian-laptop.php');
    exit;
}

// pagination
$batas = 5;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

// Hitung total data
$stmt = $pdo->prepare("SELECT COUNT(*) FROM pengembalian_laptop");
$jumlah_data = $stmt->fetchColumn();
$total_halaman = ceil($jumlah_data / $batas);

// Ambil Data Perijinan Dengan Pagination
$stmt = $pdo->prepare("SELECT * FROM pengembalian_laptop ORDER BY tanggal_pengembalian DESC LIMIT :offset, :batas");
$stmt->bindValue(':offset', $halaman_awal, PDO::PARAM_INT);
$stmt->bindValue(':batas', $batas, PDO::PARAM_INT);
$stmt->execute();
$perijinan = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Perijinan - Petugas</title>
    <link rel="icon" type="image/x-icon" href="../assets/img/ihbs-logo.png">
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
                    <a style="color: #28A745;" class="nav-link" href="data-pengembalian-laptop.php"><b>Data Pengembalian
                            Laptop</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="form-pengembalian-laptop.php">Input Pengembalian Laptop</a>
                </li>
                <!-- <li class="nav-item active">
                    <a class="nav-link" href="data_kedatangan.php">Data Kedatangan</a>
                </li> -->
                <!-- <li class="nav-item active">
                    <a class="nav-link" href="form_kedatangan.php">Input Kedatangan</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link" href="../../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-3 mb-3">
        <h2 class="mt-3 mb-3 text-center">Data Pengembalian Laptop</h2>
        <div>
            <a href="dashboard.php" class="btn btn-success btn-md text-white rounded-pill">Kembali</a>
            <a href="form-pengembalian-laptop.php" class="btn btn-warning btn-md rounded-pill">Input Pengembalian
                Laptop</a>
            <!-- <button class="btn btn-success rounded-pill" data-toggle="modal" data-target="#uploadCSVModal">Upload CSV</button>
            <a href="template_petugas.csv" class="btn btn-secondary rounded-pill" download>Download Template CSV</a> -->
        </div>

        <!-- Modal Upload CSV -->
        <!-- <div class="modal fade" id="uploadCSVModal" tabindex="-1" aria-labelledby="uploadCSVModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="uploadCSVModalLabel">Upload Data Petugas dari CSV</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <form action="upload_csv_petugas.php" method="POST" enctype="multipart/form-data">
                            <div class="form-group">
                                <label for="csv_file">Pilih File CSV</label>
                                <input type="file" class="form-control-file" id="csv_file" name="csv_file" accept=".csv" required>
                            </div>
                            <button type="submit" name="upload_csv" class="btn btn-primary">Upload</button>
                           <div class="form-group">
                                <input type="text" id="searchInput" class="form-control" placeholder="Cari berdasarkan NIP, Nama Petugas, Jabatan, atau Mapel...">
                            </div> 
                        </form>
                        
                    </div>
                </div>
            </div>
        </div> -->

        <!-- Input Pencarian -->
        <div class="form-group">
            <input type="text" id="searchInput" class="form-control"
                style="width: 200px; margin-left: 82%; margin-top: 1%" placeholder="Cari Data Tabel"><i
                class="fas fa-search" style="position: absolute"></i>
        </div>

        <!-- Tabel Data Petugas -->
        <table class="table table-bordered" id="dataTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Pengembalian</th>
                    <th>Waktu</th>
                    <th>Nama Siswa</th>
                    <th>Nomor Induk</th>
                    <th>Kelas</th>
                    <th>Petugas</th>
                    <th>Keterangan</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $nomor = $halaman_awal + 1;
                
                // foreach ($perijinan as $key => $row): 
                foreach ($perijinan as $row): 
                ?>
                <tr>
                    <!-- <td><?php echo $key + 1; ?></td> -->
                    <td><?= $nomor++; ?></td>
                    <!-- <td><?= $row['nomor_induk']; ?></td> -->
                    <!-- <td><?= htmlspecialchars($row['tanggal_pulang']); ?></td>                     -->
                    <td><?= date('d F Y', strtotime($row['tanggal_pengembalian'])); ?></td>
                    <td><?php echo substr($row['tanggal_pengembalian'], 11, 5) ?></td>
                    <td><?= htmlspecialchars($row['nama_siswa']); ?></td>
                    <td><?= htmlspecialchars($row['nomor_induk']); ?></td>
                    <td><?= htmlspecialchars($row['kelas']); ?></td>
                    <!-- <td><?= $row['nama_orang_tua']; ?></td> -->
                    <!-- <td><?= htmlspecialchars($row['keperluan']); ?></td>                     -->
                    <td><?= htmlspecialchars($row['petugas']); ?></td>
                    <td><?= htmlspecialchars($row['keterangan']); ?></td>
                    <!-- <td>
                        <button class="btn btn-warning btn-sm rounded-pill" data-toggle="modal" data-target="#editPetugasModal<?php echo $row['id']; ?>">Edit</button>
                        <a href="data_petugas.php?hapus=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td> -->
                </tr>



                <!-- Modal Edit Petugas -->
                <div class="modal fade" id="editPerijinanModal<?php echo $row['id']; ?>" tabindex="-1"
                    aria-labelledby="editPerijinanModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPerijinanModalLabel">Edit Perijinan</h5>
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
                            </div>
                            <div class="form-group">
                                <label for="tanggal_pengembalian">Tanggal Pengembalian</label>
                                <input type="text" class="form-control" id="tanggal_pengembalian"
                                    name="tanggal_pengembalian" value="<?php echo $row['tanggal_pengembalian']; ?>"
                                    required>
                            </div>
                            <div class="form-group">
                                <label for="petugas">Petugas</label>
                                <input type="text" class="form-control" id="petugas" name="petugas"
                                    value="<?php echo $row['petugas']; ?>" required>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <input type="text" class="form-control" id="keterangan" name="keterangan"
                                    value="<?php echo $row['keterangan']; ?>" required>
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

    <!-- Modal Tambah Petugas -->
    <!-- <div class="modal fade" id="tambahPetugasModal" tabindex="-1" aria-labelledby="tambahPetugasModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="tambahPetugasModalLabel">Tambah Petugas</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="" method="POST">
                        <div class="form-group">
                            <label for="nip">NIP</label>
                            <input type="text" class="form-control" id="nip" name="nip" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_petugas">Nama Petugas</label>
                            <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" required>
                        </div>
                        <div class="form-group">
                            <label for="jabatan">Jabatan</label>
                            <input type="text" class="form-control" id="jabatan" name="jabatan" required>
                        </div>
                        <div class="form-group">
                            <label for="mapel">Mata Pelajaran</label>
                            <input type="text" class="form-control" id="mapel" name="mapel" required>
                        </div>
                        <button type="submit" name="tambah" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div> -->

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