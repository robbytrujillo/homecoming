<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'pimpinan') {
    header('Location: ../../login.php');
    exit;
}
require '../../includes/db.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pimpinan</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light container">
    <img src="../../assets/homecoming-logo.png" style="width: 150px; margin-left: 0%; margin-top: 0.5%">    
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a class="nav-link" href="#">Dashboard</a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                        Data Master
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="data_siswa.php">Data Siswa</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="data_petugas.php">Data Petugas</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                        Perpulangan
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="data-perijinan.php">Perijinan</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="data-kedatangan.php">Kedatangan</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="data-perijinan-laptop.php">Perijinan Laptop</a>
                    </div>
                </li>
                <li class="nav-item">
                    <a class="nav-link rounded-pill" href="../../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4 rounded">
        <div class="row">
            <div class="col-md-3">
                <div class="card text-white bg-primary mb-3">
                    <div class="card-header">Siswa</div>
                    <div class="card-body">
                        <h5 class="card-title">Total Siswa</h5>
                        <p class="card-text"><?php echo $pdo->query("SELECT COUNT(*) FROM siswa")->fetchColumn(); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 rounded">
                <div class="card text-white bg-success mb-3">
                    <div class="card-header">Perijinan Pulang</div>
                    <div class="card-body">
                        <h5 class="card-title">Total Perijinan Pulang</h5>
                        <p class="card-text"><?php echo $pdo->query("SELECT COUNT(*) FROM perijinan")->fetchColumn(); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 rounded">
                <div class="card text-white bg-warning mb-3">
                    <div class="card-header">Kedatangan Mahad</div>
                    <div class="card-body">
                        <h5 class="card-title">Kedatangan Mahad</h5>
                        <p class="card-text"><?php echo $pdo->query("SELECT COUNT(*) FROM kedatangan")->fetchColumn(); ?></p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-white bg-info mb-3 rounded">
                    <div class="card-header">Perijinan</div>
                    <div class="card-body">
                        <h5 class="card-title">Total Perijinan</h5>
                        <p class="card-text"><?php echo $pdo->query("SELECT COUNT(*) FROM perijinan")->fetchColumn(); ?></p>
                    </div>
                </div>
            </div>
        </div>

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

    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>