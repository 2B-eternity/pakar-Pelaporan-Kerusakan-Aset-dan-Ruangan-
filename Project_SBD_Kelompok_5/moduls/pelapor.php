<?php

class Pelapor {
    // Koneksi database dan nama tabel
    private $conn;
    private $table_name = "pelapor";

    // Properti objek
    public $Id_Pelapor;
    public $Nama_Pelapor;

    // Konstruktor dengan $db sebagai koneksi database
    public function __construct($db) {
        $this->conn = $db;
    }

    // Membaca semua pelapor
    public function read() {
        $query = "SELECT Id_Pelapor, Nama_Pelapor FROM " . $this->table_name . " ORDER BY Id_Pelapor DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Membuat pelapor baru
    public function create() {
        $query = "INSERT INTO " . $this->table_name . " SET Nama_Pelapor=:nama_pelapor";
        $stmt = $this->conn->prepare($query);

        $this->Nama_Pelapor = htmlspecialchars(strip_tags($this->Nama_Pelapor));

        $stmt->bindParam(":nama_pelapor", $this->Nama_Pelapor);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Membaca satu pelapor berdasarkan ID
    public function readOne() {
        $query = "SELECT Id_Pelapor, Nama_Pelapor FROM " . $this->table_name . " WHERE Id_Pelapor = ? LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->Id_Pelapor);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->Nama_Pelapor = $row['Nama_Pelapor'];
        }
    }

    // Memperbarui pelapor
    public function update() {
        $query = "UPDATE " . $this->table_name . " SET Nama_Pelapor = :nama_pelapor WHERE Id_Pelapor = :id_pelapor";
        $stmt = $this->conn->prepare($query);

        $this->Nama_Pelapor = htmlspecialchars(strip_tags($this->Nama_Pelapor));
        $this->Id_Pelapor = htmlspecialchars(strip_tags($this->Id_Pelapor));

        $stmt->bindParam(':nama_pelapor', $this->Nama_Pelapor);
        $stmt->bindParam(':id_pelapor', $this->Id_Pelapor);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Menghapus pelapor
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE Id_Pelapor = ?";
        $stmt = $this->conn->prepare($query);

        $this->Id_Pelapor = htmlspecialchars(strip_tags($this->Id_Pelapor));

        $stmt->bindParam(1, $this->Id_Pelapor);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>