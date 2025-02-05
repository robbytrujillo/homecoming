<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'petugas') {
    header('Location: ../../login.php');
    exit;
}
require '../../includes/db.php';

// Ambil data petugas yang login
$petugas_id = $_SESSION['user_id'];
$stmtp = $pdo->prepare("SELECT * FROM petugas WHERE id = ?");
$stmtp->execute([$petugas_id]);
$petugas = $stmtp->fetch();

// Proses tambah perijinan
if (isset($_POST['tambah'])) {
    $nomor_induk = $_POST['nomor_induk'];
    $nama_siswa = $_POST['nama_siswa'];
    $kelas = $_POST['kelas'];
    $nama_orang_tua = $_POST['nama_orang_tua'];
    $keperluan = $_POST['keperluan'];
    $tanggal_pulang = $_POST['tanggal_pulang'];
    $keterangan = $_POST['keterangan'];

    // Simpan data perijinan
    $stmt = $pdo->prepare("INSERT INTO perijinan (nomor_induk, nama_siswa, kelas, nama_orang_tua, keperluan, tanggal_pulang, petugas, keterangan) 
                           VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->execute([$nomor_induk, $nama_siswa, $kelas, $nama_orang_tua, $keperluan, $tanggal_pulang, $petugas['nama_petugas'], $keterangan]);

    echo "<script>alert('Data perijinan berhasil ditambahkan!'); window.location='form_perijinan.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Perijinan - Petugas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-light bg-light container">
        <img src="../../assets/homecoming-logo.png" style="width: 100px; margin-left: 2%; margin-top: 1%">    
        <!-- <a class="navbar-brand" href="#">Aplikasi Pesantren</a> -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item">
                    <a class="nav-link" href="dashboard.php">Dashboard</a>
                </li>
                <li class="nav-item active">
                    <a style="color: blue;" class="nav-link" href="form_perijinan.php"><b>Form Perijinan</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data_perijinan.php">Data Perijinan</a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="data_kedatangan.php">Data kedatangan</a>
                </li> -->    
                <!-- <li class="nav-item active">
                    <a class="nav-link" href="form_kedatangan.php">Form Kedatangan</a>
                </li> -->
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
                        <form action="" method="POST">
                            <div class="form-group">
                                <label for="nama_siswa">Nama Siswa</label>
                                <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" autocomplete="off">
                                <div id="suggestions" class="list-group" style="position: absolute; z-index: 1000;"></div> 
                            </div>
                            <div class="form-group">
                                <label for="nomor_induk">Nomor Induk Siswa</label>
                                <input type="text" class="form-control" id="nomor_induk" name="nomor_induk" required>
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
                                    <!-- <option value="kedatangan">Kedatangan</option>  -->
                                </select> 
                            </div>
                            <div class="form-group">
                                <label for="tanggal_pulang">Tanggal Perpulangan</label>
                                <input type="date" class="form-control" id="tanggal_pulang" name="tanggal_pulang" required>
                            </div>
                            <div class="form-group">
                                <label for="perijinan">Petugas</label>
                                <select class="form-control" id="petugas" name="petugas" required>
                                    <?php
                                    // Ambil data pimpinan dari database
                                    $stmtp = $pdo->query("SELECT * FROM petugas");
                                    while ($row = $stmtp->fetch()) {
                                        echo "<option value='{$row['nama_petugas']}'>{$row['nama_petugas']}</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"></textarea>
                            </div>
                            <button type="submit" name="tambah" class="btn btn-success">Submit</button>
                            <a href="data_perijinan.php">Data Perijinan</a>
                        </form>
                    </div>
                </div>
            </div>
        </div>        
    </div>

    <!-- Footer -->
    <?php include '../../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <script>
        $(document).ready(function() {
        $("#nama_siswa").on("input", function() {
            var nama = $(this).val();
            if (nama.length > 2) {
                $.ajax({
                    url: "cari_siswa.php",
                    type: "GET",
                    data: { nama_siswa: nama },
                    success: function(response) {
                        console.log(response); // Debugging

                        try {
                            let data = JSON.parse(response);
                            if (data.length > 0) {
                                $("#suggestions").empty().show();
                                data.forEach(function(item) {
                                    $("#suggestions").append(
                                        `<a href="#" class="list-group-item list-group-item-action pilih-siswa" 
                                        data-nama="${item.nama_siswa}" 
                                        data-nomor_induk="${item.nomor_induk}" 
                                        data-kelas="${item.kelas}" 
                                        data-orangtua="${item.nama_orang_tua}">
                                        ${item.nama_siswa}
                                        </a>`
                                    );
                                });

                                // Tambahkan event click setelah data di-load
                                $(".pilih-siswa").on("click", function(e) {
                                    e.preventDefault();
                                    pilihSiswa(
                                        $(this).data("nama"),
                                        $(this).data("nomor_induk"),
                                        $(this).data("kelas"),
                                        $(this).data("orangtua")
                                    );
                                });

                            } else {
                                $("#suggestions").hide();
                            }
                        } catch (error) {
                            console.error("Parsing error:", error);
                        }
                    },
                    error: function(xhr, status, error) {
                        console.error("AJAX Error: " + error);
                    }
                });
            } else {
                $("#suggestions").hide();
            }
        });
    });

    function pilihSiswa(nama, nomor_induk, kelas, nama_orang_tua) {
        $("#nama_siswa").val(nama);
        $("#nomor_induk").val(nomor_induk);
        $("#kelas").val(kelas);
        $("#nama_orang_tua").val(nama_orang_tua);
        $("#suggestions").hide();
    }

    </script>
</body>
</html>
