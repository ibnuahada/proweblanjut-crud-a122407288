<?php
include "koneksi.php";

// Mulai session jika belum ada (untuk pesan error/sukses)
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if($_SERVER['REQUEST_METHOD'] == 'POST') {
    // 4. Implementasi Validasi Server
    $kode_barang = trim($_POST['kode_barang']);
    $nama_barang = trim($_POST['nama_barang']);
    $jumlah = $_POST['jumlah'];
    $kategori = $_POST['kategori'];
    $harga = $_POST['harga'];
    $keterangan = $_POST['keterangan'];
    
    $errors = [];

    if (empty($nama_barang)) {
        $errors[] = "Nama barang tidak boleh kosong.";
    }
    if (!is_numeric($jumlah) || $jumlah < 0) {
        $errors[] = "Jumlah harus berupa angka valid.";
    }
    if (!is_numeric($harga) || $harga < 0) {
        $errors[] = "Harga harus berupa angka valid.";
    }

    $nama_file_unik = null;
    
    if (isset($_FILES['gambar']) && $_FILES['gambar']['error'] === UPLOAD_ERR_OK) {
        $file_tmp = $_FILES['gambar']['tmp_name'];
        $file_name = $_FILES['gambar']['name'];
        $file_size = $_FILES['gambar']['size'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        $allowed_ext = ['jpg', 'jpeg', 'png'];
        if (!in_array($file_ext, $allowed_ext)) {
            $errors[] = "Format file tidak diizinkan. Gunakan JPG atau PNG.";
        }
        if ($file_size > 2000000) { 
            $errors[] = "Ukuran file terlalu besar (Maks 2MB).";
        }

        if (empty($errors)) {
            $nama_file_unik = uniqid() . '_' . $file_name;
            
            $target_dir = "uploads/";
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            if (!move_uploaded_file($file_tmp, $target_dir . $nama_file_unik)) {
                $errors[] = "Gagal mengunggah gambar.";
            }
        }
    }

    if (!empty($errors)) {
        $_SESSION['pesan'] = implode("<br>", $errors);
        $_SESSION['tipe'] = "error";
    } else {
        $check_query = $conn->prepare("SELECT id FROM barang WHERE kode_barang = ?");
        $check_query->execute([$kode_barang]);

        if ($check_query->rowCount() > 0) {
            $_SESSION['pesan'] = "Kode barang sudah digunakan!";
            $_SESSION['tipe'] = "error";
        } else {
            $query = $conn->prepare("INSERT INTO barang (kode_barang, nama_barang, jumlah, kategori, harga, keterangan, gambar) VALUES (?, ?, ?, ?, ?, ?, ?)");
            
            try {
                $query->execute([
                    $kode_barang, 
                    $nama_barang, 
                    $jumlah, 
                    $kategori, 
                    $harga, 
                    $keterangan,
                    $nama_file_unik
                ]);

                $_SESSION['pesan'] = "Barang berhasil ditambahkan!";
                $_SESSION['tipe'] = "success";

                header("Location: index.php?page=data_barang");
                exit();

            } catch(PDOException $e) {
                $_SESSION['pesan'] = "Gagal menambahkan barang: " . $e->getMessage();
                $_SESSION['tipe'] = "error";
            }
        }
    }
}
?>

<div class="container">
    <h2 class="page-title">Tambah Barang</h2>
    
    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="message <?php echo $_SESSION['tipe']; ?>" style="margin-bottom: 20px; padding: 10px; border-radius: 5px; background: <?php echo $_SESSION['tipe'] == 'success' ? '#dcfce7' : '#fee2e2'; ?>;">
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
            <label>Kode Barang *</label>
            <input type="text" name="kode_barang" value="<?php echo isset($_POST['kode_barang']) ? htmlspecialchars($_POST['kode_barang']) : ''; ?>" required>
            
            <label>Nama Barang *</label>
            <input type="text" name="nama_barang" value="<?php echo isset($_POST['nama_barang']) ? htmlspecialchars($_POST['nama_barang']) : ''; ?>" required>
            
            <label>Jumlah *</label>
            <input type="number" name="jumlah" value="<?php echo isset($_POST['jumlah']) ? htmlspecialchars($_POST['jumlah']) : ''; ?>" required>
            
            <label for="kategori"> Kategori *</label>
            <select name="kategori" required>
                <option value="">Pilih Kategori</option>
                <?php
                $opts = ['ATK', 'Alat Kebersihan', 'Alat Olahraga', 'Furniture', 'Aksesoris'];
                foreach ($opts as $opt) {
                    $selected = (isset($_POST['kategori']) && $_POST['kategori'] == $opt) ? 'selected' : '';
                    echo "<option value=\"$opt\" $selected>$opt</option>";
                }
                ?>
            </select>
            
            <label>Harga *</label>
            <input type="number" name="harga" value="<?php echo isset($_POST['harga']) ? htmlspecialchars($_POST['harga']) : ''; ?>" required>
            
            <label>Keterangan</label>
            <textarea name="keterangan" rows="4"><?php echo isset($_POST['keterangan']) ? htmlspecialchars($_POST['keterangan']) : ''; ?></textarea>
            
            <br><br>
            
            <label>Upload Gambar Barang</label>
            <input type="file" name="gambar" accept="image/png, image/jpeg">
            <small>Format: jpg, jpeg, png. Maks: 2MB</small>
            <br><br>

            <button type="submit" class="btn btn-primary">Tambah Barang</button>
            <button type="reset" class="btn btn-secondary">Reset</button>
        </form>
    </div>
</div>