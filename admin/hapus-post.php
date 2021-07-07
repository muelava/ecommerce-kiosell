<?php

$conn = mysqli_connect('localhost', 'root', '', 'kiosell');

$id_barang = $_GET["id_post"];


// hapus file gambar
$gambar = mysqli_query($conn, "SELECT *FROM barang WHERE id_barang = '$id_barang'");
$gbr = mysqli_fetch_assoc($gambar);

$gambar1 = glob('assets/img/post/' . $gbr['gambar1']);
foreach ($gambar1 as $gbr1) {
    if (is_file($gbr1))
        unlink($gbr1);
}
$gambar2 = glob('assets/img/post/' . $gbr['gambar2']);
foreach ($gambar2 as $gbr2) {
    if (is_file($gbr2))
        unlink($gbr2);
}
$gambar3 = glob('assets/img/post/' . $gbr['gambar3']);
foreach ($gambar3 as $gbr3) {
    if (is_file($gbr3))
        unlink($gbr3);
}



// hapus data
$search = "DELETE FROM barang where id_barang = '$id_barang'";
$cari = mysqli_query($conn, $search);



// tendang
header('Location:postingan');
return mysqli_affected_rows($conn);
