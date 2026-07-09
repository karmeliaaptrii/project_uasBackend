<?php
class Treatment {
    private PDO $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function getAll() {
        $query = "SELECT * FROM treatments ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add(string $nama, int $harga, int $durasi) {
        $query = "INSERT INTO treatments (nama_layanan, harga, durasi_menit) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $nama, 
            $harga, 
            $durasi
        ]);
    }

    public function delete(int $id) {
        $query = "DELETE FROM treatments WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }

    public function getById(int $id) {
        $query = "SELECT * FROM treatments WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function update(int $id, string $nama, int $harga, int $durasi) {
        $query = "UPDATE treatments SET nama_layanan = ?, harga = ?, durasi_menit = ? WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            $nama, 
            $harga, 
            $durasi, 
            $id
        ]);
    }
}
?>