<?php

session_start();

require_once '../../app/middleware/AdminMiddleware.php';

AdminMiddleware::check();

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Admin - Salon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <nav class="navbar navbar-expand-lg navbar-dark bg-dark mb-4">
        <div class="container">
            <a class="navbar-brand" href="#">Salon Admin</a>
            <div class="navbar-nav ms-auto">
                <a href="../logout.php" class="nav-link text-danger">Logout</a>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="bg-white p-4 rounded shadow-sm mb-4">
            <h2 class="h4">Dashboard Admin</h2>
            <p class="text-muted">Selamat datang kembali, <strong><?= htmlspecialchars($_SESSION['nama'] ?? 'Admin') ?></strong>!</p>
        </div>

        <div class="row">
            <div class="col-md-6 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Kelola Layanan</h5>
                        <p class="card-text">Tambahkan, edit, atau hapus data layanan salon.</p>
                        <a href="treatment.php" class="btn btn-primary">Buka Menu</a>
                    </div>
                </div>
            </div>

            <div class="col-md-6 mb-3">
                <div class="card shadow-sm border-0">
                    <div class="card-body">
                        <h5 class="card-title">Reservasi</h5>
                        <p class="card-text">Cek dan kelola antrean reservasi pelanggan.</p>
                        <a href="reservation.php" class="btn btn-success">Buka Menu</a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>