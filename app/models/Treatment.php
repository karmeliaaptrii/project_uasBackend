<?php
class Treatment {
    private ?PDO $conn;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    // Tampilkan semua layanan
    public function getAll() {
        $query = "SELECT * FROM treatments ORDER BY id DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Tambah layanan baru
    public function add(string $nama, int $harga, int $durasi) {
        $query = "INSERT INTO treatments (nama_layanan, harga, durasi_menit) VALUES (?, ?, ?)";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([
            htmlspecialchars($nama), 
            htmlspecialchars($harga), 
            htmlspecialchars($durasi)
        ]);
    }

    // Hapus layanan
    public function delete(int $id) {
        $query = "DELETE FROM treatments WHERE id = ?";
        $stmt = $this->conn->prepare($query);
        return $stmt->execute([$id]);
    }
}
?>