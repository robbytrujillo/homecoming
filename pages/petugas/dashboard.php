<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'petugas') {
    header('Location: ../../login.php');
    exit;
}
require '../../includes/db.php';

// $petugas = $_GET('nama_petugas');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Petugas</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light container">
    <img src="../../assets/homecoming-logo.png" style="width: 100px; margin-left: 1%; margin-top: 1%">    
    <!-- <a class="navbar-brand" href="#">Aplikasi Pudamah</a> -->
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a style="color: blue;" class="nav-link" href="#"><b>Dashboard</b></a>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="data_perijinan.php">Data Perijinan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data_kedatangan.php">Data Kedatangan</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link" href="form_perijinan.php">Input Perijinan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="form_kedatangan.php">Input Kedatangan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4">
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Perijinan</div>
                    <div class="card-body">
                        <h5 class="card-title">Total Perijinan</h5>
                        <p class="card-text"><?php echo $pdo->query("SELECT COUNT(*) FROM perijinan")->fetchColumn(); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Kedatangan</div>
                    <div class="card-body">
                        <h5 class="card-title">Total Kedatangan</h5>
                        <p class="card-text"><?php echo $pdo->query("SELECT COUNT(*) FROM kedatangan")->fetchColumn(); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Petugas</div>
                    <div class="card-body">
                        <h5 class="card-title">Total Petugas</h5>
                        <p class="card-text"><?php echo $pdo->query("SELECT COUNT(*) FROM petugas")->fetchColumn(); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-danger mb-3">
                    <div class="card-header">Siswa</div>
                    <div class="card-body">
                        <h5 class="card-title">Total Siswa</h5>
                        <p class="card-text"><?php echo $pdo->query("SELECT COUNT(*) FROM siswa")->fetchColumn(); ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- <h1>Selamat datang <?= $petugas; ?></h1> -->

        <div class="row mt-4">
            <div class="col-md-8">
                <canvas id="lineChart"></canvas>
            </div>
            <div class="col-md-4">
                <canvas id="pieChart"></canvas>
            </div>
        </div>
    </div>
    <br><br>

    

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        var ctx = document.getElementById('lineChart').getContext('2d');
        var lineChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: ['Minggu 1', 'Minggu 2', 'Minggu 3', 'Minggu 4'],
                datasets: [{
                    label: 'Perijinan Pulang',
                    data: [12, 19, 3, 5],
                    backgroundColor: 'rgba(75, 192, 192, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        var ctx2 = document.getElementById('pieChart').getContext('2d');
        var pieChart = new Chart(ctx2, {
            type: 'pie',
            data: {
                labels: ['Perpulangan', 'Penjengukan', 'Kedatangan'],
                datasets: [{
                    label: 'Jumlah Perijinan',
                    data: [12, 19, 3],
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)'
                    ],
                    borderWidth: 1
                }]
            }
        });
    </script>

    <!-- Footer -->
    <?php include '../../includes/footer.php'; ?>
</body>
</html>