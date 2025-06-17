<?php

class Riwayat {
    // Koneksi database dan nama tabel
    private $conn;
    private $table_name = "riwayat";

    // Properti objek
    public $id_riwayat;
    public $waktu_perubahan;
    public $tanggal_perubahan;
    public $keterangan;

    // Konstruktor dengan $db sebagai koneksi database
    public function __construct($db) {
        $this->conn = $db;
    }

    // Membaca semua riwayat
    public function read() {
        $query = "SELECT id_riwayat, waktu_perubahan, tanggal_perubahan, keterangan FROM " . $this->table_name . " ORDER BY tanggal_perubahan DESC, waktu_perubahan DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Membuat riwayat baru
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET waktu_perubahan=:waktu_perubahan, tanggal_perubahan=:tanggal_perubahan, keterangan=:keterangan";
        $stmt = $this->conn->prepare($query);

        $this->waktu_perubahan = htmlspecialchars(strip_tags($this->waktu_perubahan));
        $this->tanggal_perubahan = htmlspecialchars(strip_tags($this->tanggal_perubahan));
        $this->keterangan = htmlspecialchars(strip_tags($this->keterangan));

        $stmt->bindParam(":waktu_perubahan", $this->waktu_perubahan);
        $stmt->bindParam(":tanggal_perubahan", $this->tanggal_perubahan);
        $stmt->bindParam(":keterangan", $this->keterangan);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Membaca satu riwayat berdasarkan ID
    public function readOne() {
        $query = "SELECT id_riwayat, waktu_perubahan, tanggal_perubahan, keterangan FROM " . $this->table_name . " WHERE id_riwayat = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_riwayat);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->waktu_perubahan = $row['waktu_perubahan'];
            $this->tanggal_perubahan = $row['tanggal_perubahan'];
            $this->keterangan = $row['keterangan'];
        }
    }

    // Memperbarui riwayat
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET waktu_perubahan = :waktu_perubahan, tanggal_perubahan = :tanggal_perubahan, keterangan = :keterangan WHERE id_riwayat = :id_riwayat";
        $stmt = $this->conn->prepare($query);

        $this->waktu_perubahan = htmlspecialchars(strip_tags($this->waktu_perubahan));
        $this->tanggal_perubahan = htmlspecialchars(strip_tags($this->tanggal_perubahan));
        $this->keterangan = htmlspecialchars(strip_tags($this->keterangan));
        $this->id_riwayat = htmlspecialchars(strip_tags($this->id_riwayat));

        $stmt->bindParam(':waktu_perubahan', $this->waktu_perubahan);
        $stmt->bindParam(':tanggal_perubahan', $this->tanggal_perubahan);
        $stmt->bindParam(':keterangan', $this->keterangan);
        $stmt->bindParam(':id_riwayat', $this->id_riwayat);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Menghapus riwayat
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_riwayat = ?";
        $stmt = $this->conn->prepare($query);

        $this->id_riwayat = htmlspecialchars(strip_tags($this->id_riwayat));

        $stmt->bindParam(1, $this->id_riwayat);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>