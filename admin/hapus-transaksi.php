<?php

session_start();

include "koneksi.php";


if (!isset($_SESSION["login"])) {
    header("Location: ../login");
    return false;
}


$id_transaksi = $_GET["id_transaksi"];

mysqli_query($conn, "DELETE FROM transaksi WHERE id_transaksi = '$id_transaksi' ");

header("Location:penjualan");
return mysqli_affected_rows($conn);
