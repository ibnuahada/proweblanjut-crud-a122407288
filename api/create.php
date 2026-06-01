<?php
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../app/models/Database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Metode HTTP tidak diizinkan. Gunakan POST."
    ]);
    exit();
}

try {
    $database = new Database();
    $conn = $database->conn;

    $input = json_decode(file_get_contents("php://input"), true);

    if (
        empty($input['kode_barang']) || empty($input['nama_barang']) || 
        empty($input['jumlah']) || empty($input['kategori']) || empty($input['harga'])
    ) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Gagal menambah data. Kolom wajib tidak boleh kosong."
        ]);
        exit();
    }

    $kode_barang   = trim($input['kode_barang']);
    $nama_barang   = trim($input['nama_barang']);
    $jumlah        = (int)$input['jumlah'];
    $kategori      = trim($input['kategori']);
    $harga         = (float)$input['harga'];
    $keterangan    = isset($input['keterangan']) ? trim($input['keterangan']) : null;
    $gambar        = isset($input['gambar']) ? trim($input['gambar']) : null;
    $tanggal_masuk = date('Y-m-d H:i:s');

    $check_stmt = $conn->prepare("SELECT id FROM barang WHERE kode_barang = ?");
    $check_stmt->execute([$kode_barang]);
    if ($check_stmt->rowCount() > 0) {
        http_response_code(409);
        echo json_encode([
            "status" => "error",
            "message" => "Kode barang sudah terdaftar di sistem."
        ]);
        exit();
    }

    $query = "INSERT INTO barang (kode_barang, nama_barang, jumlah, kategori, harga, tanggal_masuk, gambar, keterangan) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($query);
    
    if ($stmt->execute([$kode_barang, $nama_barang, $jumlah, $kategori, $harga, $tanggal_masuk, $gambar, $keterangan])) {
        http_response_code(201);
        echo json_encode([
            "status" => "success",
            "message" => "Data barang berhasil ditambahkan."
        ]);
    } else {
        throw new PDOException("Gagal mengeksekusi perintah simpan.");
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Terjadi kesalahan sistem: " . $e->getMessage()
    ]);
}