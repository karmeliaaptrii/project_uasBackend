<?php

session_start();

require_once '../../app/helpers/SessionHelper.php';
require_once '../../app/config/DatabaseConnection.php';
require_once '../../app/models/Treatment.php';

SessionHelper::checkLogin();

$db = (new DatabaseConnection())->getConnection();
$treatmentModel = new Treatment($db);
$treatments = $treatmentModel->getAll();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Katalog Layanan - Salon</title>
</head>
<body style="font-family: Arial; padding: 20px;">
    <h2>Selamat Datang, <?= htmlspecialchars($_SESSION['nama'] ?? 'Pelanggan') ?>!</h2>
    <p>Silakan lihat katalog layanan kami sebelum melakukan booking.</p>
    
    <a href="booking.php" style="background-color: blue; color: white; padding: 10px; text-decoration: none;">Buat Reservasi Sekarang</a>
    <a href="history.php" style="margin-left: 10px;">Riwayat Saya</a>
    <a href="logout.php" style="color: red; margin-left: 10px;">Logout</a>
    <hr>

    <h3>Katalog Layanan Kami:</h3>
    <div style="display: flex; gap: 20px; flex-wrap: wrap;">
        <?php foreach ($treatments as $t): ?>
            <div style="border: 1px solid #ccc; padding: 15px; width: 200px;">
                <h4><?= htmlspecialchars($t['nama_layanan']) ?></h4>
                <p><b>Harga:</b> Rp <?= number_format($t['harga'], 0, ',', '.') ?></p>
                <p><b>Estimasi Waktu:</b> <?= htmlspecialchars($t['durasi_menit']) ?> Menit</p>
            </div>
        <?php endforeach; ?>
    </div>
</body>
</html>