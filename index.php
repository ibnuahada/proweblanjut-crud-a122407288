<?php
session_start();

// Proteksi Autentikasi Pengguna
if (!isset($_SESSION["user_id"])) {
    if (isset($_COOKIE["user_id"])) {
        $_SESSION["user_id"] = $_COOKIE["user_id"];
    } else {
        header("Location: login.php");
        exit();
    }
}

// Panggil Controller Utama
require_once __DIR__ . '/app/controllers/BarangController.php';
$controller = new BarangController();

// Mengambil navigasi halaman
$page = $_GET['page'] ?? 'data_barang';

// Tampilkan Komponen Struktur Konsisten (Layout)
include "includes/header.php";
?>

<div class="main-layout">
    <?php include "includes/menu.php"; ?>

    <div class="content">
        <?php
        // Routing Aksi ke Controller terkait
        switch ($page) {
            case 'data_barang':
                $controller->index();
                break;
            case 'tambah':
                $controller->create();
                break;
            case 'edit':
                $controller->edit();
                break;
            case 'hapus':
                $controller->delete();
                break;
            default:
                $controller->index();
                break;
        }
        ?>
    </div>
</div>

<?php include "includes/footer.php"; ?>