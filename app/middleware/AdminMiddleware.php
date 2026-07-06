<?php
class AdminMiddleware {
    public static function check() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        // Jika belum login atau role-nya bukan 1 (Admin), lempar ke halaman login
        if (!isset($_SESSION['user_id']) || $_SESSION['role_id'] != 1) {
            echo "<script>alert('Akses Ditolak! Anda bukan Admin.'); window.location='../customer/login.php';</script>";
            exit;
        }
    }
}
?>