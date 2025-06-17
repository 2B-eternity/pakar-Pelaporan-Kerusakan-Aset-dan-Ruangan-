<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Pelapor.php';

$database = new Database();
$db = $database->getConnection();
$pelapor = new Pelapor($db);

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));

switch ($method) {
    case 'GET':
        if (isset($_GET['Id_Pelapor'])) {
            $pelapor->Id_Pelapor = $_GET['Id_Pelapor'];
            $pelapor->readOne();

            if ($pelapor->Nama_Pelapor != null) {
                $pelapor_arr = array(
                    "Id_Pelapor" => $pelapor->Id_Pelapor,
                    "Nama_Pelapor" => $pelapor->Nama_Pelapor
                );
                http_response_code(200);
                echo json_encode($pelapor_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Pelapor tidak ditemukan."));
            }
        } else {
            $stmt = $pelapor->read();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $pelapor_arr = array();
                $pelapor_arr["records"] = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $pelapor_item = array(
                        "Id_Pelapor" => $Id_Pelapor,
                        "Nama_Pelapor" => $Nama_Pelapor
                    );
                    array_push($pelapor_arr["records"], $pelapor_item);
                }
                http_response_code(200);
                echo json_encode($pelapor_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Tidak ada pelapor ditemukan."));
            }
        }
        break;

    case 'POST':
        if (!empty($data->Nama_Pelapor)) {
            $pelapor->Nama_Pelapor = $data->Nama_Pelapor;

            if ($pelapor->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Pelapor berhasil ditambahkan."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Tidak dapat menambahkan pelapor."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Tidak dapat menambahkan pelapor. Data tidak lengkap."));
        }
        break;

    case 'PUT':
        if (!empty($data->Id_Pelapor) && !empty($data->Nama_Pelapor)) {
            $pelapor->Id_Pelapor = $data->Id_Pelapor;
            $pelapor->Nama_Pelapor = $data->Nama_Pelapor;

            if ($pelapor->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Pelapor berhasil diperbarui."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Tidak dapat memperbarui pelapor."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Tidak dapat memperbarui pelapor. Data tidak lengkap."));
        }
        break;

    case 'DELETE':
        if (!empty($data->Id_Pelapor)) {
            $pelapor->Id_Pelapor = $data->Id_Pelapor;

            if ($pelapor->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Pelapor berhasil dihapus."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Tidak dapat menghapus pelapor."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Tidak dapat menghapus pelapor. ID tidak disediakan."));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method tidak diizinkan."));
        break;
}
?>