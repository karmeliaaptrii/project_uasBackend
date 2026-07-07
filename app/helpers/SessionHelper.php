<?php
class SessionHelper {
    public static function start() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
    public static function checkLogin() {
        self::start();
        if (!isset($_SESSION['user_id'])) {
            header("Location: ../public/login.php");
            exit();
        }
    }
}