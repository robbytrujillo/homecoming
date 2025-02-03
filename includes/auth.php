<?php
session_start();
require 'db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Cari user berdasarkan username
    $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->execute([$username]);
    $user = $stmt->fetch();

    // Validasi password (tanpa hash, langsung bandingkan teks biasa)
    if ($user && $password === $user['password']) {
        // Login berhasil
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Redirect berdasarkan role
        switch ($user['role']) {
            case 'admin':
                header('Location: ../pages/admin/dashboard.php');
                break;
            case 'petugas':
                header('Location: ../pages/petugas/dashboard.php');
                break;
            case 'siswa':
                header('Location: ../pages/siswa/form_perijinan.php');
                break;
            default:
                header('Location: ../login.php');
                break;
        }
        exit;
    } else {
        // Login gagal
        echo "<script>alert('Username atau Password salah!'); window.location='../login.php';</script>";
    }

    // Cari user berdasarkan username (nomor induk untuk siswa)
    $stmts = $pdo->prepare("SELECT * FROM siswa WHERE nomor_induk = ?");
    $stmts->execute([$username]);
    $user = $stmts->fetch();

    // Validasi password (tanpa hash, langsung bandingkan teks biasa)
    if ($user && $password === '123456') {
        // Login berhasil
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = 'siswa';

        // Redirect ke halaman siswa
        header('Location: ../pages/siswa/dashboard.php');
        exit;
    } else {
        // Login gagal
        echo "<script>alert('Nomor Induk atau Password salah!'); window.location='../login.php';</script>";
    }

    // Cari petugas berdasarkan NIP
    $stmtp = $pdo->prepare("SELECT * FROM petugas WHERE nip = ?");
    $stmtp->execute([$username]);
    $petugas = $stmtp->fetch();

    // Validasi password (password default: gurudanmudarris)
    if ($petugas && $password === 'gurudanmudarris') {
        // Login berhasil
        $_SESSION['user_id'] = $petugas['id'];
        $_SESSION['role'] = 'petugas';

        // Redirect ke halaman petugas
        header('Location: ../pages/petugas/dashboard.php');
        exit;
    } else {
        // Login gagal
        echo "<script>alert('NIP atau Password salah!'); window.location='../login.php';</script>";
    }

}


?>