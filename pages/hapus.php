<?php
include '../koneksi.php';

session_start();

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {

    $query = $conn->prepare("DELETE FROM barang WHERE id = ?");

    try {
        $query->execute([$id]);

        $_SESSION['pesan'] = 'Data Berhasil Dihapus!';
        $_SESSION['tipe'] = 'success';

    } catch (PDOException $e) {

        $_SESSION['pesan'] = 'Gagal Menghapus Barang! ' . $e->getMessage();
        $_SESSION['tipe'] = 'error';
    }
}

header('Location: ../index.php?page=data_barang');
exit();
?>