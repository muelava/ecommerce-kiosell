<?php


session_start();

include "koneksi.php";


if (!isset($_SESSION["login"])) {
    header("Location: ../login");
    return false;
} elseif ($_SESSION["status"] != "admin") {
    header("Location: index");
    return false;
}



$id_user = $_GET["id_user"];

$search = "DELETE FROM user where id_user = '$id_user'";
$cari = mysqli_query($conn, $search);
header('Location:daftar-user');
return mysqli_affected_rows($conn);
