<?php
// Header untuk mengizinkan CORS dan menentukan tipe konten
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Memasukkan file database dan objek fasilitas
include_once '../config/database.php';
include_once '../models/Fasilitas.php';

// Mendapatkan koneksi database
$database = new Database();
$db = $database->getConnection();

// Menginisialisasi objek fasilitas
$fasilitas = new Fasilitas($db);

// Mendapatkan method request
$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input")); // Mendapatkan data dari body request

switch ($method) {
    case 'GET':
        // Jika ada parameter Id_Fasilitas, baca satu fasilitas
        if (isset($_GET['Id_Fasilitas'])) {
            $fasilitas->Id_Fasilitas = $_GET['Id_Fasilitas'];
            $fasilitas->readOne();

            if ($fasilitas->Nama_Fasilitas != null) {
                $fasilitas_arr = array(
                    "Id_Fasilitas" => $fasilitas->Id_Fasilitas,
                    "Nama_Fasilitas" => $fasilitas->Nama_Fasilitas,
                    "Lokasi" => $fasilitas->Lokasi
                );
                http_response_code(200);
                echo json_encode($fasilitas_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Fasilitas tidak ditemukan."));
            }
        } else {
            // Jika tidak ada parameter Id_Fasilitas, baca semua fasilitas
            $stmt = $fasilitas->read();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $fasilitas_arr = array();
                $fasilitas_arr["records"] = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $fasilitas_item = array(
                        "Id_Fasilitas" => $Id_Fasilitas,
                        "Nama_Fasilitas" => $Nama_Fasilitas,
                        "Lokasi" => $Lokasi
                    );
                    array_push($fasilitas_arr["records"], $fasilitas_item);
                }
                http_response_code(200);
                echo json_encode($fasilitas_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Tidak ada fasilitas ditemukan."));
            }
        }
        break;

    case 'POST':
        if (!empty($data->Nama_Fasilitas) && !empty($data->Lokasi)) {
            $fasilitas->Nama_Fasilitas = $data->Nama_Fasilitas;
            $fasilitas->Lokasi = $data->Lokasi;

            if ($fasilitas->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Fasilitas berhasil ditambahkan."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Tidak dapat menambahkan fasilitas."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Tidak dapat menambahkan fasilitas. Data tidak lengkap."));
        }
        break;

    case 'PUT':
        if (!empty($data->Id_Fasilitas) && !empty($data->Nama_Fasilitas) && !empty($data->Lokasi)) {
            $fasilitas->Id_Fasilitas = $data->Id_Fasilitas;
            $fasilitas->Nama_Fasilitas = $data->Nama_Fasilitas;
            $fasilitas->Lokasi = $data->Lokasi;

            if ($fasilitas->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Fasilitas berhasil diperbarui."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Tidak dapat memperbarui fasilitas."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Tidak dapat memperbarui fasilitas. Data tidak lengkap."));
        }
        break;

    case 'DELETE':
        if (!empty($data->Id_Fasilitas)) {
            $fasilitas->Id_Fasilitas = $data->Id_Fasilitas;

            if ($fasilitas->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Fasilitas berhasil dihapus."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Tidak dapat menghapus fasilitas."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Tidak dapat menghapus fasilitas. ID tidak disediakan."));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method tidak diizinkan."));
        break;
}
?>