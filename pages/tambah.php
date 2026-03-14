<?php
include "../koneksi.php";

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    $kode_barang = $_POST['kode_barang'];
    $nama_barang = $_POST['nama_barang'];
    $jumlah = $_POST['jumlah'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $keterangan = $_POST['keterangan'];

    $check_query = $conn->prepare("SELECT id FROM barang WHERE kode_barang = ?");
    $check_query->execute([$kode_barang]);

    if ($check_query->rowCount() > 0) {
        $_SESSION['pesan'] = "Kode barang sudah digunakan!";
        $_SESSION['tipe'] = "error";
    } else {
        $query = $conn->prepare("INSERT INTO barang (kode_barang, nama_barang, jumlah, kategori, harga, keterangan) VALUES (?, ?, ?, ?, ?, ?)");
        
        try {
            $query->execute([
                $kode_barang, 
                $nama_barang, 
                $jumlah, 
                $kategori, 
                $harga, 
                $keterangan
            ]);

            $_SESSION['pesan'] = "Barang berhasil ditambahkan!";
            $_SESSION['tipe'] = "success";

            header("Location: data_barang.php");
            exit();

        } catch(PDOException $e) {
            $_SESSION['pesan'] = "Gagal menambahkan barang: " . $e->getMessage();
            $_SESSION['tipe'] = "error";
        }
    }
}

?>

<form method="POST">
    <label>Kode Barang</label>
    <input type="text" id="kode_barang" name="kode_barang"> <br><br>
    <label>Nama Barang</label>
    <input type="text" id="nama_barang" name="nama_barang"> <br><br>
    <label>Jumlah</label>
    <input type="number" id="jumlah" name="jumlah"> <br><br>
    <label for="kategori"> Kategori</label>
        <select id="kategori" name="kategori" required>
        <option value="">Pilih Kategori</option>
        <option value="ATK">ATK</option>
        <option value="Alat Kebersihan">Alat Kebersihan</option>
        <option value="Alat Olahragra">Alat Olahragra</option>
        <option value="Furniture">Furniture</option>
        <option value="Aksesoris">Aksesoris</option>
    </select> <br><br>
    <label>Harga</label>
    <input type="number" id="harga" name="harga"> <br><br>
    <label>Keterangan</label>
    <textarea id="keterangan" name="keterangan" rows="4"></textarea> <br><br>

    <button type="submit">Simpan</button>
    <button type="reset">Reset</button>
</form>