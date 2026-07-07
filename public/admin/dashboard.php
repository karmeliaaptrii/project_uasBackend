<?php

session_start();

require_once '../../app/middleware/AdminMiddleware.php';

AdminMiddleware::check();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Dashboard Admin - Salon</title>
</head>
<body style="font-family: Arial; padding: 20px;">
    <h2>Dashboard Admin</h2>
    <p>Selamat Datang, <b><?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?></b>!</p>
    
    <nav>
        <ul>
            <li><a href="treatment.php">Kelola Data Layanan Salon</a></li>
            <li><a href="reservation.php">Cek Antrean Reservasi (Segera Hadir)</a></li>
            <li><a href="../customer/logout.php" style="color: red;">Logout</a></li>
        </ul>
    </nav>
</body>
</html>