<?php
class Reservation {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function create($user_id, $treatment_id, $tanggal, $jam, $file) {
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
}
?>