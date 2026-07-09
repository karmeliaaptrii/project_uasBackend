<?php
require_once '../../app/middleware/AdminMiddleware.php';
require_once '../../app/config/DatabaseConnection.php';
require_once '../../app/models/Reservation.php';
require_once '../../app/helpers/MailHelper.php'; // Panggil helper email

AdminMiddleware::check();

$db = (new DatabaseConnection())->getConnection();
$reservationModel = new Reservation($db);

if (isset($_GET['aksi']) && isset($_GET['id'])) {
    $id = $_GET['id'];
    $aksi = $_GET['aksi'];
    
    if ($aksi == 'setuju') {
        $reservationModel->updateStatus($id, 'Disetujui');
        
        MailHelper::sendKonfirmasi($_GET['email'], $_GET['nama'], $_GET['layanan'], $_GET['tgl'], $_GET['jam']);
        
        echo "<script>alert('Pesanan Disetujui & Email Terkirim!'); window.location='reservation.php';</script>";
    } elseif ($aksi == 'tolak') {
        $reservationModel->updateStatus($id, 'Dibatalkan');
        echo "<script>alert('Pesanan Dibatalkan!'); window.location='reservation.php';</script>";
    }
}

$antrean = $reservationModel->getAllAdmin();
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Reservasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Kelola Antrean Reservasi</h2>
        <a href="dashboard.php" class="btn btn-secondary">Kembali ke Dashboard</a>
    </div>

    <div class="card shadow-sm">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="table-dark">
                        <tr>
                            <th>Pelanggan</th>
                            <th>Layanan</th>
                            <th>Jadwal</th>
                            <th>Bukti</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($antrean as $a): ?>
                        <tr>
                            <td><?= htmlspecialchars($a['nama_pelanggan']) ?></td>
                            <td><?= htmlspecialchars($a['nama_layanan']) ?></td>
                            <td><?= htmlspecialchars($a['tanggal_booking']) ?> <br> <small class="text-muted"><?= htmlspecialchars($a['jam_booking']) ?></small></td>
                            <td>
                                <a href="../../uploads/payment/<?= htmlspecialchars($a['bukti_pembayaran']) ?>" target="_blank" class="btn btn-sm btn-info text-white">Lihat</a>
                            </td>
                            <td>
                                <?php 
                                    $badge = ($a['status'] == 'Menunggu') ? 'bg-warning' : (($a['status'] == 'Disetujui') ? 'bg-success' : 'bg-danger');
                                ?>
                                <span class="badge <?= $badge ?>"><?= htmlspecialchars($a['status']) ?></span>
                            </td>
                            <td>
                                <?php if($a['status'] == 'Menunggu'): ?>
                                    <a href="reservation.php?aksi=setuju&id=<?= $a['id'] ?>&email=<?= $a['email'] ?>&nama=<?= $a['nama_pelanggan'] ?>&layanan=<?= $a['nama_layanan'] ?>&tgl=<?= $a['tanggal_booking'] ?>&jam=<?= $a['jam_booking'] ?>" class="btn btn-sm btn-success" onclick="return confirm('Setujui pesanan ini?')">Setujui</a>
                                    <a href="reservation.php?aksi=tolak&id=<?= $a['id'] ?>" class="btn btn-sm btn-danger" onclick="return confirm('Tolak pesanan ini?')">Tolak</a>
                                <?php else: ?>
                                    <span class="text-muted small">Selesai</span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>