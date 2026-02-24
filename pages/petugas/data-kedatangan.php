<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'petugas') {
    header('Location: ../../login.php');
    exit;
}

require '../../includes/db.php';

/* ===============================
   EDIT DATA
=================================*/
if (isset($_POST['edit'])) {

    $id = $_POST['id'];
    $nomor_induk = $_POST['nomor_induk'];
    $nama_siswa = $_POST['nama_siswa'];
    $kelas = $_POST['kelas'];
    $keperluan = $_POST['keperluan'];
    $tanggal_datang = $_POST['tanggal_datang'];
    $petugas = $_POST['petugas'];
    $keterangan = $_POST['keterangan'];

    $stmt = $pdo->prepare("UPDATE kedatangan 
        SET nomor_induk=?, 
            nama_siswa=?, 
            kelas=?, 
            keperluan=?, 
            tanggal_datang=?, 
            petugas=?, 
            keterangan=? 
        WHERE id=?");

    $stmt->execute([
        $nomor_induk,
        $nama_siswa,
        $kelas,
        $keperluan,
        $tanggal_datang,
        $petugas,
        $keterangan,
        $id
    ]);

    header('Location: data-kedatangan.php');
    exit;
}

/* ===============================
   HAPUS DATA
=================================*/
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    $stmt = $pdo->prepare("DELETE FROM kedatangan WHERE id=?");
    $stmt->execute([$id]);
    header('Location: data-kedatangan.php');
    exit;
}

/* ===============================
   PAGINATION
=================================*/
$batas = 8;
$halaman = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;

$stmt = $pdo->prepare("SELECT COUNT(*) FROM kedatangan");
$stmt->execute();
$jumlah_data = $stmt->fetchColumn();
$total_halaman = ceil($jumlah_data / $batas);

$stmt = $pdo->prepare("SELECT * FROM kedatangan 
                       ORDER BY tanggal_datang DESC 
                       LIMIT :offset, :batas");
$stmt->bindValue(':offset', $halaman_awal, PDO::PARAM_INT);
$stmt->bindValue(':batas', $batas, PDO::PARAM_INT);
$stmt->execute();
$kedatangan = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Data Kedatangan</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">

    <style>
    .card-custom {
        border-radius: 15px;
        box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
    }

    .badge-kedatangan {
        background: #28a745;
    }

    .badge-ijin {
        background: #ffc107;
        color: #000;
    }
    </style>
</head>

<body>

    <div class="container mt-4 mb-5">

        <h3 class="text-center mb-4">📋 Data Kedatangan Santri</h3>

        <div class="mb-3">
            <a href="dashboard.php" class="btn btn-success rounded-pill">Dashboard</a>
            <a href="form-kedatangan.php" class="btn btn-warning rounded-pill">Input Kedatangan</a>
        </div>

        <input type="text" id="searchInput" class="form-control mb-3" placeholder="Cari Data...">

        <div class="card card-custom">
            <div class="card-body table-responsive">

                <table class="table table-bordered table-hover table-striped" id="dataTable">
                    <thead class="thead-dark text-center">
                        <tr>
                            <th>No</th>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>NIS</th>
                            <th>Kelas</th>
                            <th>Keperluan</th>
                            <th>Petugas</th>
                            <th>Keterangan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="text-center">

                        <?php 
$no = $halaman_awal + 1;
foreach($kedatangan as $row): 
?>

                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= date('d-m-Y H:i', strtotime($row['tanggal_datang'])); ?></td>
                            <td><?= htmlspecialchars($row['nama_siswa']); ?></td>
                            <td><?= htmlspecialchars($row['nomor_induk']); ?></td>
                            <td><?= htmlspecialchars($row['kelas']); ?></td>

                            <td>
                                <?php if($row['keperluan']=="kedatangan"): ?>
                                <span class="badge badge-kedatangan">Kedatangan</span>
                                <?php else: ?>
                                <span class="badge badge-ijin">Ijin</span>
                                <?php endif; ?>
                            </td>

                            <td><?= htmlspecialchars($row['petugas']); ?></td>
                            <td><?= htmlspecialchars($row['keterangan']); ?></td>

                            <td>
                                <button class="btn btn-sm btn-warning rounded-pill" data-toggle="modal"
                                    data-target="#edit<?= $row['id']; ?>">Edit</button>
                                <a href="?hapus=<?= $row['id']; ?>" class="btn btn-sm btn-danger rounded-pill"
                                    onclick="return confirm('Yakin hapus data?')">Hapus</a>
                            </td>
                        </tr>

                        <!-- MODAL EDIT -->
                        <div class="modal fade" id="edit<?= $row['id']; ?>">
                            <div class="modal-dialog">
                                <div class="modal-content">

                                    <div class="modal-header">
                                        <h5>Edit Data</h5>
                                        <button class="close" data-dismiss="modal">&times;</button>
                                    </div>

                                    <div class="modal-body">
                                        <form method="POST">
                                            <input type="hidden" name="id" value="<?= $row['id']; ?>">

                                            <div class="form-group">
                                                <label>Nomor Induk</label>
                                                <input type="text" name="nomor_induk" class="form-control"
                                                    value="<?= $row['nomor_induk']; ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Nama</label>
                                                <input type="text" name="nama_siswa" class="form-control"
                                                    value="<?= $row['nama_siswa']; ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Kelas</label>
                                                <input type="text" name="kelas" class="form-control"
                                                    value="<?= $row['kelas']; ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Keperluan</label>
                                                <select name="keperluan" class="form-control">
                                                    <option value="kedatangan"
                                                        <?= $row['keperluan']=="kedatangan"?'selected':''; ?>>Kedatangan
                                                    </option>
                                                    <option value="ijin"
                                                        <?= $row['keperluan']=="ijin"?'selected':''; ?>>Ijin</option>
                                                </select>
                                            </div>

                                            <div class="form-group">
                                                <label>Tanggal & Jam</label>
                                                <input type="datetime-local" name="tanggal_datang" class="form-control"
                                                    value="<?= date('Y-m-d\TH:i', strtotime($row['tanggal_datang'])); ?>"
                                                    required>
                                            </div>

                                            <div class="form-group">
                                                <label>Petugas</label>
                                                <input type="text" name="petugas" class="form-control"
                                                    value="<?= $row['petugas']; ?>" required>
                                            </div>

                                            <div class="form-group">
                                                <label>Keterangan</label>
                                                <input type="text" name="keterangan" class="form-control"
                                                    value="<?= $row['keterangan']; ?>">
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
        </div>

        <!-- PAGINATION -->
        <nav class="mt-3">
            <ul class="pagination justify-content-center">

                <li class="page-item <?= ($halaman<=1)?'disabled':''; ?>">
                    <a class="page-link" href="?halaman=<?= $halaman-1; ?>">Previous</a>
                </li>

                <?php for($x=1;$x<=$total_halaman;$x++): ?>
                <li class="page-item <?= ($halaman==$x)?'active':''; ?>">
                    <a class="page-link" href="?halaman=<?= $x; ?>"><?= $x; ?></a>
                </li>
                <?php endfor; ?>

                <li class="page-item <?= ($halaman>=$total_halaman)?'disabled':''; ?>">
                    <a class="page-link" href="?halaman=<?= $halaman+1; ?>">Next</a>
                </li>

            </ul>
        </nav>

    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
    $("#searchInput").on("keyup", function() {
        var value = $(this).val().toLowerCase();
        $("#dataTable tbody tr").filter(function() {
            $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1)
        });
    });
    </script>

</body>

</html>