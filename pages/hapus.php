<?php
session_start();
include '../koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    try {
        $stmt_select = $conn->prepare("SELECT gambar FROM barang WHERE id = ?");
        $stmt_select->execute([$id]);
        $barang = $stmt_select->fetch(PDO::FETCH_ASSOC);

        if ($barang) {
            $stmt_delete = $conn->prepare("DELETE FROM barang WHERE id = ?");
            $stmt_delete->execute([$id]);

            if (!empty($barang['gambar'])) {
                $file_path = "../uploads/" . $barang['gambar'];
                
                if (file_exists($file_path)) {
                    unlink($file_path); 
                }
            }

            $_SESSION['pesan'] = 'Data dan gambar berhasil dihapus!';
            $_SESSION['tipe'] = 'success';
        } else {
            $_SESSION['pesan'] = 'Data tidak ditemukan!';
            $_SESSION['tipe'] = 'error';
        }

    } catch (PDOException $e) {
        $_SESSION['pesan'] = 'Gagal Menghapus Barang! ' . $e->getMessage();
        $_SESSION['tipe'] = 'error';
    }
}

header('Location: ../index.php?page=data_barang');
exit();
?>