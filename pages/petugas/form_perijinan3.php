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
    $stmtp = $pdo->prepare("INSERT INTO perijinan (nomor_induk, nama_siswa, kelas, nama_orang_tua, keperluan, tanggal_pulang, petugas, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $stmtp->execute([$nomor_induk, $nama_siswa, $kelas, $nama_orang_tua, $keperluan, $tanggal_pulang, $petugas['nama_petugas'], $keterangan]);
    echo "<script>alert('Data perijinan berhasil ditambahkan!'); window.location='form_perijinan.php';</script>";
}

// Ambil Data Siswa
// $stmt = $pdo->query("SELECT * FROM siswa");
// $siswa = $stmt->fetchAll();

// Ambil daftar siswa untuk select option
$stmt = $pdo->query("SELECT nama_siswa FROM siswa ORDER BY nama_siswa ASC");
$siswa_list = $stmt->fetchAll(PDO::FETCH_ASSOC);


// ambil data
if (isset($_GET['nama_siswa'])) {
    $nama_siswa = $_GET['nama_siswa'];

    $stmt = $pdo->prepare("SELECT nomor_induk, kelas, nama_orang_tua FROM siswa WHERE nama_siswa = ?");
    $stmt->execute([$nama_siswa]);
    $data = $stmt->fetch(PDO::FETCH_ASSOC);

    // Debugging: tampilkan data
    header('Content-Type: application/json');
    echo json_encode($data);
    exit;

    // if ($data) {
    //     echo json_encode($data);
    // } else {
    //     echo json_encode(["error" => "Data tidak ditemukan"]);
    // }
}


// Jika nomor induk dipilih, ambil data siswa tersebut
// 
$selectedSiswa = null;
// if (isset($_POST['nama_siswa'])) {
//     $nomor_induk = $_POST['nomor_siswa'];
//     foreach ($siswaList as $siswa) {
//         if ($siswa['nama_siswa'] == $nama_siswa) {
//             $selectedSiswa = $siswa;
//             break;
//         }
//     }
// }
// Ambil nama guru dan nama mata pelajaran berdasarkan NIP dan kode_mapel
// $nomor_induk = isset($_POST['nomor_induk']) ? $_POST['nomor_induk'] : '';
// $sql_siswa = "SELECT nama_siswa FROM siswa WHERE nomor_induk = ?";
// $stmt = $pdo->prepare($sql_siswa);
// $stmt->execute([$nomor_induk]);
// $row_siswa = $stmt->fetch();
// $nama_siswa = $row_siswa['nama_siswa'] ?? '';

    

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
                                <label for="nomor_induk">Nomor Induk Siswa</label>
                                <input type="text" class="form-control" id="nomor_induk" name="nomor_induk" required>
                            </div>
                            <!-- <div class="form-group"> -->
                                <!-- <label for="nama_siswa">Nama Siswa</label> -->
                                <!-- <input type="text" class="form-control" id="nama_siswa" name="nama_siswa" value="<?php echo $siswa['nama_siswa']; ?>" required> -->
                                <!-- <select class="form-control" id="nama_siswa" name="nama_siswa" required> -->
                                    <!-- <option value="">Pilih Nama Siswa</option> -->
                                    <?php
                                    // Ambil data pimpinan dari database
                                    // $stmt = $pdo->query("SELECT * FROM siswa");
                                    // while ($row = $stmt->fetch()) {
                                    //     echo "<option value='{$row['nama_siswa']}'>{$row['nama_siswa']}</option>";
                                    // }

                                    // foreach ($siswa as $row) {
                                    //     echo "<option value='{$row['nama_siswa']}'>{$row['nama_siswa']}</option>";
                                    // }
                                    ?>
                                    
                                    
                                <!-- </select> -->
                            <!-- </div> -->
                            <div class="form-group">
                                <label for="nama_siswa">Nama Siswa</label>
                                <select class="form-control" id="nama_siswa" name="nama_siswa" required>
                                    <option value="">-- Pilih Siswa --</option>
                                    <?php foreach ($siswa_list as $siswa): ?>
                                        <option value="<?= htmlspecialchars($siswa['nama_siswa']) ?>"><?= htmlspecialchars($siswa['nama_siswa']) ?></option>
                                    <?php endforeach; ?>
                                </select>
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
                                    <!-- <option value="kedatangan">Kedatangan</option> -->
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
                                    $stmt = $pdo->query("SELECT * FROM petugas");
                                    while ($row = $stmt->fetch()) {
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
                            <!-- <button type="button" name="tambah" class="btn btn-success">Submit</button> -->
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
    <!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
    <!-- <script>
        $(document).ready(function() {
            $("#nama_siswa").change(function() {
                var nama_siswa = $(this).val();
                if (nama_siswa) {
                    $.ajax({
                        url: "get_siswa.php",
                        type: "GET",
                        data: { nama_siswa: nama_siswa },
                        dataType: "json",
                        success: function(response) {
                            console.log("Response:", response); // Debugging
                            if (response && response.nomor_induk) {
                                $("#nomor_induk").val(response.nomor_induk);
                                $("#kelas").val(response.kelas);
                                $("#nama_orang_tua").val(response.nama_orang_tua);
                            } else {
                                alert("Data tidak ditemukan di database!");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.log("AJAX Error:", xhr.responseText);
                            alert("Terjadi kesalahan saat mengambil data!");
                        }
                    });
                } else {
                    $("#nomor_induk, #kelas, #nama_orang_tua").val('');
                }
            });
        });

    </script> -->
    <script src="../js/script-ijin.js"></script>

</body>
</html>