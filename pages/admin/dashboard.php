<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../../login.php');
    exit;
}
require '../../includes/db.php';

// Ambil data pimpinan yang login
$users_id = $_SESSION['user_id'];
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
$stmt->execute([$users_id]);
$users = $stmt->fetch();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="../../css/style.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-light container sticky-top">
    <img src="../../assets/homecoming-logo.png" style="width: 150px; margin-left: 0%; margin-top: 0%">    
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item active">
                    <a style="color: #28A745;" class="nav-link" href="#"><b>Dashboard</b></a>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                        Data Master
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="data_siswa.php">Data Siswa</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="data_petugas.php">Data Petugas</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="data-pimpinan.php">Data Pimpinan</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="data-users.php">Data Users</a>
                    </div>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="data_siswa.php">Data Siswa</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data_petugas.php">Data Petugas</a>
                </li> -->
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                        Perpulangan
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="data_perijinan.php">Perijinan</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="data_kedatangan.php">Kedatangan</a>
                    </div>
                </li>
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-toggle="dropdown" aria-expanded="false">
                        Ijin Laptop
                    </a>
                    <div class="dropdown-menu">
                        <a class="dropdown-item" href="data-perijinan-laptop.php">Perijinan Laptop</a>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="data-pengembalian-laptop.php">Pengembalian Laptop</a>
                    </div>
                </li>
                <!-- <li class="nav-item">
                    <a class="nav-link" href="data_perijinan.php">Data Perijinan</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="data_kedatangan.php">Data Kedatangan</a>
                </li> -->
                <li class="nav-item">
                    <a class="nav-link rounded-pill" href="../../logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container mt-4 mb-4">
        <div>
            <h4 class="mt-4 mb-4">Selamat datang <strong><?= $users['username'] ?></strong> di halaman utama homecoming</h4>
        </div>
        <div class="row mt-3">
            <div class="col-md-3 text-center">
                <div class="card text-white bg-primary mb-3 border-0 shadow-lg">
                    <div class="card-header">
                        <a href="data-users.php" class="btn btn-primary btn-sm rounded-pill"><b>Data Users</b></a>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Total : <?php echo $pdo->query("SELECT COUNT(*) FROM users")->fetchColumn(); ?></h5>
                        <p class="card-text">Melihat Data Users</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="card text-white bg-success mb-3 border-0 shadow-lg">
                    <div class="card-header">
                        <a href="data_siswa.php" class="btn btn-success btn-sm rounded-pill"><b>Data Siswa</b></a>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Total : <?php echo $pdo->query("SELECT COUNT(*) FROM siswa")->fetchColumn(); ?></h5>
                        <p class="card-text">Melihat Data Siswa</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="card text-white bg-warning mb-3 border-0 shadow-lg">
                    <div class="card-header">
                        <a href="data_petugas.php" class="btn btn-warning btn-sm rounded-pill text-white"><b>Data Petugas</b></a>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Total : <?php echo $pdo->query("SELECT COUNT(*) FROM petugas")->fetchColumn(); ?></h5>
                        <p class="card-text">Melihat Data Petugas</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3 text-center">
                <div class="card text-white bg-info mb-3 border-0 shadow-lg">
                    <div class="card-header">
                        <a href="data-pimpinan.php" class="btn btn-info btn-sm rounded-pill"><b>Data Pimpinan</b></a>
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Total : <?php echo $pdo->query("SELECT COUNT(*) FROM pimpinan")->fetchColumn(); ?></h5>
                        <p class="card-text">Melihat Data Pimpinan</p>
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