<?php
require_once '../../app/helpers/SessionHelper.php';
require_once '../../app/config/DatabaseConnection.php';
require_once '../../app/models/Treatment.php';
require_once '../../app/models/Reservation.php';

SessionHelper::checkLogin();

$db = (new DatabaseConnection())->getConnection();

$treatmentModel = new Treatment($db);
$treatments = $treatmentModel->getAll();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $reservationModel = new Reservation($db);
    
    $result = $reservationModel->create(
        $_SESSION['user_id'],
        $_POST['treatment_id'],
        $_POST['tanggal_booking'],
        $_POST['jam_booking'],
        $_FILES['bukti_pembayaran'] 
    );

    if ($result === true) {
        echo "<script>alert('Booking Berhasil! Menunggu konfirmasi admin.'); window.location='history.php';</script>";
    } else {
        $error = $result; 
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Form Reservasi Salon</title></head>
<body style="font-family: Arial; padding: 20px;">
    <h2>Form Reservasi Layanan Salon</h2>
    <a href="home.php">Kembali ke Beranda</a>
    <hr>

    <?php if(isset($error)) echo "<p style='color:red; font-weight:bold;'>$error</p>"; ?>

    <form method="POST" action="" enctype="multipart/form-data">
        <label>Pilih Layanan Salon:</label><br>
        <select name="treatment_id" required style="padding: 5px; width: 250px;">
            <option value="">-- Pilih Layanan --</option>
            <?php foreach($treatments as $t): ?>
                <option value="<?= $t['id'] ?>">
                    <?= htmlspecialchars($t['nama_layanan']) ?> - Rp <?= number_format($t['harga'], 0, ',', '.') ?>
                </option>
            <?php endforeach; ?>
        </select>
        <br><br>

        <label>Tanggal Booking:</label><br>
        <input type="date" name="tanggal_booking" required style="padding: 5px;"><br><br>

        <label>Jam Booking:</label><br>
        <input type="time" name="jam_booking" required style="padding: 5px;"><br><br>

        <label>Upload Bukti Pembayaran (Max 2MB, JPG/PNG):</label><br>
        <p style="font-size: 12px; color: gray; margin: 0 0 5px 0;">Transfer ke Rekening BCA: 123-456-789 a/n Salon Glamour</p>
        <input type="file" name="bukti_pembayaran" required><br><br>

        <button type="submit" style="background-color: green; color: white; padding: 10px 20px; border: none; cursor: pointer;">Kirim Reservasi</button>
    </form>
</body>
</html>