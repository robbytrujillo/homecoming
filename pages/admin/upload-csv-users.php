<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin') {
    header('Location: ../../login.php');
    exit;
}
require '../../includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['csv_file'])) {
    $file = $_FILES['csv_file']['tmp_name'];
    $handle = fopen($file, 'r');

    // Skip header row
    fgetcsv($handle, 1000, ',');

    while (($data = fgetcsv($handle, 1000, ',')) !== FALSE) {
        $username = $data[0];
        $password = $data[1];
        $role = $data[2];

        // Insert data ke database
        $stmt = $pdo->prepare("INSERT INTO users (username, password, role) VALUES (?, ?, ?)");
        $stmt->execute([$username, $password, $role, ]);
    }

    fclose($handle);
    header('Location: data-users.php');
    exit;
}
?>