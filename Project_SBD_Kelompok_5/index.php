<?php
// Header untuk mengizinkan CORS
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Mendapatkan path dari URL
$request = $_SERVER['REQUEST_URI'];
$basePath = '/Project_SBD_Kelompok_5'; // Sesuaikan ini jika folder project Anda tidak di root server

// Menghapus base path dari request URL dan membersihkan slash di awal/akhir
$request = str_replace($basePath, '', $request);
$request = trim($request, '/');

// Memecah request menjadi segmen
$segments = explode('/', $request);

// Ambil segmen pertama sebagai resource (fasilitas, gedung, laporan, dll.)
$resource = array_shift($segments);

// Jika resource tidak kosong, sertakan file API yang sesuai
if (!empty($resource)) {
    $APIFile = 'API/' . $resource . '.php';
    if (file_exists($APIFile)) {
        include_once $APIFile;
    } else {
        http_response_code(404);
        echo json_encode(array("message" => "Resource tidak ditemukan."));
    }
} else {
    // Jika tidak ada resource, tampilkan pesan selamat datang atau daftar endpoint
    http_response_code(200);
    echo json_encode(array("message" => "Selamat datang di API Sistem Pelaporan Masalah Fasilitas."));
}

?>