<?php

$host = "localhost";
$user = "root";
$password = "";
$db = "db_produk";

try {
    $conn = new PDO(
        "mysql:host=$host;
        dbname=$db",
        $user,
        $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    echo "Koneksi Gagal:" . $e->getMessage();
}

?>