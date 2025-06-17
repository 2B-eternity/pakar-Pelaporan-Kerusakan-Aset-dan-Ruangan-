<?php

class Laporan {
    // Koneksi database dan nama tabel
    private $conn;
    private $table_name = "laporan";

    // Properti objek
    public $id_laporan;
    public $tanggal_laporan;
    public $tanggal_selesai;
    public $id_pelapor;
    public $id_fasilitas;
    public $deskripsi_masalah;
    public $STATUS;
    public $foto_masalah;
    public $id_gedung;
    public $id_riwayat;
    // Tambahan properti untuk menampung hasil JOIN
    public $Nama_Pelapor;
    public $Nama_Fasilitas;
    public $nama_gedung;
    public $riwayat_keterangan;


    // Konstruktor dengan $db sebagai koneksi database
    public function __construct($db) {
        $this->conn = $db;
    }

    // Membaca semua laporan
    public function read() {
        $query = "SELECT
                    l.id_laporan, l.tanggal_laporan, l.tanggal_selesai, l.deskripsi_masalah, l.STATUS, l.foto_masalah,
                    p.Nama_Pelapor, f.Nama_Fasilitas, g.nama_gedung, r.keterangan AS riwayat_keterangan
                FROM
                    " . $this->table_name . " l
                    LEFT JOIN pelapor p ON l.id_pelapor = p.Id_Pelapor
                    LEFT JOIN fasilitas f ON l.id_fasilitas = f.Id_Fasilitas
                    LEFT JOIN gedung g ON l.id_gedung = g.id_gedung
                    LEFT JOIN riwayat r ON l.id_riwayat = r.id_riwayat
                ORDER BY
                    l.tanggal_laporan DESC";
        $stmt = $this->conn->prepare($query);
        $stmt->execute();
        return $stmt;
    }

    // Membuat laporan baru
    public function create() {
        $query = "INSERT INTO " . $this->table_name . "
                  SET tanggal_laporan=:tanggal_laporan, tanggal_selesai=:tanggal_selesai, id_pelapor=:id_pelapor,
                      id_fasilitas=:id_fasilitas, deskripsi_masalah=:deskripsi_masalah, STATUS=:status,
                      foto_masalah=:foto_masalah, id_gedung=:id_gedung, id_riwayat=:id_riwayat";
        $stmt = $this->conn->prepare($query);

        $this->tanggal_laporan = htmlspecialchars(strip_tags($this->tanggal_laporan));
        $this->tanggal_selesai = $this->tanggal_selesai ? htmlspecialchars(strip_tags($this->tanggal_selesai)) : null; // Handle null
        $this->id_pelapor = htmlspecialchars(strip_tags($this->id_pelapor));
        $this->id_fasilitas = htmlspecialchars(strip_tags($this->id_fasilitas));
        $this->deskripsi_masalah = htmlspecialchars(strip_tags($this->deskripsi_masalah));
        $this->STATUS = htmlspecialchars(strip_tags($this->STATUS));
        $this->foto_masalah = $this->foto_masalah ? htmlspecialchars(strip_tags($this->foto_masalah)) : null; // Handle null
        $this->id_gedung = htmlspecialchars(strip_tags($this->id_gedung));
        $this->id_riwayat = $this->id_riwayat ? htmlspecialchars(strip_tags($this->id_riwayat)) : null; // Handle null

        $stmt->bindParam(":tanggal_laporan", $this->tanggal_laporan);
        $stmt->bindParam(":tanggal_selesai", $this->tanggal_selesai);
        $stmt->bindParam(":id_pelapor", $this->id_pelapor);
        $stmt->bindParam(":id_fasilitas", $this->id_fasilitas);
        $stmt->bindParam(":deskripsi_masalah", $this->deskripsi_masalah);
        $stmt->bindParam(":status", $this->STATUS);
        $stmt->bindParam(":foto_masalah", $this->foto_masalah);
        $stmt->bindParam(":id_gedung", $this->id_gedung);
        $stmt->bindParam(":id_riwayat", $this->id_riwayat);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Membaca satu laporan berdasarkan ID
    public function readOne() {
        $query = "SELECT
                    l.id_laporan, l.tanggal_laporan, l.tanggal_selesai, l.deskripsi_masalah, l.STATUS, l.foto_masalah,
                    p.Nama_Pelapor, f.Nama_Fasilitas, g.nama_gedung, r.keterangan AS riwayat_keterangan
                FROM
                    " . $this->table_name . " l
                    LEFT JOIN pelapor p ON l.id_pelapor = p.Id_Pelapor
                    LEFT JOIN fasilitas f ON l.id_fasilitas = f.Id_Fasilitas
                    LEFT JOIN gedung g ON l.id_gedung = g.id_gedung
                    LEFT JOIN riwayat r ON l.id_riwayat = r.id_riwayat
                WHERE
                    l.id_laporan = ?
                LIMIT 0,1";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(1, $this->id_laporan);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($row) {
            $this->id_laporan = $row['id_laporan'];
            $this->tanggal_laporan = $row['tanggal_laporan'];
            $this->tanggal_selesai = $row['tanggal_selesai'];
            $this->Nama_Pelapor = $row['Nama_Pelapor'];
            $this->Nama_Fasilitas = $row['Nama_Fasilitas'];
            $this->deskripsi_masalah = $row['deskripsi_masalah'];
            $this->STATUS = $row['STATUS'];
            $this->foto_masalah = $row['foto_masalah'];
            $this->id_gedung = $row['id_gedung']; // Memperbaiki ini agar properti id_gedung terisi
            $this->nama_gedung = $row['nama_gedung'];
            $this->id_riwayat = $row['id_riwayat']; // Memperbaiki ini agar properti id_riwayat terisi
            $this->riwayat_keterangan = $row['riwayat_keterangan'];
        }
    }

    // Memperbarui laporan
    public function update() {
        $query = "UPDATE " . $this->table_name . "
                  SET tanggal_laporan=:tanggal_laporan, tanggal_selesai=:tanggal_selesai, id_pelapor=:id_pelapor,
                      id_fasilitas=:id_fasilitas, deskripsi_masalah=:deskripsi_masalah, STATUS=:status,
                      foto_masalah=:foto_masalah, id_gedung=:id_gedung, id_riwayat=:id_riwayat
                  WHERE id_laporan = :id_laporan";
        $stmt = $this->conn->prepare($query);

        $this->tanggal_laporan = htmlspecialchars(strip_tags($this->tanggal_laporan));
        $this->tanggal_selesai = $this->tanggal_selesai ? htmlspecialchars(strip_tags($this->tanggal_selesai)) : null; // Handle null
        $this->id_pelapor = htmlspecialchars(strip_tags($this->id_pelapor));
        $this->id_fasilitas = htmlspecialchars(strip_tags($this->id_fasilitas));
        $this->deskripsi_masalah = htmlspecialchars(strip_tags($this->deskripsi_masalah));
        $this->STATUS = htmlspecialchars(strip_tags($this->STATUS));
        $this->foto_masalah = $this->foto_masalah ? htmlspecialchars(strip_tags($this->foto_masalah)) : null; // Handle null
        $this->id_gedung = htmlspecialchars(strip_tags($this->id_gedung));
        $this->id_riwayat = $this->id_riwayat ? htmlspecialchars(strip_tags($this->id_riwayat)) : null; // Handle null
        $this->id_laporan = htmlspecialchars(strip_tags($this->id_laporan));

        $stmt->bindParam(":tanggal_laporan", $this->tanggal_laporan);
        $stmt->bindParam(":tanggal_selesai", $this->tanggal_selesai);
        $stmt->bindParam(":id_pelapor", $this->id_pelapor);
        $stmt->bindParam(":id_fasilitas", $this->id_fasilitas);
        $stmt->bindParam(":deskripsi_masalah", $this->deskripsi_masalah);
        $stmt->bindParam(":status", $this->STATUS);
        $stmt->bindParam(":foto_masalah", $this->foto_masalah);
        $stmt->bindParam(":id_gedung", $this->id_gedung);
        $stmt->bindParam(":id_riwayat", $this->id_riwayat);
        $stmt->bindParam(':id_laporan', $this->id_laporan);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }

    // Menghapus laporan
    public function delete() {
        $query = "DELETE FROM " . $this->table_name . " WHERE id_laporan = ?";
        $stmt = $this->conn->prepare($query);

        $this->id_laporan = htmlspecialchars(strip_tags($this->id_laporan));

        $stmt->bindParam(1, $this->id_laporan);

        if($stmt->execute()) {
            return true;
        }
        return false;
    }
}
?>