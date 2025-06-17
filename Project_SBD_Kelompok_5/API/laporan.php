<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: GET, POST, PUT, DELETE");
header("Access-Control-Max-Age: 3600");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

include_once '../config/database.php';
include_once '../models/Laporan.php';

$database = new Database();
$db = $database->getConnection();
$laporan = new Laporan($db);

$method = $_SERVER['REQUEST_METHOD'];
$data = json_decode(file_get_contents("php://input"));

switch ($method) {
    case 'GET':
        if (isset($_GET['id_laporan'])) {
            $laporan->id_laporan = $_GET['id_laporan'];
            $laporan->readOne();

            if ($laporan->deskripsi_masalah != null) {
                $laporan_arr = array(
                    "id_laporan" => $laporan->id_laporan,
                    "tanggal_laporan" => $laporan->tanggal_laporan,
                    "tanggal_selesai" => $laporan->tanggal_selesai,
                    "id_pelapor" => $laporan->id_pelapor, // Tambahkan ini
                    "Nama_Pelapor" => $laporan->Nama_Pelapor,
                    "id_fasilitas" => $laporan->id_fasilitas, // Tambahkan ini
                    "Nama_Fasilitas" => $laporan->Nama_Fasilitas,
                    "deskripsi_masalah" => $laporan->deskripsi_masalah,
                    "STATUS" => $laporan->STATUS,
                    "foto_masalah" => $laporan->foto_masalah,
                    "id_gedung" => $laporan->id_gedung, // Tambahkan ini
                    "nama_gedung" => $laporan->nama_gedung,
                    "id_riwayat" => $laporan->id_riwayat, // Tambahkan ini
                    "riwayat_keterangan" => $laporan->riwayat_keterangan
                );
                http_response_code(200);
                echo json_encode($laporan_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Laporan tidak ditemukan."));
            }
        } else {
            $stmt = $laporan->read();
            $num = $stmt->rowCount();

            if ($num > 0) {
                $laporan_arr = array();
                $laporan_arr["records"] = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                    extract($row);
                    $laporan_item = array(
                        "id_laporan" => $id_laporan,
                        "tanggal_laporan" => $tanggal_laporan,
                        "tanggal_selesai" => $tanggal_selesai,
                        "id_pelapor" => $id_pelapor, // Pastikan ini juga ada di hasil SELECT model
                        "Nama_Pelapor" => $Nama_Pelapor,
                        "id_fasilitas" => $id_fasilitas, // Pastikan ini juga ada di hasil SELECT model
                        "Nama_Fasilitas" => $Nama_Fasilitas,
                        "deskripsi_masalah" => $deskripsi_masalah,
                        "STATUS" => $STATUS,
                        "foto_masalah" => $foto_masalah,
                        "id_gedung" => $id_gedung, // Pastikan ini juga ada di hasil SELECT model
                        "nama_gedung" => $nama_gedung,
                        "id_riwayat" => $id_riwayat, // Pastikan ini juga ada di hasil SELECT model
                        "riwayat_keterangan" => $riwayat_keterangan
                    );
                    array_push($laporan_arr["records"], $laporan_item);
                }
                http_response_code(200);
                echo json_encode($laporan_arr);
            } else {
                http_response_code(404);
                echo json_encode(array("message" => "Tidak ada laporan ditemukan."));
            }
        }
        break;

    case 'POST':
        if (
            !empty($data->tanggal_laporan) &&
            !empty($data->id_pelapor) &&
            !empty($data->id_fasilitas) &&
            !empty($data->deskripsi_masalah) &&
            !empty($data->STATUS) &&
            !empty($data->id_gedung)
        ) {
            $laporan->tanggal_laporan = $data->tanggal_laporan;
            $laporan->tanggal_selesai = isset($data->tanggal_selesai) ? $data->tanggal_selesai : null;
            $laporan->id_pelapor = $data->id_pelapor;
            $laporan->id_fasilitas = $data->id_fasilitas;
            $laporan->deskripsi_masalah = $data->deskripsi_masalah;
            $laporan->STATUS = $data->STATUS;
            $laporan->foto_masalah = isset($data->foto_masalah) ? $data->foto_masalah : null;
            $laporan->id_gedung = $data->id_gedung;
            $laporan->id_riwayat = isset($data->id_riwayat) ? $data->id_riwayat : null;

            if ($laporan->create()) {
                http_response_code(201);
                echo json_encode(array("message" => "Laporan berhasil ditambahkan."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Tidak dapat menambahkan laporan."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Tidak dapat menambahkan laporan. Data tidak lengkap."));
        }
        break;

    case 'PUT':
        if (
            !empty($data->id_laporan) &&
            !empty($data->tanggal_laporan) &&
            !empty($data->id_pelapor) &&
            !empty($data->id_fasilitas) &&
            !empty($data->deskripsi_masalah) &&
            !empty($data->STATUS) &&
            !empty($data->id_gedung)
        ) {
            $laporan->id_laporan = $data->id_laporan;
            $laporan->tanggal_laporan = $data->tanggal_laporan;
            $laporan->tanggal_selesai = isset($data->tanggal_selesai) ? $data->tanggal_selesai : null;
            $laporan->id_pelapor = $data->id_pelapor;
            $laporan->id_fasilitas = $data->id_fasilitas;
            $laporan->deskripsi_masalah = $data->deskripsi_masalah;
            $laporan->STATUS = $data->STATUS;
            $laporan->foto_masalah = isset($data->foto_masalah) ? $data->foto_masalah : null;
            $laporan->id_gedung = $data->id_gedung;
            $laporan->id_riwayat = isset($data->id_riwayat) ? $data->id_riwayat : null;

            if ($laporan->update()) {
                http_response_code(200);
                echo json_encode(array("message" => "Laporan berhasil diperbarui."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Tidak dapat memperbarui laporan."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Tidak dapat memperbarui laporan. Data tidak lengkap."));
        }
        break;

    case 'DELETE':
        if (!empty($data->id_laporan)) {
            $laporan->id_laporan = $data->id_laporan;

            if ($laporan->delete()) {
                http_response_code(200);
                echo json_encode(array("message" => "Laporan berhasil dihapus."));
            } else {
                http_response_code(503);
                echo json_encode(array("message" => "Tidak dapat menghapus laporan."));
            }
        } else {
            http_response_code(400);
            echo json_encode(array("message" => "Tidak dapat menghapus laporan. ID tidak disediakan."));
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(array("message" => "Method tidak diizinkan."));
        break;
}
?>