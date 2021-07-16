<?php

session_start();

include "koneksi.php";


if (!isset($_SESSION["login"])) {
    header("Location: ../login");
    return false;
}


$id_ulasan = $_GET["id_ulasan"];

mysqli_query($conn, "DELETE FROM ulasan WHERE id_ulasan = '$id_ulasan' ");

echo "<script>alert('Berhasil dihapus!'); window.location.href='ulasan';</script>";
return mysqli_affected_rows($conn);
