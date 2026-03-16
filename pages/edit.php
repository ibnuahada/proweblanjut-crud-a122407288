<?php
include 'koneksi.php';

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = $conn->prepare("SELECT * FROM barang WHERE id = ?");
$query->execute([$id]);
$barang = $query->fetch(PDO::FETCH_ASSOC);

if(!$barang) {
    $_SESSION['pesan'] = "Barang Tidak Ditemukan!";
    $_SESSION['tipe'] = "error";
    header("Location: index.php?page=data_barang");
    exit();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $kode_barang = $_POST['kode_barang'];
        $nama_barang = $_POST['nama_barang'];
        $jumlah = $_POST['jumlah'];
        $kategori = $_POST['kategori'];
        $harga = $_POST['harga'];
        $keterangan = $_POST['keterangan'];

        $query = $conn->prepare("
            UPDATE barang SET 
                nama_barang = ?,
                jumlah = ?,
                kategori = ?,
                harga = ?,
                keterangan = ?
            WHERE id = ?");
        
        try {
            $query->execute([
                $nama_barang,
                $jumlah,
                $kategori,
                $harga,
                $keterangan,
                $id
            ]);

            $_SESSION['pesan'] = 'Barang Berhasil diperbarui!';
            $_SESSION['tipe'] = 'Success';

            header('Location: ../index.php?page=data_barang');
            exit();
        } catch (PDOException $e) {
            $_SESSION['pesan'] = 'Gagal Memperbarui Barang!' . $e->getMessage();
            $_SESSION['tipe'] = 'error';
        }
}
?>

<div class="container">
<h2 class="page-title">Edit Barang</h2>

<a href="index.php?page=data_barang" 
class="btn back-link">
<i class="fas fa-arrow-left"></i> Kembali</a>

<div class="form-card">

<form method="POST">
    <label>Kode Barang</label>
    <input type="text" id="kode_barang" name="kode_barang" value="<?php echo $barang['kode_barang']; ?>" disabled>
    <label>Nama Barang</label>
    <input type="text" id="nama_barang" name="nama_barang"  value="<?php echo $barang['nama_barang']; ?>" required>
    <label>Jumlah</label>
    <input type="number" id="jumlah" name="jumlah" value="<?php echo $barang['jumlah']; ?>" required>
    <label for="kategori"> Kategori</label>
        <select id="kategori" name="kategori" required>
        <option value="">Pilih Kategori</option>
        <option value="ATK" 
        <?php echo $barang['kategori'] == 'ATK' ? 'selected' : ''; ?>>ATK</option>
        <option value="Alat Kebersihan"
        <?php echo $barang['kategori'] == 'Alat Kebersihan' ? 'selected' : ''; ?>>Alat Kebersihan</option>
        <option value="Alat Olahraga"
        <?php echo $barang['kategori'] == 'Alat Olahraga' ? 'selected' : ''; ?>>Alat Olahraga</option>
        <option value="Furniture"
        <?php echo $barang['kategori'] == 'Furniture' ? 'selected' : ''; ?>>Furniture</option>
        <option value="Aksesoris"
        <?php echo $barang['kategori'] == 'Aksesoris' ? 'selected' : ''; ?>>Aksesoris</option>
    </select>
    <label>Harga</label>
    <input type="number" id="harga" name="harga" value="<?php echo (int)$barang['harga']; ?>" required>
    <label>Keterangan</label>
    <textarea id="keterangan" name="keterangan" rows="4"><?php echo $barang['keterangan']; ?></textarea> <br><br>

    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
    <button type="reset" class="btn btn-secondary">Reset</button>
</form>

</div>
</div>