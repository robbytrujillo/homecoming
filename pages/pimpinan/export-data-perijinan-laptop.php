<?php 
    session_start();
    if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'pimpinan') {
        header('Location: ../../login.php');
        exit;
    }
    require '../../includes/db.php';

    // Ambil Data Kedatangan
    $stmt = $pdo->query("SELECT * FROM perijinan_laptop ORDER BY tanggal_pengambilan DESC");
    $data = $stmt->fetchAll();
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
  <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.10.19/css/jquery.dataTables.min.css">
  <script type="text/javascript" charset="utf8" src="https://cdn.datatables.net/1.10.19/js/jquery.dataTables.js"></script>
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-light bg-light container sticky-top">
    <img src="../../assets/homecoming-logo.png" style="width: 150px; margin-left: 0%; margin-top: 0%">
</nav>

<div class="container mt-3 mb-3">
    <!-- <img src="../../assets/homecoming-logo.png" style="width: 150px; margin-left: 0%; margin-top: 0%; margin-right: 5%" href="index.php"> -->
    <h4 class="mt-3 mb-3 text-center">Data Perijinan Membawa Laptop</h4>
    <br>
    <a href="data-perijinan-laptop.php" class="btn btn-success rounded-pill">Kembali</a>
    <br>
    <br>
        <div class="data-tables datatable-dark">
            
            <!-- Masukkan table nya disini, dimulai dari tag TABLE -->
            <table class="table table-bordered" id="mauexport" width="100%" cellspacing="0">                                       
                <thead>
                    <tr>
                    <th>No</th>
                    <th>Tanggal Pengambilan</th>
                    <th>Nama Siswa</th>
                    <th>Nomor Induk</th>
                    <th>Kelas</th>
                    <th>Perijinan</th>
                    <th>Alasan Membawa Laptop</th>
                    <!-- <th>Keterangan</th> -->
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    
                    $ambilsemuadataperijinanlaptop = $pdo->query( "SELECT * FROM perijinan_laptop ORDER BY tanggal_pengambilan DESC")->fetchAll();
                    $i = 1;

                    foreach($ambilsemuadataperijinanlaptop as $data) {                                          
                        $tanggal_pengambilan = $data['tanggal_pengambilan'];
                        $nama_siswa = $data['nama_siswa'];
                        $nomor_induk = $data['nomor_induk'];
                        $kelas = $data['kelas'];
                        $perijinan = $data['perijinan'];
                        $alasan_membawa_laptop = $data['alasan_membawa_laptop'];
                    ?>
                    <tr>
                        <td><?= $i++; ?></td>
                        <td><?php echo date('d F Y', strtotime($tanggal_pengambilan)); ?></td>
                        <!-- <td><?php echo $tanggal_datang; ?></td> -->
                        <td><?php echo $nama_siswa; ?></td>
                        <td><?php echo $nomor_induk; ?></td>
                        <td><?php echo $kelas; ?></td>
                        <td><?php echo $perijinan; ?></td>
                        <td><?php echo $alasan_membawa_laptop; ?></td>
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
	
<script>
$(document).ready(function() {
    $('#mauexport').DataTable( {
        dom: 'Bfrtip',
        buttons: [
            'copy','csv','excel', 'pdf', 'print'
        ]
    } );
} );

</script>

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.flash.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.5/js/buttons.print.min.js"></script>

	

</body>

</html>