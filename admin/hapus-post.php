<?php

$conn = mysqli_connect('localhost', 'root', '', 'kiosell');

$id = $_GET["id_post"];

$search = "DELETE FROM barang where id_barang = '$id'";
$cari = mysqli_query($conn, $search);
header('Location:postingan');
return mysqli_affected_rows($conn);
