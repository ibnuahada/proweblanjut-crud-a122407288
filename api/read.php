<?php
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../app/models/Database.php';

try {
    $database = new Database();
    $conn = $database->conn;

    $query = "SELECT id, kode_barang, nama_barang, jumlah, kategori, harga, tanggal_masuk, gambar, update_at, keterangan FROM barang ORDER BY id DESC";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    $barang_list = $stmt->fetchAll(PDO::FETCH_ASSOC);

    http_response_code(200);
    echo json_encode([
        "status" => "success",
        "message" => "Data barang berhasil diambil.",
        "data" => $barang_list
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Terjadi kesalahan sistem: " . $e->getMessage()
    ]);
}