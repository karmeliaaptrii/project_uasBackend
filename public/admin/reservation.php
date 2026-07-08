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
<html>
<head><title>Kelola Reservasi Masuk</title></head>
<body style="font-family: Arial; padding: 20px;">
    <h2>Kelola Antrean Reservasi</h2>
    <a href="dashboard.php">Kembali ke Dashboard</a>
    <hr>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr style="background-color: #ddd;">
            <th>Nama Pelanggan</th>
            <th>Layanan</th>
            <th>Jadwal</th>
            <th>Bukti Bayar</th>
            <th>Status</th>
            <th>Aksi Admin</th>
        </tr>
        <?php foreach ($antrean as $a): ?>
        <tr>
            <td><?= htmlspecialchars($a['nama_pelanggan']) ?></td>
            <td><?= htmlspecialchars($a['nama_layanan']) ?></td>
            <td><?= htmlspecialchars($a['tanggal_booking']) ?> Pukul <?= htmlspecialchars($a['jam_booking']) ?></td>
            <td>
                <a href="../../uploads/payment/<?= htmlspecialchars($a['bukti_pembayaran']) ?>" target="_blank">Lihat Bukti</a>
            </td>
            <td><b><?= htmlspecialchars($a['status']) ?></b></td>
            <td>
                <?php if($a['status'] == 'Menunggu'): ?>
                    <a href="reservation.php?aksi=setuju&id=<?= $a['id'] ?>&email=<?= $a['email'] ?>&nama=<?= $a['nama_pelanggan'] ?>&layanan=<?= $a['nama_layanan'] ?>&tgl=<?= $a['tanggal_booking'] ?>&jam=<?= $a['jam_booking'] ?>" style="color:green;" onclick="return confirm('Setujui pesanan ini?')">Setujui</a> | 
                    <a href="reservation.php?aksi=tolak&id=<?= $a['id'] ?>" style="color:red;" onclick="return confirm('Tolak pesanan ini?')">Tolak</a>
                <?php else: ?>
                    Selesai
                <?php endif; ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>