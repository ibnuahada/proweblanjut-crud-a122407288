<?php
include "../koneksi.php";

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$query = $conn->prepare("SELECT * FROM barang WHERE id = ?");
$query->execute([$id]);
$barang = $query->fetch(PDO::FETCH_ASSOC);

if(!$barang) {
    $_SESSION['pesan'] = "Barang Tidak Ditemukan!";
    $_SESSION['tipe'] = "error";
    header("Location: data_barang.php");
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

            header('Location: data_barang.php');
            exit();
        } catch (PDOException $e) {
            $_SESSION['pesan'] = 'Gagal Memperbarui Barang!' . $e->getMessage();
            $_SESSION['tipe'] = 'error';
        }
}
?>

<form method="POST">
    <label>Kode Barang</label>
    <input type="text" id="kode_barang" name="kode_barang" value="<?php echo $barang['kode_barang']; ?>" disabled> <br><br>
    <label>Nama Barang</label>
    <input type="text" id="nama_barang" name="nama_barang"  value="<?php echo $barang['nama_barang']; ?>" required> <br><br>
    <label>Jumlah</label>
    <input type="number" id="jumlah" name="jumlah" value="<?php echo $barang['jumlah']; ?>" required> <br><br>
    <label for="kategori"> Kategori</label>
        <select id="kategori" name="kategori" required>
        <option value="">Pilih Kategori</option>
        <option value="ATK" 
        <?php echo $barang['kategori'] == 'ATK' ? 'selected' : ''; ?>>ATK</option>
        <option value="Alat Kebersihan"
        <?php echo $barang['kategori'] == 'Alat Kebersihan' ? 'selected' : ''; ?>>Alat Kebersihan</option>
        <option value="Alat Olahraga"
        <?php echo $barang['kategori'] == 'Alat Olahraga' ? 'selected' : ''; ?>>Alat Olahragra</option>
        <option value="Furniture"
        <?php echo $barang['kategori'] == 'Furniture' ? 'selected' : ''; ?>>Furniture</option>
        <option value="Aksesoris"
        <?php echo $barang['kategori'] == 'Aksesoris' ? 'selected' : ''; ?>>Aksesoris</option>
    </select> <br><br>
    <label>Harga</label>
    <input type="number" id="harga" name="harga" value="<?php echo $barang['harga']; ?>" required> <br><br>
    <label>Keterangan</label>
    <textarea id="keterangan" name="keterangan" rows="4" required><?php echo $barang['keterangan']; ?></textarea> <br><br>

    <button type="submit">Simpan Perubahan</button>
    <button type="reset">Reset</button>
</form>