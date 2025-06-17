<?php

class Gedung {
    // Koneksi database dan nama tabel
    private $conn;
    private $table_name = "gedung";

    // Properti objek
    public $id_gedung;
    public $nama_gedung;
    public $lantai_gedung;

    // Konstruktor dengan $db sebagai koneksi database
    public function __construct($db) {
        $this->conn = $db;
    }

    // Membaca semua gedung
    public function read() {
        $query = "SELECT id_gedung, nama_gedung, lantai_gedung FROM " . $this->table_name . " ORDER BY id_gedung DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Membuat gedung baru
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET nama_gedung=:nama_gedung, lantai_gedung=:lantai_gedung";
        $stmt = $this->conn->prepare($query);

        $this->nama_gedung = htmlspecialchars(strip_tags($this->nama_gedung));
        $this->lantai_gedung = htmlspecialchars(strip_tags($this->lantai_gedung));

        $stmt->bindParam(":nama_gedung", $this->nama_gedung);
        $stmt->bindParam(":lantai_gedung", $this->lantai_gedung);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Membaca satu gedung berdasarkan ID
    public function readOne() {
        $query = "SELECT id_gedung, nama_gedung, lantai_gedung FROM " . $this->table_name . " WHERE id_gedung = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_gedung);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->nama_gedung = $row['nama_gedung'];
            $this->lantai_gedung = $row['lantai_gedung'];
        }
    }

    // Memperbarui gedung
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET nama_gedung = :nama_gedung, lantai_gedung = :lantai_gedung WHERE id_gedung = :id_gedung";
        $stmt = $this->conn->prepare($query);

        $this->nama_gedung = htmlspecialchars(strip_tags($this->nama_gedung));
        $this->lantai_gedung = htmlspecialchars(strip_tags($this->lantai_gedung));
        $this->id_gedung = htmlspecialchars(strip_tags($this->id_gedung));

        $stmt->bindParam(':nama_gedung', $this->nama_gedung);
        $stmt->bindParam(':lantai_gedung', $this->lantai_gedung);
        $stmt->bindParam(':id_gedung', $this->id_gedung);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Menghapus gedung
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_gedung = ?";
        $stmt = $this->conn->prepare($query);

        $this->id_gedung = htmlspecialchars(strip_tags($this->id_gedung));

        $stmt->bindParam(1, $this->id_gedung);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>