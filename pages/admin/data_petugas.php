<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../../login.php');
    exit;
}
require '../../includes/db.php';

// Tambah Data Petugas
if (isset($_POST['tambah'])) {
    $nip = $_POST['nip'];
    $nama_petugas = $_POST['nama_petugas'];
    $jabatan = $_POST['jabatan'];
    $mapel = $_POST['mapel'];

    $stmt = $pdo->prepare("INSERT INTO petugas (nip, nama_petugas, jabatan, mapel) VALUES (?, ?, ?, ?)");
    $stmt->execute([$nip, $nama_petugas, $jabatan, $mapel]);
    header('Location: data_petugas.php');
    exit;
}

// Edit Data Petugas
if (isset($_POST['edit'])) {
    $id = $_POST['id'];
    $nip = $_POST['nip'];
    $nama_petugas = $_POST['nama_petugas'];
    $jabatan = $_POST['jabatan'];
    $mapel = $_POST['mapel'];

    $stmt = $pdo->prepare("UPDATE petugas SET nip= ?, nama_petugas = ?, jabatan = ?, mapel = ? WHERE id = ?");
    $stmt->execute([$nip, $nama_petugas, $jabatan, $mapel, $id]);
    header('Location: data_petugas.php');
    exit;
}

// Hapus Data Petugas
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM petugas WHERE id = ?");
    $stmt->execute([$id]);
    header('Location: data_petugas.php');
    exit;
}

// Ambil Data Petugas
$stmt = $pdo->query("SELECT * FROM petugas");
$petugas = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Petugas - Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light container">
        <img src="../../assets/pudamah-logo.png" style="width: 100px; margin-left: 1%; margin-top: 1%">
        <!-- <a class="navbar-brand" href="#">Aplikasi Pesantren</a> -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data_siswa.php">Data Siswa</a>
                </li>
                <li class="nav-item active">
                    <a class="nav-link" href="data_petugas.php">Data Petugas</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <h2>Data Petugas</h2>
        <div class="mb-3">
            <button class="btn btn-primary" data-toggle="modal" data-target="#tambahPetugasModal">Tambah Petugas</button>
            <button class="btn btn-success" data-toggle="modal" data-target="#uploadCSVModal">Upload CSV</button>
            <a href="template_petugas.csv" class="btn btn-secondary" download>Download Template CSV</a>
        </div>

        <!-- Modal Upload CSV -->
        <div class="modal fade" id="uploadCSVModal" tabindex="-1" aria-labelledby="uploadCSVModalLabel" aria-hidden="true">
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
                                <!-- <input type="text" id="searchInput" class="form-control" placeholder="Cari berdasarkan NIP, Nama Petugas, Jabatan, atau Mapel..."> -->
                            </div>
                            <button type="submit" name="upload_csv" class="btn btn-primary">Upload</button>
                           
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Input Pencarian -->
        <div class="form-group">
            <input type="text" id="searchInput" class="form-control" placeholder="Cari berdasarkan NIP, Nama Petugas, Jabatan, atau Mapel...">
        </div>

        <!-- Tabel Data Petugas -->
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>No</th>
                    <th>NIP</th>
                    <th>Nama Petugas</th>
                    <th>Jabatan</th>
                    <th>Mata Pelajaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($petugas as $key => $row): ?>
                <tr>
                    <td><?php echo $key + 1; ?></td>
                    <td><?php echo $row['nip']; ?></td>
                    <td><?php echo $row['nama_petugas']; ?></td>
                    <td><?php echo $row['jabatan']; ?></td>
                    <td><?php echo $row['mapel']; ?></td>
                    <td>
                        <button class="btn btn-warning btn-sm" data-toggle="modal" data-target="#editPetugasModal<?php echo $row['id']; ?>">Edit</button>
                        <a href="data_petugas.php?hapus=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin menghapus?')">Hapus</a>
                    </td>
                </tr>

                <!-- Modal Edit Petugas -->
                <div class="modal fade" id="editPetugasModal<?php echo $row['id']; ?>" tabindex="-1" aria-labelledby="editPetugasModalLabel" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title" id="editPetugasModalLabel">Edit Petugas</h5>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                    <span aria-hidden="true">&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form action="" method="POST">
                                    <input type="hidden" name="id" value="<?php echo $row['id']; ?>">
                                    <div class="form-group">
                                        <label for="nip">NIP</label>
                                        <input type="text" class="form-control" id="nip" name="nip" value="<?php echo $row['nip']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="nama_petugas">Nama Petugas</label>
                                        <input type="text" class="form-control" id="nama_petugas" name="nama_petugas" value="<?php echo $row['nama_petugas']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="jabatan">Jabatan</label>
                                        <input type="text" class="form-control" id="jabatan" name="jabatan" value="<?php echo $row['jabatan']; ?>" required>
                                    </div>
                                    <div class="form-group">
                                        <label for="mapel">Mata Pelajaran</label>
                                        <input type="text" class="form-control" id="mapel" name="mapel" value="<?php echo $row['mapel']; ?>" required>
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
    <div class="modal fade" id="tambahPetugasModal" tabindex="-1" aria-labelledby="tambahPetugasModalLabel" aria-hidden="true">
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
    </div>

    <!-- Footer -->
    <?php include '../../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- search -->
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