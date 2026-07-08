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
<html>
<head><title>Riwayat Reservasi Saya</title></head>
<body style="font-family: Arial; padding: 20px;">
    <h2>Riwayat Reservasi Anda</h2>
    <a href="home.php">Kembali ke Beranda</a>
    <hr>

    <table border="1" cellpadding="10" cellspacing="0" width="100%">
        <tr style="background-color: #f2f2f2;">
            <th>Tanggal</th>
            <th>Jam</th>
            <th>Layanan</th>
            <th>Harga</th>
            <th>Status</th>
        </tr>
        <?php foreach ($histori as $h): ?>
        <tr>
            <td><?= htmlspecialchars($h['tanggal_booking']) ?></td>
            <td><?= htmlspecialchars($h['jam_booking']) ?></td>
            <td><?= htmlspecialchars($h['nama_layanan']) ?></td>
            <td>Rp <?= number_format($h['harga'], 0, ',', '.') ?></td>
            <td>
                <?php
                    if($h['status'] == 'Menunggu') echo "<b style='color:orange;'>Menunggu</b>";
                    elseif($h['status'] == 'Disetujui') echo "<b style='color:green;'>Disetujui</b>";
                    else echo "<b style='color:red;'>Dibatalkan</b>";
                ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>