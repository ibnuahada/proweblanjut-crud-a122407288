<?php
include '../koneksi.php';

session_start();

$id = ISSET($_GET['id']) ? intval($_GET['id']) : 0;

if ($id > 0) {
    $query = $conn->prepare("DELETE FROM barang WHERE id = ?");

    try {
        $query->execute([$id]);

        $_SESSION['pesan'] = 'Data Berhasil Dihapus!';
        $_SESSION['tipe'] = 'success';
    } catch (PDOException $e) {
        $_SESSION['pesan'] = 'Gagal Menambahkan Barang!' . $e->getMessage();
        $_SESSION['tipe'] = 'error';
    }
}

header('Location: data_barang.php');
exit();
?>