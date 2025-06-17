<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

$host = "localhost";
$db_name = "project_sbd";
$username = "root";
$password_db = "";

$conn = new mysqli($host, $username, $password_db, $db_name);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Koneksi database gagal."]);
    exit();
}

$data = json_decode(file_get_contents("php://input"));

if (!isset($data->id_laporan) || !isset($data->status)) {
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Data tidak lengkap."]);
    exit();
}

$id_laporan = $data->id_laporan;
$status_baru = $data->status;

$stmt = $conn->prepare("UPDATE laporan SET status = ? WHERE id_laporan = ?");
$stmt->bind_param("si", $status_baru, $id_laporan);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Status berhasil diperbarui."]);
} else {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Gagal memperbarui status."]);
}

$stmt->close();
$conn->close();
?>
