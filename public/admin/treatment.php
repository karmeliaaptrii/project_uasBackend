<?php
require_once '../../app/middleware/AdminMiddleware.php';
require_once '../../app/config/DatabaseConnection.php';
require_once '../../app/models/Treatment.php';

AdminMiddleware::check();

$db = (new DatabaseConnection())->getConnection();
$treatmentModel = new Treatment($db);

if (isset($_POST['tambah'])) {
    if ($treatmentModel->add($_POST['nama_layanan'], $_POST['harga'], $_POST['durasi_menit'])) {
        echo "<script>alert('Layanan berhasil ditambahkan!'); window.location='treatment.php';</script>";
    }
}

if (isset($_GET['hapus'])) {
    if ($treatmentModel->delete($_GET['hapus'])) {
        echo "<script>alert('Layanan berhasil dihapus!'); window.location='treatment.php';</script>";
    }
}

$treatments = $treatmentModel->getAll();
?>

<!DOCTYPE html>
<html>
<head><title>Kelola Layanan</title></head>
<body style="font-family: Arial; padding: 20px;">
    <h2>Kelola Layanan Salon</h2>
    <a href="dashboard.php">Kembali ke Dashboard</a>
    <hr>

    <h3>Tambah Layanan Baru</h3>
    <form method="POST" action="">
        <input type="text" name="nama_layanan" placeholder="Nama Layanan (Msl: Smoothing)" required>
        <input type="number" name="harga" placeholder="Harga (Msl: 150000)" required>
        <input type="number" name="durasi_menit" placeholder="Durasi (Menit)" required>
        <button type="submit" name="tambah">Simpan</button>
    </form>

    <h3>Daftar Layanan Saat Ini</h3>
    <table border="1" cellpadding="10" cellspacing="0">
        <tr>
            <th>No</th>
            <th>Nama Layanan</th>
            <th>Harga</th>
            <th>Durasi</th>
            <th>Aksi</th>
        </tr>
        <?php $i = 1; foreach ($treatments as $t): ?>
        <tr>
            <td><?= $i++ ?></td>
            <td><?= htmlspecialchars($t['nama_layanan']) ?></td>
            <td>Rp <?= number_format($t['harga'], 0, ',', '.') ?></td>
            <td><?= htmlspecialchars($t['durasi_menit']) ?> Menit</td>
            <td>
                <a href="treatment.php?hapus=<?= $t['id'] ?>" onclick="return confirm('Yakin hapus?')" style="color:red;">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>