<?php
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../app/models/Database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'PUT' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Metode HTTP tidak diizinkan. Gunakan PUT atau POST."
    ]);
    exit();
}

try {
    $database = new Database();
    $conn = $database->conn;

    $input = json_decode(file_get_contents("php://input"), true);

    if (
        empty($input['id']) || empty($input['nama_barang']) || 
        empty($input['jumlah']) || empty($input['kategori']) || empty($input['harga'])
    ) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Gagal memperbarui data. ID dan kolom wajib tidak boleh kosong."
        ]);
        exit();
    }

    $id          = (int)$input['id'];
    $nama_barang = trim($input['nama_barang']);
    $jumlah      = (int)$input['jumlah'];
    $kategori    = trim($input['kategori']);
    $harga       = (float)$input['harga'];
    $keterangan  = isset($input['keterangan']) ? trim($input['keterangan']) : null;
    $gambar      = isset($input['gambar']) ? trim($input['gambar']) : null;
    $update_at   = date('Y-m-d H:i:s');

    $check_stmt = $conn->prepare("SELECT id FROM barang WHERE id = ?");
    $check_stmt->execute([$id]);
    if ($check_stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "Data barang tidak ditemukan di sistem."
        ]);
        exit();
    }

    $query = "UPDATE barang SET nama_barang = ?, jumlah = ?, kategori = ?, harga = ?, keterangan = ?, gambar = ?, update_at = ? WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt->execute([$nama_barang, $jumlah, $kategori, $harga, $keterangan, $gambar, $update_at, $id])) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Data barang berhasil diperbarui."
        ]);
    } else {
        throw new PDOException("Gagal mengeksekusi perintah pembaruan.");
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Terjadi kesalahan sistem: " . $e->getMessage()
    ]);
}