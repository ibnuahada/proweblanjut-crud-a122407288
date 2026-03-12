<?php
include "../koneksi.php";

$query = $conn->query("SELECT * FROM barang");
$data = $query->fetchAll();
?>

<div>
    <a href="tambah.php">Tambah Barang</a>
</div>
<table border="1">
<tr>
    <th>No</th>
    <th>Kode Barang</th>
    <th>Nama Barang</th>
    <th>Jumlah</th>
    <th>Harga</th>
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
        <a href="edit.php">Edit</a>
        <a href="hapus.php">Hapus</a>
    </td>
</tr>

<?php } ?>
<?php else: ?>

<tr>
    <td colspan="5"> Data Kosong </td>
</tr>

<?php endif; ?>
</table>