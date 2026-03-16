<?php
include 'koneksi.php';

$limit = 5;

$page_now = isset($_GET['halaman']) ? (int)$_GET['halaman'] : 1;
$start = ($page_now - 1) * $limit;

$search = $_GET['search'] ?? '';

if($search != ""){

    $total_query = $conn->prepare("
    SELECT COUNT(*) FROM barang
    WHERE nama_barang LIKE ? OR kode_barang LIKE ?
    ");

    $like = "%$search%";
    $total_query->execute([$like,$like]);

    $total_data = $total_query->fetchColumn();

    $query = $conn->prepare("
    SELECT * FROM barang
    WHERE nama_barang LIKE ? OR kode_barang LIKE ?
    ORDER BY id DESC
    LIMIT $start,$limit
    ");

    $query->execute([$like,$like]);

}else{

    $total_query = $conn->query("SELECT COUNT(*) FROM barang");
    $total_data = $total_query->fetchColumn();

    $query = $conn->prepare("
    SELECT * FROM barang
    ORDER BY id DESC
    LIMIT $start,$limit
    ");

    $query->execute();

}

$total_page = ceil($total_data / $limit);
?>

<div class="container">

<h2 class="page-title">Data Barang</h2>

<div class="top-bar">
<a href="index.php?page=tambah" class="btn btn-primary">+ Tambah Barang</a>
</div>

<form method="GET" action="index.php" class="search-box">

    <input type="hidden" name="page" value="data_barang">
    <input
    type="text" 
    name="search" 
    placeholder="Cari barang..."
    value="<?php echo $_GET['search'] ?? ''; ?>"
    style="outline: none;">

    <button type="submit">Cari</button>
</form>

<table class="table">
<tr>
    <th>No</th>
    <th>Kode Barang</th>
    <th>Nama Barang</th>
    <th>Kategori</th>
    <th>Jumlah</th>
    <th>Harga</th>
    <th>Aksi</th>
</tr>

<?php if($query->rowCount() > 0): ?>

<?php $no = $start + 1; while($row = $query->fetch(PDO::FETCH_ASSOC)){ ?>

<tr>
    <td><?php echo $no++;?></td>  
    <td><?php echo $row['kode_barang']; ?></td>
    <td><?php echo $row['nama_barang']; ?></td>
    <td><?php echo $row['kategori']; ?></td>
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
<div class="pagination">

<?php if($page_now > 1){ ?>
<a href="index.php?page=data_barang&search=<?php echo $search ?>&halaman=<?php echo $page_now-1 ?>">
Prev
</a>
<?php } ?>

<?php for($i=1;$i<=$total_page;$i++){ ?>

<a 
href="index.php?page=data_barang&search=<?php echo $search ?>&halaman=<?php echo $i ?>"
class="<?php echo ($i==$page_now)?'active':'' ?>"
>
<?php echo $i ?>
</a>

<?php } ?>

<?php if($page_now < $total_page){ ?>
<a href="index.php?page=data_barang&search=<?php echo $search ?>&halaman=<?php echo $page_now+1 ?>">
Next
</a>
<?php } ?>

</div>

</div>