<?php

session_start();

include "admin/koneksi.php";

if (!isset($_SESSION["login"])) {
    header("Location:login");
    return false;
}

$id_transaksi = $_GET["id_transaksi"];


if (!$id_transaksi) {
    header("Location:index");
    return false;
}


$transaksi = mysqli_query($conn, "SELECT *FROM transaksi where id_transaksi = '$id_transaksi'");
$result_transaksi = mysqli_fetch_assoc($transaksi);

if ($result_transaksi["status"] == "false") {
    $result_transaksi["status"] = "Belum dibayar";
}


if (mysqli_num_rows($transaksi) === 0) {
    header("Location:login");
    return false;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="assets/css/main.css">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <title>Transaksi</title>
</head>

<body>

    <!-- navabar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container-fluid mx-5">
            <a class="navbar-brand fw-bold" href="/kiosell">Kiosell</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <!-- <a class="nav-link active" aria-current="page" href="#kategori">Home</a> -->
                    </li>
                </ul>
                <ul class="navbar-nav mb-2 mb-lg-0">
                    <?php if (!isset($_SESSION["login"])) : ?>
                        <li class="nav-item pe-3">
                            <a class="nav-link" href="login">Login</a>
                        </li>
                        <li class="nav-item ">
                            <a class="nav-link text-white fw-bold btn daftar" href="register">Daftar</a>
                        </li>
                    <?php else : ?>

                        <li class="nav-item">
                            <div class="dropdown">
                                <a href="#" class="d-flex align-items-center text-decoration-none" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span style='background: rgb(0,0,0,.5); color:white' class='inisial rounded-circle me-2'>
                                        <span class='text-uppercase position-absolute fw-bold coba'><?= substr($_SESSION["login"], 0, 1);; ?></span>
                                    </span>
                                    <div class="row">
                                        <span class="ms-1 text-capitalize fw-bold" id="nama"><?= $_SESSION["login"]; ?> <?php if ($_SESSION["status"] == "admin") {
                                                                                                                            echo '<img src="admin/assets/img/check-verifed.png" alt="" width="16">';
                                                                                                                        } ?> <i class="fa fa-chevron-down"></i></span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-light text-small border-0 shadow-sm">
                                    <li class="py-1">
                                        <a class="dropdown-item" href="admin"><i class="fa fa-columns"></i> Dashboard</a>
                                    </li>
                                    <li class="py-1">
                                        <!-- <hr class="dropdown-divider"> -->
                                    </li>
                                    <li><a class="dropdown-item text-danger" href="admin/logout"> <i class="fa fa-sign-out"></i> Keluar</a></li>
                                </ul>
                            </div>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <!-- /navabar -->

    <!-- container -->
    <div class="container" id="container-produk">

        <h5 class="mt-5 fw-bold">Rincian Pembelian</h5>
        <table class="table">
            <thead>
                <tr>
                    <th scope="col"></th>
                    <th scope="col"></th>
                    <th scope="col"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Id Transaksi</td>
                    <th scope="row"><?= $result_transaksi["id_transaksi"]; ?></th>
                </tr>
                <tr>
                    <td>Id Admin</td>
                    <th scope="row"><?= $result_transaksi["id_admin"]; ?></th>
                </tr>
                <tr>
                    <td>Id User</td>
                    <th scope="row"><?= $result_transaksi["id_user"]; ?></th>
                </tr>
                <tr>
                    <td>Rekening</td>
                    <th scope="row"><?= $result_transaksi["rekening"]; ?></th>
                </tr>
                <tr>
                    <td>Nama Barang</td>
                    <th scope="row"><?= $result_transaksi["nama_barang"]; ?></th>
                </tr>
                <tr>
                    <td>Harga</td>
                    <th scope="row">Rp. <?= number_format($result_transaksi["harga"], "0", ",", "."); ?></th>
                </tr>
                <tr>
                    <td>Jumlah Barang</td>
                    <th scope="row"><?= $result_transaksi["jml_barang"]; ?></th>
                </tr>
                <tr>
                    <td>Jumlah Tagihan</td>
                    <th scope="row"><?= $result_transaksi["jml_tagihan"]; ?></th>
                </tr>
                <tr>
                    <td>Status</td>
                    <th scope="row"><?= $result_transaksi["status"]; ?></th>
                </tr>
            </tbody>
        </table>

    </div>



    <!-- Optional JavaScript; choose one of the two! -->
    <script src="assets/js/main.js"></script>
    <script src="assets/js/jquery.easing.1.3.js"></script>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

    <!-- Option 2: Separate Popper and Bootstrap JS -->
    <!--
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
    -->
</body>

</html>