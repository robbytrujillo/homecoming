<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'petugas') {
    header('Location: ../../login.php');
    exit;
}
require '../../includes/db.php';

// Tambah Data Petugas
// if (isset($_POST['tambah'])) {
//     $nip = $_POST['nip'];
//     $nama_petugas = $_POST['nama_petugas'];
//     $jabatan = $_POST['jabatan'];
//     $mapel = $_POST['mapel'];

//     $stmt = $pdo->prepare("INSERT INTO petugas (nip, nama_petugas, jabatan, mapel) VALUES (?, ?, ?, ?)");
//     $stmt->execute([$nip, $nama_petugas, $jabatan, $mapel]);
//     header('Location: data_petugas.php');
//     exit;
// }

// Edit Data Perijinan
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nomor_induk = $_POST['nomor_induk'];
    $nama_siswa = $_POST['nama_siswa'];
    $kelas = $_POST['kelas'];
    $keperluan = $_POST['keperluan'];
    $tanggal_pulang = $_POST['tanggal_datang'];
    $petugas = $_POST['petugas'];
    $keterangan = $_POST['keterangan'];

    $stmt = $pdo->prepare("UPDATE kedatangan SET nomor_induk= ?, nama_siswa = ?, kelas = ?, keperluan = ?, tanggal_datang = ?, petugas = ?, keterangan = ? WHERE id = ?");
    $stmt->execute([$nip, $nama_petugas, $jabatan, $mapel, $id]);
    header('Location: data_kedatangan.php');
    exit;
}

// Hapus Data Perijinan
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM kedatangan WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: data_kedatangan.php');
    exit;
}

// Ambil Data Perijinan
$stmt = $pdo->query("SELECT * FROM kedatangan");
$kedatangan = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Datang - Petugas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light container">
        <img src="../../assets/homecoming-logo.png" style="width: 100px; margin-left: 1%; margin-top: 1%">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="data_perijinan.php">Data Perijinan</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link" href="form_kedatangan.php">Form Kedatangan</a>
                </li>
                <li class="nav-item active">
                    <a style="color: blue" class="nav-link" href="data_kedatangan.php"><b>Data Kedatangan</b></a>
                </li>
                <!-- <li class="nav-item active">
                    <a class="nav-link" href="form_perijinan.php">Input Perijinan</a>
                </li> -->
                
                <li class="nav-item">
                    <a class="nav-link" href="../../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Data Kedatangan</h2>
        <!-- <div class="mb-3">
            <button class="btn btn-primary" data-toggle="modal" data-target="#tambahPetugasModal">Tambah Petugas</button>
            <button class="btn btn-success" data-toggle="modal" data-target="#uploadCSVModal">Upload CSV</button>
            <a href="template_petugas.csv" class="btn btn-secondary" download>Download Template CSV</a>
        </div> -->

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
            <input type="text" id="searchInput" class="form-control" style="width: 200px; margin-left: 82%; margin-top: 1%" placeholder="Cari Data Tabel"><i class="fas fa-search" style="position: absolute"></i>
        </div>

        <!-- Tabel Data Petugas -->
        <table class="table table-bordered" id="dataTable">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nomor Induk</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <!-- <th>Nama Orang Tua</th> -->
                    <!-- <th>Keperluan</th> -->
                    <th>Tanggal Datang</th>
                    <th>Petugas</th>
                    <th>Keterangan</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($kedatangan as $key => $row): ?>
                <tr>
                    <td><?php echo $key + 1; ?></td>
                    <td><?php echo $row['nomor_induk']; ?></td>
                    <td><?php echo $row['nama_siswa']; ?></td>
                    <td><?php echo $row['kelas']; ?></td>
                    <!-- <td><?php echo $row['nama_orang_tua']; ?></td> -->
                    <!-- <td><?php echo $row['keperluan']; ?></td> -->
                    <td><?php echo $row['tanggal_datang']; ?></td>
                    <td><?php echo $row['petugas']; ?></td>
                    <td><?php echo $row['keterangan']; ?></td>
                    <td>
                        <a href="form_kedatangan.php" class="btn btn-primary text-light btn-sm">Isi Kedatangan</a>
                    </td>
                </tr>

                <!-- Modal Edit Petugas -->
                <div class="modal fade" id="editPerijinanModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editPerijinanModalLabel" aria-hidden="true">
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
                                        <input type="text" class="form-control" id="nomor_induk" name="nomor_induk" value="<?php echo $row['nomor_induk']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_siswa">Nama Siswa</label>
                                        <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" value="<?php echo $row['nama_siswa']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="kelas">Kelas</label>
                                        <input type="text" class="form-control" id="kelas" name="kelas" value="<?php echo $row['kelas']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_orang_tua">Nama Orang Tua</label>
                                        <input type="text" class="form-control" id="nama_orang_tua" name="nama_orang_tua" value="<?php echo $row['nama_orang_tua']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="keperluan">Keperluan</label>
                                        <input type="text" class="form-control" id="keperluan" name="keperluan" value="<?php echo $row['keperluan']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="tanggal_datang">Tanggal Datang</label>
                                        <input type="text" class="form-control" id="tanggal_datang" name="tanggal_datang" value="<?php echo $row['tanggal_datang']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="petugas">Petugas</label>
                                        <input type="text" class="form-control" id="petugas" name="petugas" value="<?php echo $row['petugas']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <input type="text" class="form-control" id="keterangan" name="keterangan" value="<?php echo $row['keterangan']; ?>" required>
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
                $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1); // Tampilkan/sembunyikan baris yang sesuai
            });
        });
    });
    </script>
</body>
</html>