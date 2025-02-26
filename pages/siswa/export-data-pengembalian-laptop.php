<?php 
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'siswa') {
    header('Location: ../../login.php');
    exit;
}

require '../../includes/db.php';

// Ambil ID siswa dari sesi
$id_siswa = $_SESSION['user_id']; 

// Ambil nomor_induk siswa dari database
$stmt = $pdo->prepare("SELECT nomor_induk FROM siswa WHERE id = ?");
$stmt->execute([$id_siswa]);
$siswa = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$siswa) {
    die("Data siswa tidak ditemukan.");
}

$nomor_induk = $siswa['nomor_induk'];

// Ambil data perijinan hanya untuk siswa yang sedang login
$stmt = $pdo->prepare("
    SELECT * FROM pengembalian_laptop 
    WHERE nomor_induk = ? 
    ORDER BY tanggal_pengembalian DESC
");

$stmt->execute([$nomor_induk]);
$data = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html>
<head>
  <title>Data Kedatangan</title>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.16.0/umd/popper.min.js"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.css">
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/buttons/1.6.5/css/buttons.dataTables.min.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light container sticky-top">
    <img src="../../assets/homecoming-logo.png" style="width: 150px; margin-left: 0%; margin-top: 0%">
</nav>

<div class="container mt-3 mb-3">
    <h4 class="mt-3 mb-3 text-center">Data Pengembalian Laptop</h4>
    <br>
    <a href="data-pengembalian-laptop.php" class="btn btn-success rounded-pill">Kembali</a>
    <br><br>

    <div class="data-tables datatable-dark">
        <table class="table table-bordered" id="mauexport" width="100%" cellspacing="0">                                       
            <thead>
                <tr>
                    <th>No</th>
                    <th>Tanggal Pengembalian</th>
                    <th>Nama Siswa</th>
                    <th>Nomor Induk</th>
                    <th>Kelas</th>
                    <th>Petugas</th>
                    <th>Keterangan</th>
                    <!-- <th>Keterangan</th> -->
                </tr>
            </thead>
            <tbody>
                <?php 
                $i = 1;
                foreach($data as $row) {                                          
                ?>
                <tr>
                    <td><?= $i++; ?></td>
                    <td><?= date('d F Y', strtotime($row['tanggal_pengembalian'])); ?></td>
                    <td><?= $row['nama_siswa']; ?></td>
                    <td><?= $row['nomor_induk']; ?></td>
                    <td><?= $row['kelas']; ?></td>
                    <td><?= $row['petugas']; ?></td>
                    <td><?= $row['keterangan']; ?></td>
                    <!-- <td><?= $row['keterangan']; ?></td> -->
                </tr>   
                <?php 
                };
                ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Footer -->
<?php include '../../includes/footer.php'; ?>

<script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<script src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#mauexport').DataTable( {
        dom: 'Bfrtip',
        buttons: ['copy', 'csv', 'excel', 'pdf', 'print']
    });
});
</script>

</body>
</html>
