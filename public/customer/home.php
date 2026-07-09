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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Layanan - Salon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand fw-bold" href="#">Salon Cantik</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="history.php">Riwayat Saya</a></li>
                    <li class="nav-item"><a class="nav-link text-warning fw-bold" href="../logout.php">Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container pb-5">
        <div class="mb-4">
            <h2 class="h3">Selamat Datang, <?= htmlspecialchars($_SESSION['nama'] ?? 'Pelanggan') ?>!</h2>
            <p class="text-muted">Silakan pilih layanan di bawah ini dan buat reservasi kamu.</p>
            <a href="booking.php" class="btn btn-primary btn-lg shadow-sm">Buat Reservasi Sekarang</a>
        </div>

        <hr class="my-4">

        <h3 class="mb-4">Katalog Layanan Kami:</h3>
        
        <div class="row row-cols-1 row-cols-md-3 g-4">
            <?php foreach ($treatments as $t): ?>
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title text-primary"><?= htmlspecialchars($t['nama_layanan']) ?></h5>
                        <p class="card-text mb-1">
                            <strong>Harga:</strong> Rp <?= number_format($t['harga'], 0, ',', '.') ?>
                        </p>
                        <p class="card-text text-muted">
                            <small>Estimasi Waktu: <?= htmlspecialchars($t['durasi_menit']) ?> Menit</small>
                        </p>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>