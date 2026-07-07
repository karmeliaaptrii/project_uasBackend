<?php
class Auth {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($nama, $email, $password, $role_id = 2) {
        $query = "INSERT INTO users (role_id, nama, email, password) VALUES (:role_id, :nama, :email, :password)";
        $stmt = $this->conn->prepare($query);

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        return $stmt->execute([
            ':role_id' => $role_id,
            ':nama' => htmlspecialchars($nama),
            ':email' => htmlspecialchars($email),
            ':password' => $hashed_password
        ]);
    }
}