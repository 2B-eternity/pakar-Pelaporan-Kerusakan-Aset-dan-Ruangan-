<?php
header("Access-Control-Allow-Origin: http://localhost:5173");
header("Content-Type: application/json; charset=UTF-8");

$host = "localhost";
$db_name = "project_sbd";
$username = "root";
$password_db = "";

$conn = new mysqli($host, $username, $password_db, $db_name);
if ($conn->connect_error) {
    http_response_code(500);
    echo json_encode(["message" => "Koneksi database gagal."]);
    exit();
}

$sql = "
    SELECT 
        l.id_laporan,
        p.nama_pelapor,
        p.no_hp,
        g.nama_gedung,
        r.nama_ruangan,
        f.nama_fasilitas,
        l.tanggal_laporan,
        l.deskripsi_masalah,
        l.status
    FROM laporan l
    JOIN pelapor p ON l.id_pelapor = p.id_pelapor
    JOIN gedung g ON l.id_gedung = g.id_gedung
    JOIN ruangan r ON l.id_ruangan = r.id_ruangan
    JOIN fasilitas f ON l.id_fasilitas = f.id_fasilitas
    ORDER BY l.id_laporan DESC
";

$result = $conn->query($sql);

$laporan_arr = [];
if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        array_push($laporan_arr, $row);
    }
}

echo json_encode($laporan_arr);

$conn->close();
?>
