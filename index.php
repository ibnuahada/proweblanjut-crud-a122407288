<?php
session_start();

if (!isset($_SESSION["user_id"])) {
    header("Location: login.php");
    exit();
}

include "includes/header.php";
?>

<div class="main-layout">

<?php include "includes/menu.php"; ?>

<div class="content">

<?php

$page = $_GET['page'] ?? 'data_barang';

if($page == "data_barang"){
include "pages/data_barang.php";
}
elseif($page == "tambah"){
include "pages/tambah.php";
}
elseif($page == "edit"){
include "pages/edit.php";
}

?>

</div>
</div>

<?php include "includes/footer.php"; ?>