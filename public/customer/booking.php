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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Reservasi Salon</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white text-center">
                    <h4 class="mb-0">Form Reservasi Layanan</h4>
                </div>
                <div class="card-body p-4">
                    
                    <?php if(isset($error)): ?>
                        <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
                    <?php endif; ?>

                    <form method="POST" action="" enctype="multipart/form-data">
                        <div class="mb-3">
                            <label class="form-label">Pilih Layanan Salon:</label>
                            <select name="treatment_id" class="form-select" required>
                                <option value="">-- Pilih Layanan --</option>
                                <?php foreach($treatments as $t): ?>
                                    <option value="<?= $t['id'] ?>">
                                        <?= htmlspecialchars($t['nama_layanan']) ?> - Rp <?= number_format($t['harga'], 0, ',', '.') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Tanggal Booking:</label>
                                <input type="date" name="tanggal_booking" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Jam Booking:</label>
                                <input type="time" name="jam_booking" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Upload Bukti Pembayaran:</label>
                            <div class="alert alert-info py-2" style="font-size: 0.85rem;">
                                Transfer ke BCA: <strong>123-456-789</strong> a/n Salon Cantik
                            </div>
                            <input type="file" name="bukti_pembayaran" class="form-control" required>
                            <small class="text-muted">Max 2MB (JPG/PNG)</small>
                        </div>

                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg">Kirim Reservasi</button>
                            <a href="home.php" class="btn btn-outline-secondary">Kembali ke Beranda</a>
                        </div>
                    </form>

                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>