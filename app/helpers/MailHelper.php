<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once '../../vendor/autoload.php';

class MailHelper {

    private $conn;

    public static function sendKonfirmasi($email_tujuan, $nama, $layanan, $tanggal, $jam) {
        $mail = new PHPMailer(true);
        try {
            $mail->isSMTP();
            $mail->Host       = 'smtp.gmail.com';
            $mail->SMTPAuth   = true;
            $mail->Username   = 'sarjanati534@gmail.com';
            $mail->Password   = 'bziflynuggefseed';          
            $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
            $mail->Port       = 465;

            $mail->setFrom('sarjanati534@gmail.com', 'Salon Cantik');
            $mail->addAddress($email_tujuan, $nama); 

            $mail->isHTML(true);
            $mail->Subject = 'Konfirmasi Reservasi Salon Disetujui';
            $mail->Body    = "Halo <b>$nama</b>,<br><br>Kabar gembira! Reservasi Anda untuk layanan <b>$layanan</b> pada tanggal <b>$tanggal</b> jam <b>$jam</b> telah <b>DISETUJUI</b> oleh Admin.<br><br>Silakan datang tepat waktu.<br><br>Terima kasih,<br>Salon Glamour";

            $mail->send();
            return true;
        } catch (Exception $e) {
            return false;
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

    public function getHistoryUser($user_id) {
        $query = "SELECT r.*, t.nama_layanan, t.harga
                  FROM reservations r
                  JOIN treatments t ON r.treatment_id = t.id
                  WHERE r.user_id = ?
                  ORDER BY r.tanggal_booking DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$user_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($id, $status) {
        $query = "UPDATE reservations SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$status, $id]);
    }
}
?>