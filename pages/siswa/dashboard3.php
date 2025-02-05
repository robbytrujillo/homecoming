<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'siswa') {
    header('Location: ../../login.php');
    exit;
}
require '../../includes/db.php';

try {
    // Ambil data siswa yang login
    $siswa_id = $_SESSION['user_id'];
    $stmt = $pdo->prepare("SELECT * FROM siswa WHERE id = ?");
    $stmt->execute([$siswa_id]);
    $siswa = $stmt->fetch();

    if (!$siswa) {
        die("Data siswa tidak ditemukan.");
    }

    // Data untuk QR Code
    $dataSiswa = [
        'nomor_induk' => $siswa['nomor_induk'],
        'nama_siswa' => $siswa['nama_siswa'],
        'kelas' => $siswa['kelas'],
        'nama_orang_tua' => $siswa['nama_orang_tua'],
    ];
    $dataString = json_encode($dataSiswa);

    // Generate QR Code dan simpan ke folder
    require '../../vendor/autoload.php'; // Pastikan library PHP QR Code sudah diinstal
    use Endroid\QrCode\Builder\Builder;
    use Endroid\QrCode\Encoding\Encoding;
    use Endroid\QrCode\ErrorCorrectionLevel;
    use Endroid\QrCode\Writer\PngWriter;

    // Pastikan folder QR Code ada
    $dir = '../../uploads/qrcodes/';
    if (!is_dir($dir)) {
        mkdir($dir, 0777, true);
    }

    // Buat QR Code
    $result = Builder::create()
        ->writer(new PngWriter())
        ->data($dataString)
        ->encoding(new Encoding('UTF-8'))
        ->errorCorrectionLevel(ErrorCorrectionLevel::High)
        ->size(300)
        ->margin(10)
        ->build();

    $qrcodePath = $dir . $siswa['nomor_induk'] . '.png';
    $result->saveToFile($qrcodePath);

    // Simpan path QR Code ke database
    $stmt = $pdo->prepare("UPDATE siswa SET qrcode = ? WHERE id = ?");
    $stmt->execute([$qrcodePath, $siswa_id]);
} catch (Exception $e) {
    die("Terjadi kesalahan: " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Siswa</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/qrcodejs/qrcode.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
</head>
<body>
    <div class="container mt-4">
        <h2>Dashboard Siswa</h2>
        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">Profil Siswa</div>
                    <div class="card-body">
                        <p><strong>Nomor Induk:</strong> <?php echo htmlspecialchars($siswa['nomor_induk']); ?></p>
                        <p><strong>Nama Siswa:</strong> <?php echo htmlspecialchars($siswa['nama_siswa']); ?></p>
                        <p><strong>Kelas:</strong> <?php echo htmlspecialchars($siswa['kelas']); ?></p>
                        <p><strong>Nama Orang Tua:</strong> <?php echo htmlspecialchars($siswa['nama_orang_tua']); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">QR Code Profil</div>
                    <div class="card-body text-center">
                        <div id="qrcode"></div>
                        <button onclick="printQRCodeToPDF()" class="btn btn-success mt-3">Cetak QR Code</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script>
    document.addEventListener("DOMContentLoaded", function () {
        const dataSiswa = {
            nomor_induk: "<?php echo addslashes($siswa['nomor_induk']); ?>",
            nama_siswa: "<?php echo addslashes($siswa['nama_siswa']); ?>",
            kelas: "<?php echo addslashes($siswa['kelas']); ?>",
            nama_orang_tua: "<?php echo addslashes($siswa['nama_orang_tua']); ?>"
        };

        const dataString = `Nomor Induk: ${dataSiswa.nomor_induk}\nNama Siswa: ${dataSiswa.nama_siswa}\nKelas: ${dataSiswa.kelas}\nNama Orang Tua: ${dataSiswa.nama_orang_tua}`;

        if (document.getElementById("qrcode")) {
            new QRCode(document.getElementById("qrcode"), {
                text: dataString,
                width: 200,
                height: 200
            });
        }
    });

    function printQRCodeToPDF() {
        const { jsPDF } = window.jspdf;
        const doc = new jsPDF('p', 'mm', 'a4');
        const qrcodeElement = document.getElementById("qrcode");

        html2canvas(qrcodeElement).then((canvas) => {
            const imgData = canvas.toDataURL('image/png');
            doc.addImage(imgData, 'PNG', 10, 10, 50, 50);
            doc.text(`Nomor Induk: ${dataSiswa.nomor_induk}`, 70, 20);
            doc.text(`Nama Siswa: ${dataSiswa.nama_siswa}`, 70, 30);
            doc.text(`Kelas: ${dataSiswa.kelas}`, 70, 40);
            doc.text(`Nama Orang Tua: ${dataSiswa.nama_orang_tua}`, 70, 50);
            doc.save('qrcode_profil.pdf');
        });
    }
    </script>
</body>
</html>