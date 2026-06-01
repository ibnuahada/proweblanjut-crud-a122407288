<?php
header("Content-Type: application/json; charset=UTF-8");

require_once __DIR__ . '/../app/models/Database.php';

if ($_SERVER['REQUEST_METHOD'] !== 'DELETE' && $_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode([
        "status" => "error",
        "message" => "Metode HTTP tidak diizinkan. Gunakan DELETE atau POST."
    ]);
    exit();
}

try {
    $database = new Database();
    $conn = $database->conn;

    $input = json_decode(file_get_contents("php://input"), true);

    if (empty($input['id'])) {
        http_response_code(400);
        echo json_encode([
            "status" => "error",
            "message" => "Gagal menghapus data. ID barang wajib dicantumkan."
        ]);
        exit();
    }

    $id = (int)$input['id'];

    $check_stmt = $conn->prepare("SELECT id FROM barang WHERE id = ?");
    $check_stmt->execute([$id]);
    if ($check_stmt->rowCount() === 0) {
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "Data barang tidak ditemukan. Proses hapus dibatalkan."
        ]);
        exit();
    }

    $query = "DELETE FROM barang WHERE id = ?";
    $stmt = $conn->prepare($query);
    
    if ($stmt->execute([$id])) {
        http_response_code(200);
        echo json_encode([
            "status" => "success",
            "message" => "Data barang berhasil dihapus dari sistem."
        ]);
    } else {
        throw new PDOException("Gagal mengeksekusi perintah penghapusan.");
    }

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Terjadi kesalahan sistem: " . $e->getMessage()
    ]);
}