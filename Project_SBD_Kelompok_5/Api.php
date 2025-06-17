<?php
// === HEADER UNTUK MENGATASI MASALAH CORS === //
// Mengizinkan permintaan dari origin React App Anda (sesuaikan port jika berbeda)
header("Access-Control-Allow-Origin: http://localhost:5173"); 
header("Content-Type: application/json; charset=UTF-8");
// Mengizinkan metode request POST
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// === KONEKSI KE DATABASE (DISESUAIKAN DENGAN DATABASE ANDA) === //
$host = "localhost"; // atau 127.0.0.1
$db_name = "project_sbd"; // NAMA DATABASE ANDA
$username = "root"; // Username default XAMPP
$password_db = ""; // Password database default XAMPP adalah kosong

// Buat koneksi
$conn = new mysqli($host, $username, $password_db, $db_name);

// Cek koneksi
if ($conn->connect_error) {
    // Jika koneksi gagal, kirim respons error dan hentikan script
    http_response_code(500); // Internal Server Error
    echo json_encode(["status" => "error", "message" => "Koneksi database gagal: " . $conn->connect_error]);
    exit();
}

// === MEMBACA DATA JSON DARI REACT === //
// Ambil data JSON yang dikirim dari body request
$data = json_decode(file_get_contents("php://input"));

// Pastikan email dan password tidak kosong
if (empty($data->email) || empty($data->password)) {
    // Jika data tidak lengkap, kirim respons error
    http_response_code(400); // Bad Request
    echo json_encode(["status" => "error", "message" => "Email dan password harus diisi."]);
    exit();
}

// === PROSES LOGIN (DISESUAIKAN DENGAN TABEL ANDA) === //
$email = $data->email;
$password_input = $data->password;

// **PENTING: Gunakan Prepared Statements untuk keamanan dari SQL Injection**
// Query disesuaikan dengan tabel `pelapor` dan kolom-kolomnya
$sql = "SELECT id_pelapor, email_pelapor, password FROM pelapor WHERE email_pelapor = ?";

// Siapkan statement
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Gagal menyiapkan statement: " . $conn->error]);
    exit();
}

// Bind parameter ke statement
$stmt->bind_param("s", $email);

// Eksekusi statement
$stmt->execute();

// Dapatkan hasil
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    // User ditemukan, sekarang verifikasi password
    $pelapor = $result->fetch_assoc();
    $hashed_password_from_db = $pelapor['password'];

    // **PENTING: Verifikasi password yang sudah di-hash**
    // Pastikan password di database Anda disimpan menggunakan password_hash()
    if (password_verify($password_input, $hashed_password_from_db)) {
        // Password cocok, login berhasil
        http_response_code(200); // OK
        echo json_encode([
            "status" => "success", 
            "message" => "Login berhasil.", 
            "user" => [
                "id" => $pelapor['id_pelapor'], 
                "email" => $pelapor['email_pelapor']
            ]
        ]);
    } else {
        // Password tidak cocok
        http_response_code(401); // Unauthorized
        echo json_encode(["status" => "error", "message" => "Password salah."]);
    }
} else {
    // Email tidak ditemukan
    http_response_code(404); // Not Found
    echo json_encode(["status" => "error", "message" => "Email tidak terdaftar."]);
}

// Tutup statement dan koneksi
$stmt->close();
$conn->close();

?>
