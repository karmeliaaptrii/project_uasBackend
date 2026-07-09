<?php
class Auth {
    private PDO $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function register(string $nama, string $email, string $password, int $role_id = 2) {
        $query = "INSERT INTO users (role_id, nama, email, password) VALUES (:role_id, :nama, :email, :password)";
        $stmt = $this->conn->prepare($query);

        $hashed_password = password_hash($password, PASSWORD_DEFAULT);

        return $stmt->execute([
            ':role_id' => $role_id,
            ':nama' => $nama,
            ':email' => $email,
            ':password' => $hashed_password
        ]);
    }
    public function login(string $email, string $password) {
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            return $user;
        }
        return false;
    }
}