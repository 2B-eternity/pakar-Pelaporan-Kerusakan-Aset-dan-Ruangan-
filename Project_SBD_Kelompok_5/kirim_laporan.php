<?php
// === PENGATURAN DEBUGGING TINGKAT LANJUT === //
// Menonaktifkan tampilan error agar tidak merusak JSON
ini_set('display_errors', 0); 
// Mengaktifkan pencatatan error ke file
ini_set('log_errors', 1);
// Menentukan file log (pastikan folder ini bisa ditulis oleh server)
ini_set('error_log', __DIR__ . '/php-error.log');
// Mengatur MySQLi untuk melempar exception saat terjadi error
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);


// === HEADER UNTUK MENGATASI MASALAH CORS === //
header("Access-Control-Allow-Origin: http://localhost:5173"); 
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

if ($_SERVER['REQUEST_METHOD'] == 'OPTIONS') {
    http_response_code(200);
    exit();
}

// === KONEKSI KE DATABASE === //
$host = "localhost";
$db_name = "project_sbd";
$username = "root";
$password_db = "";

try {
    $conn = new mysqli($host, $username, $password_db, $db_name);
} catch (mysqli_sql_exception $e) {
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Koneksi database gagal: " . $e->getMessage()]);
    exit();
}


// === FUNGSI GET OR CREATE ID (DENGAN PENANGANAN ERROR) === //
function getOrCreateId($conn, $table, $columnValue, $otherData = []) {
    $idCol = 'id_' . $table;
    $nameCol = 'nama_' . $table;
    if ($table === 'pelapor') {
        $nameCol = 'no_hp';
    }

    $stmt = $conn->prepare("SELECT `$idCol` FROM `$table` WHERE `$nameCol` = ?");
    $stmt->bind_param("s", $columnValue);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        return $row[$idCol];
    } else {
        if ($table === 'pelapor') {
            $stmt_insert = $conn->prepare("INSERT INTO pelapor (nama_pelapor, no_hp) VALUES (?, ?)");
            $stmt_insert->bind_param("ss", $otherData['nama'], $columnValue);
        } elseif ($table === 'gedung' || $table === 'fasilitas' || $table === 'ruangan') {
            $stmt_insert = $conn->prepare("INSERT INTO `$table` (`$nameCol`) VALUES (?)");
            $stmt_insert->bind_param("s", $columnValue);
        } else {
            return null;
        }
        
        $stmt_insert->execute();
        return $conn->insert_id;
    }
}

// === MEMBUNGKUS LOGIKA UTAMA DALAM BLOK TRY-CATCH === //
try {
    // Validasi input
    $required_fields = ['namaLengkap', 'nomorTelepon', 'gedung', 'ruang', 'fasilitas', 'deskripsi'];
    foreach ($required_fields as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Field '$field' harus diisi.");
        }
    }

    $namaLengkap = $_POST['namaLengkap'];
    $nomorTelepon = $_POST['nomorTelepon'];
    $gedungNama = $_POST['gedung'];
    $ruangNama = $_POST['ruang'];
    $fasilitasNama = $_POST['fasilitas'];
    $deskripsi = $_POST['deskripsi'];
    $tanggal_laporan = date('Y-m-d');
    $status = 'Belum Diproses';

    // Proses upload foto
    $foto_path_db = '';
    if (isset($_FILES['foto']) && $_FILES['foto']['error'] == 0) {
        $upload_dir = __DIR__ . '/uploads/'; 
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0777, true);
        }
        $foto_name = uniqid() . '-' . basename($_FILES["foto"]["name"]);
        $foto_target_file = $upload_dir . $foto_name;

        if (move_uploaded_file($_FILES["foto"]["tmp_name"], $foto_target_file)) {
            $foto_path_db = 'uploads/' . $foto_name;
        } else {
            throw new Exception("Gagal mengupload foto. Cek izin folder.");
        }
    }

    // Mendapatkan ID relasi
    $id_pelapor = getOrCreateId($conn, 'pelapor', $nomorTelepon, ['nama' => $namaLengkap]);
    $id_gedung = getOrCreateId($conn, 'gedung', $gedungNama);
    $id_fasilitas = getOrCreateId($conn, 'fasilitas', $fasilitasNama);
    $id_ruangan = getOrCreateId($conn, 'ruangan', $ruangNama);

    // Memasukkan laporan ke database
    $sql = "INSERT INTO laporan (id_gedung, id_pelapor, id_fasilitas, id_ruangan, tanggal_laporan, deskripsi_masalah, status, foto_laporan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiissss", $id_gedung, $id_pelapor, $id_fasilitas, $id_ruangan, $tanggal_laporan, $deskripsi, $status, $foto_path_db);
    $stmt->execute();
    
    // Jika berhasil
    http_response_code(200);
    echo json_encode(["status" => "success", "message" => "Laporan berhasil dikirim."]);

} catch (mysqli_sql_exception $e) {
    // Menangkap error spesifik dari database
    http_response_code(500);
    echo json_encode(["status" => "error", "message" => "Database Error: " . $e->getMessage()]);
} catch (Exception $e) {
    // Menangkap error umum lainnya (misal: validasi, upload)
    http_response_code(400);
    echo json_encode(["status" => "error", "message" => "Application Error: " . $e->getMessage()]);
} finally {
    // Selalu tutup koneksi
    $conn->close();
}
?>
