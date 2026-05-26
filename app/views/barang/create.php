<div class="container" style="max-width: 600px; margin: 20px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <h2 class="page-title">Tambah Barang Baru</h2>

    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="message <?php echo $_SESSION['tipe']; ?>" style="padding: 15px; margin-bottom: 20px; border-radius: 8px; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;">
            <?php echo $_SESSION['pesan']; unset($_SESSION['pesan']); unset($_SESSION['tipe']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?page=tambah" enctype="multipart/form-data">
        <label>Kode Barang *</label>
        <input type="text" name="kode_barang" value="<?php echo isset($_POST['kode_barang']) ? htmlspecialchars($_POST['kode_barang']) : ''; ?>" required>
        
        <label>Nama Barang *</label>
        <input type="text" name="nama_barang" value="<?php echo isset($_POST['nama_barang']) ? htmlspecialchars($_POST['nama_barang']) : ''; ?>" required>
        
        <label>Jumlah *</label>
        <input type="number" name="jumlah" value="<?php echo isset($_POST['jumlah']) ? htmlspecialchars($_POST['jumlah']) : ''; ?>" required>
        
        <label>Kategori *</label>
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
        <small style="color: #666; display: block; margin-top: 5px;">Format: jpg, jpeg, png. Maks: 2MB</small>
        <br>

        <button type="submit" class="btn btn-primary" style="margin-top: 15px;">Tambah Barang</button>
        <a href="index.php?page=data_barang" class="back-link" style="margin-left: 15px; color: #666; text-decoration: none;">Kembali</a>
    </form>
</div>