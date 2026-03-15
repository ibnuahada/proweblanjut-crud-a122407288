<?php
include 'koneksi.php';

$query = $conn->query("SELECT * FROM barang");
$data = $query->fetchAll();
?>

<!-- <link rel="stylesheet" href="style/style.css"> -->

<div class="container">

<h2 class="page-title">Data Barang</h2>

<div class="top-bar">
<a href="index.php?page=tambah" class="btn btn-primary">+ Tambah Barang</a>
</div>
<table class="table">
<tr>
    <th>No</th>
    <th>Kode Barang</th>
    <th>Nama Barang</th>
    <th>Jumlah</th>
    <th>Harga</th>
    <th>Aksi</th>
</tr>

<?php if(count($data) > 0): ?>

<?php $no = 1; foreach($data as $row) {?>

<tr>
    <td><?php echo $no++;?></td>  
    <td><?php echo $row['kode_barang']; ?></td>
    <td><?php echo $row['nama_barang']; ?></td>
    <td><?php echo $row['jumlah']; ?></td>
    <td>
        <span>
        Rp<?php echo number_format($row['harga'], 0, ',', '.'); ?>
        </span>
    </td>
    <td>
        <a href="index.php?page=edit&id=<?php echo $row['id']; ?>" class="btn btn-warning">Edit</a>
<a href="pages/hapus.php?id=<?php echo $row['id']; ?>"
class="btn btn-danger"
onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus
</a>
    </td>
</tr>

<?php } ?>
<?php else: ?>

<tr>
    <td colspan="6"> Data Kosong </td>
</tr>

<?php endif; ?>
</table>
</div>