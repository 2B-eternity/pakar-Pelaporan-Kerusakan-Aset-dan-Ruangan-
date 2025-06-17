<?php

class Fasilitas {
    // Koneksi database dan nama tabel
    private $conn;
    private $table_name = "fasilitas";

    // Properti objek
    public $Id_Fasilitas;
    public $Nama_Fasilitas;
    public $Lokasi;

    // Konstruktor dengan $db sebagai koneksi database
    public function __construct($db) {
        $this->conn = $db;
    }

    // Membaca semua fasilitas
    public function read() {
        // Query select semua
        $query = "SELECT Id_Fasilitas, Nama_Fasilitas, Lokasi FROM " . $this->table_name . " ORDER BY Id_Fasilitas DESC";

        // Menyiapkan statement query
        $stmt = $this->conn->prepare($query);

        // Menjalankan query
        $stmt->execute();

        return $stmt;
    }

    // Membuat fasilitas baru
    public function create() {
        // Query insert
        $query = "INSERT INTO " . $this->table_name . " SET Nama_Fasilitas=:nama_fasilitas, Lokasi=:lokasi";

        // Menyiapkan statement
        $stmt = $this->conn->prepare($query);

        // Membersihkan data
        $this->Nama_Fasilitas = htmlspecialchars(strip_tags($this->Nama_Fasilitas));
        $this->Lokasi = htmlspecialchars(strip_tags($this->Lokasi));

        // Mengikat nilai
        $stmt->bindParam(":nama_fasilitas", $this->Nama_Fasilitas);
        $stmt->bindParam(":lokasi", $this->Lokasi);

        // Menjalankan query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Membaca satu fasilitas berdasarkan ID
    public function readOne() {
        // Query untuk membaca satu record
        $query = "SELECT Id_Fasilitas, Nama_Fasilitas, Lokasi FROM " . $this->table_name . " WHERE Id_Fasilitas = ? LIMIT 0,1";

        // Menyiapkan statement query
        $stmt = $this->conn->prepare($query);

        // Mengikat ID fasilitas yang akan di-update
        $stmt->bindParam(1, $this->Id_Fasilitas);

        // Menjalankan query
        $stmt->execute();

        // Mendapatkan baris yang diambil
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        // Mengatur nilai ke properti objek
        if ($row) { // Cek apakah baris ditemukan
            $this->Nama_Fasilitas = $row['Nama_Fasilitas'];
            $this->Lokasi = $row['Lokasi'];
        }
    }

    // Memperbarui fasilitas
    public function update() {
        // Query update
        $query = "UPDATE " . $this->table_name . " SET Nama_Fasilitas = :nama_fasilitas, Lokasi = :lokasi WHERE Id_Fasilitas = :id_fasilitas";

        // Menyiapkan statement query
        $stmt = $this->conn->prepare($query);

        // Membersihkan data
        $this->Nama_Fasilitas = htmlspecialchars(strip_tags($this->Nama_Fasilitas));
        $this->Lokasi = htmlspecialchars(strip_tags($this->Lokasi));
        $this->Id_Fasilitas = htmlspecialchars(strip_tags($this->Id_Fasilitas));

        // Mengikat nilai
        $stmt->bindParam(':nama_fasilitas', $this->Nama_Fasilitas);
        $stmt->bindParam(':lokasi', $this->Lokasi);
        $stmt->bindParam(':id_fasilitas', $this->Id_Fasilitas);

        // Menjalankan query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Menghapus fasilitas
    public function delete() {
        // Query delete
        $query = "DELETE FROM " . $this->table_name . " WHERE Id_Fasilitas = ?";

        // Menyiapkan statement
        $stmt = $this->conn->prepare($query);

        // Membersihkan data
        $this->Id_Fasilitas = htmlspecialchars(strip_tags($this->Id_Fasilitas));

        // Mengikat id dari record yang akan dihapus
        $stmt->bindParam(1, $this->Id_Fasilitas);

        // Menjalankan query
        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>