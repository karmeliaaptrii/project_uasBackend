<?php
require_once '../../app/middleware/AdminMiddleware.php';
require_once '../../app/config/DatabaseConnection.php';
require_once '../../app/models/Treatment.php';

AdminMiddleware::check();

$db = (new DatabaseConnection())->getConnection();
$treatmentModel = new Treatment($db);

$editData = null;
if (isset($_GET['edit'])) {
    $editData = $treatmentModel->getById($_GET['edit']);
}

if (isset($_POST['tambah'])) {
    if ($treatmentModel->add($_POST['nama_layanan'], $_POST['harga'], $_POST['durasi_menit'])) {
        echo "<script>alert('Layanan berhasil ditambahkan!'); window.location='treatment.php';</script>";
    }
}

if (isset($_POST['update'])) {
    if ($treatmentModel->update($_POST['id'], $_POST['nama_layanan'], $_POST['harga'], $_POST['durasi_menit'])) {
        echo "<script>alert('Layanan berhasil diperbarui!'); window.location='treatment.php';</script>";
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

    <h3><?= $editData ? 'Edit Layanan' : 'Tambah Layanan Baru' ?></h3>
    <form method="POST" action="">
        <?php if ($editData): ?>
            <input type="hidden" name="id" value="<?= $editData['id'] ?>">
        <?php endif; ?>

        <input type="text" name="nama_layanan" placeholder="Nama Layanan (Msl: Smoothing)" 
               value="<?= $editData ? htmlspecialchars($editData['nama_layanan']) : '' ?>" required>
        
        <input type="number" name="harga" placeholder="Harga (Msl: 150000)" 
               value="<?= $editData ? $editData['harga'] : '' ?>" required>
        
        <input type="number" name="durasi_menit" placeholder="Durasi (Menit)" 
               value="<?= $editData ? $editData['durasi_menit'] : '' ?>" required>
        
        <button type="submit" name="<?= $editData ? 'update' : 'tambah' ?>">
            <?= $editData ? 'Simpan Perubahan' : 'Simpan' ?>
        </button>

        <?php if ($editData): ?>
            <a href="treatment.php" style="margin-left: 10px; color: gray; text-decoration: none;">Batal</a>
        <?php endif; ?>
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
                <a href="treatment.php?edit=<?= $t['id'] ?>" style="color:blue; margin-right: 15px; text-decoration: none;">Edit</a>
                <a href="treatment.php?hapus=<?= $t['id'] ?>" onclick="return confirm('Yakin hapus?')" style="color:red; text-decoration: none;">Hapus</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
</body>
</html>