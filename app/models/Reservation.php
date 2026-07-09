<?php
class Reservation {
    private PDO $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function create(int $user_id, int $treatment_id, string $tanggal, string $jam, array $file) {
        $fileName = $file['name'];
        $fileTmpName = $file['tmp_name'];
        $fileSize = $file['size'];
        $fileError = $file['error'];

        $fileExt = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
        $allowedExt = ['jpg', 'jpeg', 'png'];

        if (!in_array($fileExt, $allowedExt)) {
            return "Ekstensi file tidak diizinkan! Hanya boleh JPG, JPEG, atau PNG.";
        }

        if ($fileError !== 0) {
            return "Terjadi error saat mengupload file.";
        }

        if ($fileSize > 2000000) {
            return "Ukuran file terlalu besar! Maksimal 2MB.";
        }

        $newFileName = uniqid('PAY-', true) . "." . $fileExt;
        
        $fileDestination = '../../uploads/payment/' . $newFileName;

        if (move_uploaded_file($fileTmpName, $fileDestination)) {
            $query = "INSERT INTO reservations (user_id, treatment_id, tanggal_booking, jam_booking, bukti_pembayaran, status) 
                      VALUES (?, ?, ?, ?, ?, 'Menunggu')";
            
            $stmt = $this->conn->prepare($query);
            $success = $stmt->execute([
                $user_id,
                $treatment_id,
                htmlspecialchars($tanggal),
                htmlspecialchars($jam),
                $newFileName
            ]);

            if ($success) {
                return true; 
            } else {
                return "Gagal menyimpan data transaksi ke database.";
            }
        } else {
            return "Gagal memindahkan file bukti pembayaran ke server.";
        }
    }
    public function getAllAdmin() {
        $query = "SELECT r.*, u.nama as nama_pelanggan, u.email, t.nama_layanan, t.harga
                  FROM reservations r
                  JOIN users u ON r.user_id = u.id
                  JOIN treatments t ON r.treatment_id = t.id
                  ORDER BY r.tanggal_booking DESC, r.jam_booking DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getHistoryUser(int $user_id) {
        $query = "SELECT r.*, t.nama_layanan, t.harga
                  FROM reservations r
                  JOIN treatments t ON r.treatment_id = t.id
                  WHERE r.user_id = ?
                  ORDER BY r.tanggal_booking DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus(int $id, string $status) {
        $query = "UPDATE reservations SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$status, $id]);
    }
}
?>