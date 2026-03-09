<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'siswa') {
    header('Location: ../../login.php');
    exit;
}
require '../../includes/db.php';

// Ambil ID siswa dari sesi
$siswa_id = $_SESSION['user_id'];

// Konfigurasi pagination
$limit = 5; // Jumlah data per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1; // Halaman aktif
$offset = ($page - 1) * $limit; // Hitung offset

// Hitung total data
$stmtTotal = $pdo->prepare("SELECT COUNT(*) FROM perijinan WHERE nomor_induk = (SELECT nomor_induk FROM siswa WHERE id = ?)");
$stmtTotal->execute([$siswa_id]);
$totalData = $stmtTotal->fetchColumn();
$totalPages = ceil($totalData / $limit); // Total halaman

// Ambil data perijinan dengan batasan pagination
$stmt = $pdo->prepare("SELECT * FROM perijinan WHERE nomor_induk = (SELECT nomor_induk FROM siswa WHERE id = ?) 
                        ORDER BY tanggal_pulang DESC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $siswa_id, PDO::PARAM_INT);
$stmt->bindValue(2, $limit, PDO::PARAM_INT);
$stmt->bindValue(3, $offset, PDO::PARAM_INT);
$stmt->execute();
$perijinan = $stmt->fetchAll();

// Ambil data perijinan siswa yang login
// $siswa_id = $_SESSION['user_id'];
// $stmt = $pdo->prepare("SELECT * FROM perijinan WHERE nomor_induk = (SELECT nomor_induk FROM siswa WHERE id = ?) ORDER BY tanggal_pulang DESC");
// $stmt->execute([$siswa_id]);
// $perijinan = $stmt->fetchAll();

/* ======================
   HARI INDO
====================== */
function hariIndonesia($tanggal) {
    $hari = date('l', strtotime($tanggal));

    $hariIndo = [
        'Sunday'    => 'Ahad',
        'Monday'    => 'Senin',
        'Tuesday'   => 'Selasa',
        'Wednesday' => 'Rabu',
        'Thursday'  => 'Kamis',
        'Friday'    => 'Jumat',
        'Saturday'  => 'Sabtu'
    ];

    return $hariIndo[$hari];
}

function tanggalIndonesia($tanggal) {

    $bulan = [
        1 => 'Januari','Februari','Maret','April','Mei','Juni',
        'Juli','Agustus','September','Oktober','November','Desember'
    ];

    $tanggalExplode = explode('-', date('Y-m-d', strtotime($tanggal)));

    return $tanggalExplode[2] . ' ' .
           $bulan[(int)$tanggalExplode[1]] . ' ' .
           $tanggalExplode[0];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Perijinan - Siswa</title>
    <link rel="icon" type="image/x-icon" href="../../assets/img/ihbs-logo.png">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">

    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">

    <style>
    /* poppins */

    h1,
    h2,
    h3,
    h4,
    h5,
    h6 {
        font-family: "Poppins", sans-serif;
        font-weight: bold;
    }

    p,
    a,
    input,
    strong,
    tr,
    th,
    td,
    button,
    div {
        font-family: "Poppins", sans-serif;
    }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-light container sticky-top">
        <!-- <a class="navbar-brand" href="#">Aplikasi Pesantren</a> -->
        <img src="../../assets/homecoming-logo.png" style="width: 150px; margin-left: 0%; margin-top: 0%">
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
                    <a style="color: #28A745" class="nav-link" href="data-perijinan.php"><b>Data Perijinan</b></a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="data-kedatangan.php">Data Kedatangan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="form-perijinan-laptop.php">Perijinan Laptop</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link" href="../../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4 mb-5">
        <h2 class="mt-5 mb-3 text-center">Data Perijinan Perpulangan</h2>

        <div class="mt-3">
            <a href="dashboard.php" class="btn btn-success btn-md text-white rounded-pill">Kembali</a>
            <a href="export-data-perijinan.php" class="btn btn-info btn-md text-white rounded-pill">Cetak</a>
            <!-- <button class="btn btn-success" data-toggle="modal" data-target="#uploadCSVModal">Upload CSV</button>
            <a href="template_petugas.csv" class="btn btn-secondary" download>Download Template CSV</a> -->
        </div>

        <!-- Input Pencarian -->
        <div class="form-group">
            <input type="text" id="searchInput" class="form-control"
                style="width: 200px; margin-left: 82%; margin-top: 1%" placeholder="Cari Data Tabel"><i
                class="fas fa-search" style="position: absolute"></i>
        </div>

        <div class="table-responsive">x
            <table class="table table-bordered" id="dataTable">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Waktu Pulang</th>
                        <!-- <th>Waktu</th> -->
                        <th>Nama Siswa</th>
                        <!-- <th>Nomor Induk</th> -->
                        <!-- <th>Kelas</th> -->
                        <th>Keperluan</th>
                        <!-- <th>Petugas</th> -->
                        <!-- <th>Keterangan</th> -->
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($perijinan as $key => $row): ?>
                    <tr>
                        <td><?php echo $key + 1; ?></td>
                        <!-- <td><?php echo date('d/m/Y', strtotime($row['tanggal_pulang'])); ?></td> -->
                        <!-- <td><?php echo date('d F Y', strtotime($row['tanggal_pulang'])); ?></td> -->
                        <td>
                            <?= hariIndonesia($row['tanggal_pulang']); ?>,
                            <?= tanggalIndonesia($row['tanggal_pulang']); ?>
                            <br>
                            <small style="color: red;">Jam: <?= substr($row['tanggal_pulang'],11,5) ?> WIB</small>
                        </td>
                        <!-- <td><?php echo substr($row['tanggal_pulang'], 11, 5) ?></td> -->
                        <td><?php echo $row['nama_siswa']; ?>
                            <br>
                            <small style="color: red;">Kelas: <?= htmlspecialchars($row['kelas']); ?></small>
                        </td>
                        <!-- <td><?php echo $row['nomor_induk']; ?></td> -->
                        <!-- <td><?php echo $row['kelas']; ?></td> -->
                        <td><?php echo $row['keperluan']; ?></td>
                        <!-- <td><?php echo $row['petugas']; ?></td> -->
                        <!-- <td><?php echo $row['keterangan']; ?></td> -->
                        <td>
                            <button class="btn btn-info btn-sm rounded-pill" data-toggle="modal"
                                data-target="#detailModal<?= $row['id']; ?>">
                                Detail
                            </button>
                        </td>
                    </tr>

                    <?php endforeach; ?>
                </tbody>
            </table>

            <?php foreach ($perijinan as $row): ?>
            <!-- detail Modal -->
            <div class="modal fade" id="detailModal<?= $row['id']; ?>" tabindex="-1">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <div class="modal-header bg-light text-black">
                            <h5 class="modal-title">Detail Perijina</h5>
                            <button type="button" class="close text-black" data-dismiss="modal">
                                <span>&times;</span>
                            </button>
                        </div>

                        <div class="modal-body print-card" id="printArea<?= $row['id']; ?>">

                            <div style="text-align:center;">
                                <img src="../../assets/img/logo-sma.png" width="60">
                                <br><br>
                                <h4>BUKTI PERIJINAN SISWA</h4>
                            </div>
                            <br>
                            <table class="table table-bordered">

                                <tr>
                                    <th>Nama</th>
                                    <td><?= htmlspecialchars($row['nama_siswa']); ?></td>
                                </tr>

                                <tr>
                                    <th>NIS</th>
                                    <td><?= htmlspecialchars($row['nomor_induk']); ?></td>
                                </tr>

                                <tr>
                                    <th>Kelas</th>
                                    <td><?= htmlspecialchars($row['kelas']); ?></td>
                                </tr>

                                <tr>
                                    <th>Tanggal</th>
                                    <td>
                                        <?= hariIndonesia($row['tanggal_pulang']); ?>,
                                        <?= tanggalIndonesia($row['tanggal_pulang']); ?>
                                    </td>
                                </tr>

                                <tr>
                                    <th>Jam</th>
                                    <td><?= date('H:i', strtotime($row['tanggal_pulang'])) ?> WIB</td>
                                </tr>

                                <!-- <tr>
                                    <th>Keperluan</th>
                                    <td><?= htmlspecialchars($row['keperluan']); ?></td>
                                </tr> -->

                                <tr>
                                    <th>Keterangan</th>
                                    <td><?= htmlspecialchars($row['keterangan']); ?></td>
                                </tr>

                            </table>

                            <br>

                            <div style="text-align: center;">
                                Depok, <?= tanggalIndonesia($row['tanggal_pulang']); ?><br>
                                Petugas
                                <br><br><br>
                                <b><?= htmlspecialchars($row['petugas']); ?></b>
                            </div>
                            <br>
                            <div class="no-print mt-3" style="text-align: center">
                                <button class="btn btn-primary rounded-pill" onclick="printData(<?= $row['id']; ?>)">
                                    <i class="fas fa-save"></i> Print Bukti
                                </button>

                                <button class="btn btn-secondary rounded-pill" data-dismiss="modal">
                                    Tutup
                                </button>
                            </div>

                        </div>

                    </div>
                </div>
            </div>
            <?php endforeach ?>
        </div>

        <!-- Pagination -->
        <nav>
            <ul class="pagination justify-content-center">
                <li class="page-item <?php echo ($page <= 1) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page - 1; ?>">Previous</a>
                </li>
                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                <li class="page-item <?php echo ($i == $page) ? 'active' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $i; ?>"><?php echo $i; ?></a>
                </li>
                <?php endfor; ?>
                <li class="page-item <?php echo ($page >= $totalPages) ? 'disabled' : ''; ?>">
                    <a class="page-link" href="?page=<?php echo $page + 1; ?>">Next</a>
                </li>
            </ul>
        </nav>

    </div>
    <br>

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

    <!-- Print Data dari Modal Detail -->
    <script>
    function printData(id) {

        var isi = document.getElementById("printArea" + id).innerHTML;

        var printWindow = window.open('', '', 'height=600,width=800');

        printWindow.document.write('<html>');
        printWindow.document.write('<head>');
        printWindow.document.write('<title>Bukti Perijinan</title>');

        // Google Font Poppins
        printWindow.document.write(
            '<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">'
        );

        printWindow.document.write(
            '<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">'
        );

        printWindow.document.write(`
    <style>
        body{
            display:flex;
            justify-content:center;
            align-items:center;
            height:100vh;
        }

        .print-card{
            width:500px;
            border:2px solid #000;
            padding:30px;
            font-size:16px;
        }

        table{
            font-size:13px;
        }

        .no-print{
            display:none;
        }
    </style>
    `);

        printWindow.document.write('</head>');
        printWindow.document.write('<body>');

        printWindow.document.write('<div class="print-card">');
        printWindow.document.write(isi);
        printWindow.document.write('</div>');

        printWindow.document.write('</body></html>');

        printWindow.document.close();
        printWindow.print();
    }
    </script>

</body>

</html>