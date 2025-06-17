<?php
header("Content-Type: text/html; charset=UTF-8");

// === KONEKSI KE DATABASE === //
$host = "localhost";
$db_name = "project_sbd";
$username = "root";
$password_db = "";

$conn = new mysqli($host, $username, $password_db, $db_name);
if ($conn->connect_error) {
    die("<h1>Koneksi database gagal: " . $conn->connect_error . "</h1>");
}

echo "<h1>Memulai Proses Seeding Database...</h1>";

try {
    // === MEMASUKKAN DATA AWAL KE TABEL 'gedung' === //
    $conn->query("INSERT INTO gedung (id_gedung, nama_gedung, lantai_gedung) VALUES (1, 'Gedung A', '1-3') ON DUPLICATE KEY UPDATE nama_gedung=nama_gedung;");
    echo "<p>✔️ Data 'Gedung A' berhasil dimasukkan/sudah ada.</p>";
    
    // === MEMASUKKAN DATA AWAL KE TABEL 'fasilitas' === //
    $conn->query("INSERT INTO fasilitas (id_fasilitas, nama_fasilitas) VALUES (1, 'Proyektor') ON DUPLICATE KEY UPDATE nama_fasilitas=nama_fasilitas;");
    echo "<p>✔️ Data 'Proyektor' berhasil dimasukkan/sudah ada.</p>";

    // === MEMASUKKAN DATA AWAL KE TABEL 'ruangan' (DENGAN STRUKTUR BARU) === //
    // Sekarang kita hanya memasukkan nama ruangan.
    $conn->query("INSERT INTO ruangan (id_ruangan, nama_ruangan) VALUES (1, 'R-101') ON DUPLICATE KEY UPDATE nama_ruangan=nama_ruangan;");
    echo "<p>✔️ Data 'Ruangan R-101' berhasil dimasukkan/sudah ada.</p>";

    echo "<h2>✅ Proses Seeding Selesai!</h2>";
    echo "<p>Database Anda sekarang memiliki data awal yang dibutuhkan. Silakan coba kirim laporan lagi dari aplikasi Anda.</p>";

} catch (Exception $e) {
    echo "<h2>❌ Terjadi Error!</h2>";
    echo "<p>Pesan Error: " . $e->getMessage() . "</p>";
}

$conn->close();
?>
