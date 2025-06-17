<?php

// Konfigurasi koneksi database
class Database {
    private $host = "localhost"; // Host database Anda
    private $db_name = "project sbd"; // Nama database Anda
    private $username = "root"; // Username database Anda (sesuaikan)
    private $password = " "; // Password database Anda (sesuaikan)
    public $conn;

    // Mendapatkan koneksi database
    public function getConnection() {
        $this->conn = null;
        try {
            $this->conn = new PDO("mysql:host=" . $this->host . ";dbname=" . $this->db_name, $this->username, $this->password);
            $this->conn->exec("set names utf8");
        } catch(PDOException $exception) {
            echo "Koneksi database gagal: " . $exception->getMessage();
        }
        return $this->conn;
    }
}

?>