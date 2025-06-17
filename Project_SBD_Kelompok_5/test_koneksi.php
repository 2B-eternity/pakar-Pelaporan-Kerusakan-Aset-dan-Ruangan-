<?php
// === HEADER UNTUK MENGATASI MASALAH CORS === //
// Mengizinkan permintaan dari origin React App Anda (sesuaikan port jika berbeda)
header("Access-Control-Allow-Origin: http://localhost:5173"); 
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, GET, OPTIONS"); // Izinkan OPTIONS untuk preflight
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Script ini tidak terhubung ke database.
// Tujuannya hanya untuk memastikan frontend (React) bisa menerima respons dari backend (PHP).

// Mengambil data yang dikirim (jika ada) untuk simulasi
$data = json_decode(file_get_contents("php://input"));
$email = $data->email ?? 'tidak ada email'; // Mengambil email jika ada

// Mengirim respons JSON sederhana
http_response_code(200); // Kode 200 OK
echo json_encode([
    "status" => "success",
    "message" => "Koneksi dari PHP berhasil!",
    "user" => [ // Menambahkan data user dummy agar cocok dengan struktur di React
        "email" => $email,
        "id" => 1
    ]
]);

?>
