<?php

class Database {
    private $host = "localhost";
    private $user = "root";
    private $password = "";
    private $db = "db_produk";
    public $conn;

    public function __construct() {
        try {
            $this->conn = new PDO(
                "mysql:host={$this->host};dbname={$this->db}",
                $this->user,
                $this->password
            );
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch(PDOException $e) {
            die("Koneksi Gagal: " . $e->getMessage());
        }
    }
}