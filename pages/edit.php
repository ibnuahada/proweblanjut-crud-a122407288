<?php
include 'koneksi.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

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
    $nama_barang = trim($_POST['nama_barang']);
    $jumlah      = $_POST['jumlah'];
    $kategori    = $_POST['kategori'];
    $harga       = $_POST['harga'];
    $keterangan  = $_POST['keterangan'];
    
    $errors = [];

    if (empty($nama_barang)) {
        $errors[] = "Nama barang tidak boleh kosong.";
    }
    if (!is_numeric($jumlah) || !is_numeric($harga)) {
        $errors[] = "Jumlah dan harga harus berupa angka.";
    }

    $nama_file_unik = $barang['gambar'];

    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp  = $_FILES['gambar']['tmp_name'];
        $file_name = $_FILES['gambar']['name'];
        $file_size = $_FILES['gambar']['size'];
        $file_ext  = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        
        if (!in_array($file_ext, $allowed_ext)) {
            $errors[] = "Format file harus JPG atau PNG.";
        }
        if ($file_size > 2000000) {
            $errors[] = "Ukuran file maksimal 2MB.";
        }

        if (empty($errors)) {
            $nama_file_unik = uniqid() . '_' . $file_name;
            $target_dir = "uploads/";

            if (move_uploaded_file($file_tmp, $target_dir . $nama_file_unik)) {
                if (!empty($barang['gambar']) && file_exists($target_dir . $barang['gambar'])) {
                    unlink($target_dir . $barang['gambar']);
                }
            } else {
                $errors[] = "Gagal mengunggah gambar baru.";
            }
        }
    }

    if (empty($errors)) {
        $query = $conn->prepare("
            UPDATE barang SET 
                nama_barang = ?,
                jumlah = ?,
                kategori = ?,
                harga = ?,
                keterangan = ?,
                gambar = ?
            WHERE id = ?");
        
        try {
            $query->execute([
                $nama_barang,
                $jumlah,
                $kategori,
                $harga,
                $keterangan,
                $nama_file_unik,
                $id
            ]);

            $_SESSION['pesan'] = 'Barang Berhasil diperbarui!';
            $_SESSION['tipe'] = 'success';

            header('Location: index.php?page=data_barang');
            exit();
        } catch (PDOException $e) {
            $_SESSION['pesan'] = 'Gagal Memperbarui Barang! ' . $e->getMessage();
            $_SESSION['tipe'] = 'error';
        }
    } else {
        $_SESSION['pesan'] = implode("<br>", $errors);
        $_SESSION['tipe'] = "error";
    }
}
?>

<div class="container">
    <h2 class="page-title">Edit Barang</h2>

    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="message <?php echo $_SESSION['tipe']; ?>" style="padding: 10px; margin-bottom: 20px; border-radius: 5px; background: <?php echo $_SESSION['tipe'] == 'success' ? '#dcfce7' : '#fee2e2'; ?>;">
            <?php 
                echo $_SESSION['pesan']; 
                unset($_SESSION['pesan']); 
                unset($_SESSION['tipe']);
            ?>
        </div>
    <?php endif; ?>

    <a href="index.php?page=data_barang" class="btn back-link">
        <i class="fas fa-arrow-left"></i> Kembali
    </a>

    <div class="form-card">
        <form method="POST" enctype="multipart/form-data">
            <label>Kode Barang</label>
            <input type="text" value="<?php echo htmlspecialchars($barang['kode_barang']); ?>" disabled>
            
            <label>Nama Barang</label>
            <input type="text" name="nama_barang" value="<?php echo htmlspecialchars($barang['nama_barang']); ?>" required>
            
            <label>Jumlah</label>
            <input type="number" name="jumlah" value="<?php echo htmlspecialchars($barang['jumlah']); ?>" required>
            
            <label for="kategori">Kategori</label>
            <select name="kategori" required>
                <?php
                $categories = ['ATK', 'Alat Kebersihan', 'Alat Olahraga', 'Furniture', 'Aksesoris'];
                foreach ($categories as $cat) {
                    $selected = ($barang['kategori'] == $cat) ? 'selected' : '';
                    echo "<option value=\"$cat\" $selected>$cat</option>";
                }
                ?>
            </select>
            
            <label>Harga</label>
            <input type="number" name="harga" value="<?php echo htmlspecialchars((int)$barang['harga']); ?>" required>
            
            <label>Keterangan</label>
            <textarea name="keterangan" rows="4"><?php echo htmlspecialchars($barang['keterangan']); ?></textarea>
            
            <br>
            <label>Gambar Saat Ini</label><br>
            <?php if (!empty($barang['gambar'])): ?>
                <img src="uploads/<?php echo htmlspecialchars($barang['gambar']); ?>" width="100" style="margin-bottom: 10px; border-radius: 5px; border: 1px solid #ddd;">
            <?php else: ?>
                <p style="color: #999; font-size: 0.9em;">Tidak ada gambar.</p>
            <?php endif; ?>
            
            <br>
            <label>Ganti Gambar (Opsional)</label>
            <input type="file" name="gambar" accept="image/png, image/jpeg">
            <small>Format: jpg, png. Maks: 2MB</small>
            <br><br>

            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </form>
    </div>
</div>