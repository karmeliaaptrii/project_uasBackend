<?php
require_once '../../app/helpers/SessionHelper.php';
require_once '../../app/config/DatabaseConnection.php';
require_once '../../app/models/Reservation.php';

SessionHelper::checkLogin();

$db = (new DatabaseConnection())->getConnection();
$reservationModel = new Reservation($db);
$histori = $reservationModel->getHistoryUser($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Riwayat Reservasi Saya</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Riwayat Reservasi Anda</h2>
        <a href="home.php" class="btn btn-outline-primary">Kembali ke Beranda</a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="ps-4">Tanggal</th>
                            <th>Jam</th>
                            <th>Layanan</th>
                            <th>Harga</th>
                            <th class="pe-4">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (empty($histori)): ?>
                            <tr>
                                <td colspan="5" class="text-center py-4">Belum ada riwayat reservasi.</td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($histori as $h): ?>
                            <tr>
                                <td class="ps-4"><?= htmlspecialchars($h['tanggal_booking']) ?></td>
                                <td><?= htmlspecialchars($h['jam_booking']) ?></td>
                                <td><?= htmlspecialchars($h['nama_layanan']) ?></td>
                                <td>Rp <?= number_format($h['harga'], 0, ',', '.') ?></td>
                                <td class="pe-4">
                                    <?php
                                        $badgeClass = '';
                                        if($h['status'] == 'Menunggu') $badgeClass = 'bg-warning text-dark';
                                        elseif($h['status'] == 'Disetujui') $badgeClass = 'bg-success';
                                        else $badgeClass = 'bg-danger';
                                    ?>
                                    <span class="badge <?= $badgeClass ?>"><?= htmlspecialchars($h['status']) ?></span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>