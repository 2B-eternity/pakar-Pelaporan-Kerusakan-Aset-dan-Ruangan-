<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Gedung.php';

$database = new Database();
$db = $database->getConnection();
$gedung = new Gedung($db);

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));

switch ($method) {
    case 'GET':
        if (isset($_GET['id_gedung'])) {
            $gedung->id_gedung = $_GET['id_gedung'];
            $gedung->readOne();

            if ($gedung->nama_gedung != null) {
                $gedung_arr = array(
                    "id_gedung" => $gedung->id_gedung,
                    "nama_gedung" => $gedung->nama_gedung,
                    "lantai_gedung" => $gedung->lantai_gedung
                );
                http_response_code(200);
                echo json_encode($gedung_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Gedung tidak ditemukan."));
            }
        } else {
            $stmt = $gedung->read();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $gedung_arr = array();
                $gedung_arr["records"] = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $gedung_item = array(
                        "id_gedung" => $id_gedung,
                        "nama_gedung" => $nama_gedung,
                        "lantai_gedung" => $lantai_gedung
                    );
                    array_push($gedung_arr["records"], $gedung_item);
                }
                http_response_code(200);
                echo json_encode($gedung_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Tidak ada gedung ditemukan."));
            }
        }
        break;

    case 'POST':
        if (!empty($data->nama_gedung) && !empty($data->lantai_gedung)) {
            $gedung->nama_gedung = $data->nama_gedung;
            $gedung->lantai_gedung = $data->lantai_gedung;

            if ($gedung->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Gedung berhasil ditambahkan."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Tidak dapat menambahkan gedung."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Tidak dapat menambahkan gedung. Data tidak lengkap."));
        }
        break;

    case 'PUT':
        if (!empty($data->id_gedung) && !empty($data->nama_gedung) && !empty($data->lantai_gedung)) {
            $gedung->id_gedung = $data->id_gedung;
            $gedung->nama_gedung = $data->nama_gedung;
            $gedung->lantai_gedung = $data->lantai_gedung;

            if ($gedung->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Gedung berhasil diperbarui."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Tidak dapat memperbarui gedung."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Tidak dapat memperbarui gedung. Data tidak lengkap."));
        }
        break;

    case 'DELETE':
        if (!empty($data->id_gedung)) {
            $gedung->id_gedung = $data->id_gedung;

            if ($gedung->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Gedung berhasil dihapus."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Tidak dapat menghapus gedung."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Tidak dapat menghapus gedung. ID tidak disediakan."));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method tidak diizinkan."));
        break;
}
?>