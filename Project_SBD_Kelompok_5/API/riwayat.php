<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Riwayat.php';

$database = new Database();
$db = $database->getConnection();
$riwayat = new Riwayat($db);

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));

switch ($method) {
    case 'GET':
        if (isset($_GET['id_riwayat'])) {
            $riwayat->id_riwayat = $_GET['id_riwayat'];
            $riwayat->readOne();

            if ($riwayat->keterangan != null) {
                $riwayat_arr = array(
                    "id_riwayat" => $riwayat->id_riwayat,
                    "waktu_perubahan" => $riwayat->waktu_perubahan,
                    "tanggal_perubahan" => $riwayat->tanggal_perubahan,
                    "keterangan" => $riwayat->keterangan
                );
                http_response_code(200);
                echo json_encode($riwayat_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Riwayat tidak ditemukan."));
            }
        } else {
            $stmt = $riwayat->read();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $riwayat_arr = array();
                $riwayat_arr["records"] = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $riwayat_item = array(
                        "id_riwayat" => $id_riwayat,
                        "waktu_perubahan" => $waktu_perubahan,
                        "tanggal_perubahan" => $tanggal_perubahan,
                        "keterangan" => $keterangan
                    );
                    array_push($riwayat_arr["records"], $riwayat_item);
                }
                http_response_code(200);
                echo json_encode($riwayat_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Tidak ada riwayat ditemukan."));
            }
        }
        break;

    case 'POST':
        if (!empty($data->waktu_perubahan) && !empty($data->tanggal_perubahan) && !empty($data->keterangan)) {
            $riwayat->waktu_perubahan = $data->waktu_perubahan;
            $riwayat->tanggal_perubahan = $data->tanggal_perubahan;
            $riwayat->keterangan = $data->keterangan;

            if ($riwayat->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Riwayat berhasil ditambahkan."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Tidak dapat menambahkan riwayat."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Tidak dapat menambahkan riwayat. Data tidak lengkap."));
        }
        break;

    case 'PUT':
        if (!empty($data->id_riwayat) && !empty($data->waktu_perubahan) && !empty($data->tanggal_perubahan) && !empty($data->keterangan)) {
            $riwayat->id_riwayat = $data->id_riwayat;
            $riwayat->waktu_perubahan = $data->waktu_perubahan;
            $riwayat->tanggal_perubahan = $data->tanggal_perubahan;
            $riwayat->keterangan = $data->keterangan;

            if ($riwayat->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Riwayat berhasil diperbarui."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Tidak dapat memperbarui riwayat."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Tidak dapat memperbarui riwayat. Data tidak lengkap."));
        }
        break;

    case 'DELETE':
        if (!empty($data->id_riwayat)) {
            $riwayat->id_riwayat = $data->id_riwayat;

            if ($riwayat->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Riwayat berhasil dihapus."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Tidak dapat menghapus riwayat."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Tidak dapat menghapus riwayat. ID tidak disediakan."));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method tidak diizinkan."));
        break;
}
?>