<?php
require_once 'Database.php';

class BarangModel extends Database {
    
    // Untuk pagination dan search di halaman utama
    public function getData($start, $limit, $search = "") {
        if ($search != "") {
            $sql = "SELECT * FROM barang WHERE nama_barang LIKE ? OR kode_barang LIKE ? ORDER BY id DESC LIMIT $start, $limit";
            $stmt = $this->conn->prepare($sql);
            $like = "%$search%";
            $stmt->execute([$like, $like]);
        } else {
            $sql = "SELECT * FROM barang ORDER BY id DESC LIMIT $start, $limit";
            $stmt = $this->conn->prepare($sql);
            $stmt->execute();
        }
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Menghitung total data untuk jumlah pagination
    public function getTotalData($search = "") {
        if ($search != "") {
            $sql = "SELECT COUNT(*) FROM barang WHERE nama_barang LIKE ? OR kode_barang LIKE ?";
            $stmt = $this->conn->prepare($sql);
            $like = "%$search%";
            $stmt->execute([$like, $like]);
            return $stmt->fetchColumn();
        } else {
            $sql = "SELECT COUNT(*) FROM barang";
            return $this->conn->query($sql)->fetchColumn();
        }
    }

    // Mengambil data spesifik berdasarkan ID (untuk edit & hapus)
    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM barang WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    // Cek duplikasi kode barang
    public function checkKodeBarang($kode_barang) {
        $stmt = $this->conn->prepare("SELECT id FROM barang WHERE kode_barang = ?");
        $stmt->execute([$kode_barang]);
        return $stmt->rowCount() > 0;
    }

    // Menyimpan data barang baru
    public function save($data) {
        $sql = "INSERT INTO barang (kode_barang, nama_barang, jumlah, kategori, harga, keterangan, gambar) VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['kode_barang'],
            $data['nama_barang'],
            $data['jumlah'],
            $data['kategori'],
            $data['harga'],
            $data['keterangan'],
            $data['gambar']
        ]);
    }

    // Mengubah data barang
    public function update($id, $data) {
        $sql = "UPDATE barang SET nama_barang = ?, jumlah = ?, kategori = ?, harga = ?, keterangan = ?, gambar = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([
            $data['nama_barang'],
            $data['jumlah'],
            $data['kategori'],
            $data['harga'],
            $data['keterangan'],
            $data['gambar'],
            $id
        ]);
    }

    // Menghapus data barang
    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM barang WHERE id = ?");
        return $stmt->execute([$id]);
    }
}