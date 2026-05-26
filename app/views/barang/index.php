<div class="container">
    <h2 class="page-title">Data Barang</h2>

    <?php if (isset($_SESSION['pesan'])): ?>
        <div class="message <?php echo $_SESSION['tipe']; ?>" style="padding: 15px; margin-bottom: 20px; border-radius: 8px; background: <?php echo $_SESSION['tipe'] == 'success' ? '#dcfce7' : '#fee2e2'; ?>; color: <?php echo $_SESSION['tipe'] == 'success' ? '#166534' : '#991b1b'; ?>;">
            <?php echo $_SESSION['pesan']; unset($_SESSION['pesan']); unset($_SESSION['tipe']); ?>
        </div>
    <?php endif; ?>

    <div class="top-bar">
        <a href="index.php?page=tambah" class="btn btn-primary">+ Tambah Barang</a>
    </div>

    <form method="GET" action="index.php" class="search-box">
        <input type="hidden" name="page" value="data_barang">
        <input type="text" name="search" placeholder="Cari barang..." value="<?php echo htmlspecialchars($search); ?>" style="outline: none;">
        <button type="submit">Cari</button>
    </form>

    <table class="table">
        <tr>
            <th>No</th>
            <th>Gambar</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Kategori</th>
            <th>Jumlah</th>
            <th>Harga</th>
            <th>Aksi</th>
        </tr>
        <?php if (!empty($barang_list)): ?>
            <?php $no = $start + 1; foreach ($barang_list as $row): ?>
            <tr>
                <td><?php echo $no++; ?></td>  
                <td>
                    <?php if (!empty($row['gambar'])): ?>
                        <img src="public/uploads/<?php echo htmlspecialchars($row['gambar']); ?>" width="50" alt="Gambar">
                    <?php else: ?>
                        <span style="color: #999;">No Image</span>
                    <?php endif; ?>
                </td>
                <td><?php echo htmlspecialchars($row['kode_barang']); ?></td>
                <td><?php echo htmlspecialchars($row['nama_barang']); ?></td>
                <td><?php echo htmlspecialchars($row['kategori']); ?></td>
                <td><?php echo htmlspecialchars($row['jumlah']); ?></td>
                <td>Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?></td>
                <td>
                    <a href="index.php?page=edit&id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-warning">Edit</a>
                    <a href="index.php?page=hapus&id=<?php echo htmlspecialchars($row['id']); ?>" class="btn btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</a>
                </td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr><td colspan="8" style="text-align:center; padding: 20px;">Data Kosong</td></tr>
        <?php endif; ?>
    </table>

    <div class="pagination">
        <?php if($page_now > 1): ?>
            <a href="index.php?page=data_barang&search=<?php echo urlencode($search); ?>&halaman=<?php echo $page_now-1 ?>">Prev</a>
        <?php endif; ?>
        <?php for($i=1; $i<=$total_page; $i++): ?>
            <a href="index.php?page=data_barang&search=<?php echo urlencode($search); ?>&halaman=<?php echo $i ?>" class="<?php echo ($i==$page_now)?'active':'' ?>"><?php echo $i ?></a>
        <?php endfor; ?>
        <?php if($page_now < $total_page): ?>
            <a href="index.php?page=data_barang&search=<?php echo urlencode($search); ?>&halaman=<?php echo $page_now+1 ?>">Next</a>
        <?php endif; ?>
    </div>
</div>