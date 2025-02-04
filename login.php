<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - Homecoming</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <br><br><br><br>
    <div class="container mt-5">
        <div class="row justify-content-center mt-3">
            <div class="col-md-6 col-lg-4">
                <div class="card">
                    <div class="card-header">
                        <!-- <h3 class="text-center">Login</h3> -->
                        <img src="./assets/homecoming-logo.png" style="width: 200px; margin-left: 15%; margin-top: 5%">
                        <marquee><h6 style="text-align: center; color: rgb(54, 50, 50)">Selamat Datang di Sistem Informasi PuDaMah - Pulang Datang Ma'had, Silahkan login terlebih dahulu!</h6></marquee>
                    </div>
                    <div class="card-body">
                        <form action="includes/auth.php" method="POST">
                            <div class="form-group">
                                <label for="username">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <button type="submit" class="btn btn-success btn-block">Login</button>
                        </form>
                    </div>
                    <div class="card-footer text-center py-3">
                        <div class="small">&copy; <?php echo date('Y'); ?> IT Development IHBS </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>