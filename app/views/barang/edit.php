<div class="container" style="max-width: 600px; margin: 20px auto; background: white; padding: 30px; border-radius: 8px; box-shadow: 0 4px 6px rgba(0,0,0,0.05);">
    <h2 class="page-title">Edit Barang</h2>

    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="message <?php echo $_SESSION['tipe']; ?>" style="padding: 15px; margin-bottom: 20px; border-radius: 8px; background: #fee2e2; color: #991b1b; border: 1px solid #fecaca;">
            <?php echo $_SESSION['pesan']; unset($_SESSION['pesan']); unset($_SESSION['tipe']); ?>
        </div>
    <?php endif; ?>

    <form method="POST" action="index.php?page=edit&id=<?php echo htmlspecialchars($barang['id']); ?>" enctype="multipart/form-data">
        <label>Kode Barang (Tidak dapat diubah)</label>
        <input type="text" value="<?php echo htmlspecialchars($barang['kode_barang']); ?>" disabled style="background-color: #f3f4f6; color: #6b7280;">
        
        <label>Nama Barang *</label>
        <input type="text" name="nama_barang" value="<?php echo htmlspecialchars($barang['nama_barang']); ?>" required>
        
        <label>Jumlah *</label>
        <input type="number" name="jumlah" value="<?php echo htmlspecialchars((int)$barang['jumlah']); ?>" required>
        
        <label>Kategori *</label>
        <select name="kategori" required>
            <?php
            $categories = ['ATK', 'Alat Kebersihan', 'Alat Olahraga', 'Furniture', 'Aksesoris'];
            foreach ($categories as $cat) {
                $selected = ($barang['kategori'] == $cat) ? 'selected' : '';
                echo "<option value=\"$cat\" $selected>$cat</option>";
            }
            ?>
        </select>
        
        <label>Harga *</label>
        <input type="number" name="harga" value="<?php echo htmlspecialchars((int)$barang['harga']); ?>" required>
        
        <label>Keterangan</label>
        <textarea name="keterangan" rows="4"><?php echo htmlspecialchars($barang['keterangan']); ?></textarea>
        
        <br>
        <label>Gambar Saat Ini</label><br>
        <?php if (!empty($barang['gambar'])): ?>
            <img src="public/uploads/<?php echo htmlspecialchars($barang['gambar']); ?>" width="100" style="margin-top: 10px; margin-bottom: 10px; border-radius: 5px; border: 1px solid #ddd;">
        <?php else: ?>
            <p style="color: #999; font-size: 0.9em; margin: 10px 0;">Tidak ada gambar.</p>
        <?php endif; ?>
        
        <br>
        <label>Ganti Gambar (Opsional)</label>
        <input type="file" name="gambar" accept="image/png, image/jpeg">
        <small style="color: #666; display: block; margin-top: 5px;">Format: jpg, png. Maks: 2MB</small>
        <br>

        <button type="submit" class="btn btn-warning" style="margin-top: 15px;">Simpan Perubahan</button>
        <a href="index.php?page=data_barang" class="back-link" style="margin-left: 15px; color: #666; text-decoration: none;">Batal</a>
    </form>
</div>