<?php

$conn = mysqli_connect('localhost', 'root', '', 'kiosell');

$id = $_GET["id"];

$search = "DELETE FROM ucapan where id = '$id'";
$cari = mysqli_query($conn, $search);
header('Location:ucapan');
return mysqli_affected_rows($conn);
