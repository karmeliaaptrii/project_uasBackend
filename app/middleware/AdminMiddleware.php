<?php
class AdminMiddleware {
    public static function check() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            echo "<script>alert('Akses Ditolak! Anda bukan Admin.'); window.location='../login.php';</script>";
            exit;
        }
    }
}
?>