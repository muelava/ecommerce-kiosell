<?php
$conn = mysqli_connect("localhost", "root", "", "kiosell");

if (!$conn) {
    die("Koneksi database gagal: " . mysqli_connect_error());
}
