<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'siswa') {
    header('Location: ../../login.php');
    exit;
}
require '../../includes/db.php';

// Ambil data siswa yang login
$siswa_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM siswa WHERE id = ?");
$stmt->execute([$siswa_id]);
$siswa = $stmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">

    <!-- Library untuk QR Code -->
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
    <!-- Librady untuk Print QRCode -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>

</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light container sticky-top">
        <!-- <a class="navbar-brand" href="#">Aplikasi Pesantren</a> -->
        <img src="../../assets/homecoming-logo.png" style="width: 150px; margin-left: 0%; margin-top: 0%">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" style="color: #28A745;" href="dashboard.php"><b>Dashboard</b></a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data-perijinan.php">Data Perijinan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data-kedatangan.php">Data Kedatangan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="form-perijinan-laptop.php">Perijinan Laptop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="form-pengembalian-laptop.php">Pengembalian Laptop</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-5">
        <h4 class="mt-5 mb-3">Selamat datang <strong><?= $siswa['nama_siswa'] ?></strong> di halaman utama siswa</h4>
        <div class="row">
            <div class="col-md-4 bg-white">
                <div class="card bg-light mb-3 shadow-sm rounded-lg border-0">
                    <div class="card-body">
                        <!-- <h5 class="card-title">Data Perijinan</h5> -->
                        <img src="../../assets/permissions.svg" style="height: 320px" class="cover img-fluid">
                        <h5 class="card-text text-center mt-3 mb-3">Melihat data perijinan siswa</h5>
                        <div class="d-flex justify-content-center">
                            <a href="data-perijinan.php" class="btn btn-outline-success btn-block font-weight-bold rounded-pill">Lihat Data</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light mb-3 shadow-sm rounded-lg border-0">
                    <div class="card-body">
                        <!-- <h5 class="card-title">Data Kedatangan</h5> -->
                        <img src="../../assets/holiday.svg" style="height: 320px" class="cover img-fluid">
                        <h5 class="card-text text-center mt-3 mb-3">Melihat data kedatangan siswa</h5>
                        <div class="d-flex justify-content-center">
                            <a href="data-kedatangan.php" class="btn btn-outline-success btn-block font-weight-bold rounded-pill">Lihat Data</a>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card bg-light mb-3 shadow-sm rounded-lg border-0">
                    <div class="card-body">
                        <!-- <h5 class="card-title">Perijinan Laptop</h5> -->
                        <img src="../../assets/laptops.svg" style="height: 320px" class="cover img-fluid">
                        <h5 class="card-text text-center mt-3 mb-3">Perijinan membawa laptop</h5>
                        <div class="d-flex justify-content-center">
                            <a href="form-perijinan-laptop.php" class="btn btn-outline-success btn-block font-weight-bold rounded-pill">Ajukan Perijinan</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row d-flex justify-content-center mb-4 ">
            <div class="col-md-6 d-flex align-items-center">
                <div class="card w-100 shadow-sm rounded-lg border-0">
                    <div class="card-header">
                        <h5 class="card-title text-center">QRCode Siswa</h5>
                    </div>
                    <div class="card-body d-flex justify-content-center">
                        <div id="qrcode"></div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card w-100 shadow-sm rounded-lg border-0">
                    <div class="card-header">
                        <h5 class="card-title text-center">Profil Siswa</h5>
                    </div>
                    <div class="card-body">
                        <p>Nomor Induk Siswa &nbsp; &nbsp;: <strong><?php echo $siswa['nomor_induk']; ?></strong></p>
                        <p>Nama Siswa  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: <strong><?php echo $siswa['nama_siswa']; ?></strong></p>
                        <p>Kelas &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;: <strong><?php echo $siswa['kelas']; ?></strong></p>
                        <p>Nama Orang Tua &nbsp; &nbsp; &nbsp; &nbsp;: <strong><?php echo $siswa['nama_orang_tua']; ?></strong></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <br>
    
    <!-- Footer -->
    <?php include '../../includes/footer.php'; ?>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>

    <!-- Script untuk Generate QR Code -->
    <script>

    // Data dalam format string biasa, bukan JSON
    const dataSiswa = 
        "Nomor Induk    : <?php echo $siswa['nomor_induk']; ?>\n" +
        "Nama Siswa      : <?php echo $siswa['nama_siswa']; ?>\n" +
        "Kelas             : <?php echo $siswa['kelas']; ?>\n" +
        "Nama Orang Tua : <?php echo $siswa['nama_orang_tua']; ?>";

    // Konversi data ke format JSON
    // const dataString = JSON.stringify(dataSiswa);

    // Generate QR Code dengan format teks biasa
    const qrcode = new QRCode(document.getElementById("qrcode"), {
        text: dataSiswa,
        width: 160, // Ubah ukuran agar lebih besar
        height: 160
    });


    // document.getElementById("printQR").addEventListener("click", function() {
    // const { jsPDF } = window.jspdf;
    // let doc = new jsPDF();

    // html2canvas(document.querySelector("#qrcode")).then(canvas => {
    //     let imgData = canvas.toDataURL("image/png");
    //     doc.text("QR Code Profil", 80, 10); // Judul PDF
    //     doc.addImage(imgData, 'PNG', 50, 20, 100, 100); // Tambahkan QR Code
    //     doc.save("QRCode-Profile.pdf"); // Simpan sebagai PDF
    //     });
    // });

    // document.getElementById("printQR").addEventListener("click", function() {
    //     const { jsPDF } = window.jspdf;
    //     let doc = new jsPDF();

    //     html2canvas(document.querySelector("#qrcode")).then(canvas => {
    //         let imgData = canvas.toDataURL("image/png");

    //         // Ukuran dan posisi gambar QR Code agar rata tengah
    //         let pageWidth = doc.internal.pageSize.getWidth();
    //         let pageHeight = doc.internal.pageSize.getHeight();
    //         let imgWidth = 180; // Sesuaikan ukuran QR Code
    //         let imgHeight = 180;
    //         let centerX = (pageWidth - imgWidth) / 2; // Posisi tengah horizontal
    //         let centerY = (pageHeight - imgHeight) / 2; // Posisi tengah vertikal

    //         // Tambahkan teks judul
    //         doc.setFontSize(16);
    //         doc.text("QR Code Profil", pageWidth / 2, 30, { align: "center" });

    //         // Tambahkan gambar QR Code
    //         doc.addImage(imgData, 'PNG', centerX, centerY, imgWidth, imgHeight);

    //         // Simpan sebagai PDF
    //         doc.save("QRCode-Profile.pdf");
    //     });
    // });

    </script>
</body>
</html>