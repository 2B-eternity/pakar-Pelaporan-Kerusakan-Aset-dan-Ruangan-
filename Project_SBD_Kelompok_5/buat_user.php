<?php
// === KONEKSI KE DATABASE (DISESUAIKAN DENGAN DATABASE ANDA) === //
$host = "localhost";
$db_name = "project_sbd"; // Pastikan nama DB sudah benar
$username = "root";
$password_db = "";

$conn = new mysqli($host, $username, $password_db, $db_name);
if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// === DATA DUMMY YANG AKAN DIBUAT === //
$nama_pelapor = "User Dummy";
$email_pelapor = "test@example.com";
$password_polos = "123456"; // Password yang akan Anda ketik di form login
$no_hp = "081234567890";

// **PENTING: Buat HASH dari password**
$password_hash = password_hash($password_polos, PASSWORD_DEFAULT);

// === MASUKKAN KE DATABASE === //
// Query menggunakan prepared statement untuk keamanan
$sql = "INSERT INTO pelapor (nama_pelapor, email_pelapor, password, no_hp) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ssss", $nama_pelapor, $email_pelapor, $password_hash, $no_hp);

if ($stmt->execute()) {
    echo "<h1>User Dummy Berhasil Dibuat!</h1>";
    echo "<p>Email: " . htmlspecialchars($email_pelapor) . "</p>";
    echo "<p>Password: " . htmlspecialchars($password_polos) . "</p>";
    echo "<p>Anda sekarang bisa menggunakan data ini untuk login.</p>";
} else {
    echo "<h1>Error!</h1>";
    echo "<p>Gagal membuat user dummy: " . $stmt->error . "</p>";
}

$stmt->close();
$conn->close();

?>
