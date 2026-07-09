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
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Layanan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">

    <div class="container py-5">
        
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fw-bold text-dark">Kelola Layanan Salon</h2>
            <a href="dashboard.php" class="btn btn-sm btn-outline-secondary">← Kembali ke Dashboard</a>
        </div>
        <hr>

        <div class="row g-4">
            
            <div class="col-md-4">
                <div class="card p-3 shadow-sm">
                    <h5 class="fw-bold mb-3"><?= $editData ? 'Edit Layanan' : 'Tambah Layanan Baru' ?></h5>
                    
                    <form method="POST" action="">
                        <?php if ($editData): ?>
                            <input type="hidden" name="id" value="<?= $editData['id'] ?>">
                        <?php endif; ?>

                        <div class="mb-3">
                            <label class="form-label small text-muted">Nama Layanan</label>
                            <input type="text" name="nama_layanan" class="form-control" placeholder="Msl: Smoothing" value="<?= $editData ? htmlspecialchars($editData['nama_layanan']) : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Harga</label>
                            <input type="number" name="harga" class="form-control" placeholder="Msl: 150000" value="<?= $editData ? $editData['harga'] : '' ?>" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small text-muted">Durasi (Menit)</label>
                            <input type="number" name="durasi_menit" class="form-control" placeholder="Durasi (Menit)" value="<?= $editData ? $editData['durasi_menit'] : '' ?>" required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" name="<?= $editData ? 'update' : 'tambah' ?>" class="btn btn-primary fw-semibold">
                                <?= $editData ? 'Simpan Perubahan' : 'Simpan' ?>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="col-md-8">
                <div class="card p-3 shadow-sm">
                    <h5 class="fw-bold mb-3">Daftar Layanan Saat Ini</h5>
                    
                    <table class="table table-hover table-striped mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>No</th>
                                <th>Nama Layanan</th>
                                <th>Harga</th>
                                <th>Durasi</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $i = 1; foreach ($treatments as $t): ?>
                            <tr>
                                <td><?= $i++ ?></td>
                                <td><strong><?= htmlspecialchars($t['nama_layanan']) ?></strong></td>
                                <td class="text-success fw-semibold">Rp <?= number_format($t['harga'], 0, ',', '.') ?></td>
                                <td><?= htmlspecialchars($t['durasi_menit']) ?> Menit</td>
                                <td>
                                    <a href="treatment.php?edit=<?= $t['id'] ?>" class="btn btn-sm btn-warning text-white me-1">Edit</a>
                                    <a href="treatment.php?hapus=<?= $t['id'] ?>" onclick="return confirm('Yakin hapus?')" class="btn btn-sm btn-danger">Hapus</a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>

        </div> 
    </div> 
</body>
</html>