<?php


session_start();

include "koneksi.php";

if (!isset($_SESSION["login"])) {
    header("Location: ../login");
} elseif ($_SESSION["status"] != "user") {
    header("Location:index");
}


$username = $_SESSION["login"];


if ($_SESSION["status"] === "admin") {
    $admin = mysqli_query($conn, "SELECT *FROM admin WHERE username = '$username'");
    $result  = mysqli_fetch_assoc($admin);
} else {
    $user = mysqli_query($conn, "SELECT *FROM user WHERE username = '$username'");
    $result  = mysqli_fetch_assoc($user);
}


$id_user = $result["id_user"];

$transaksi = mysqli_query($conn, "SELECT *FROM transaksi WHERE id_user = '$id_user' ORDER BY id_transaksi DESC");
$result_transaksi = mysqli_fetch_assoc($transaksi);



?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembelian</title>

    <script src="assets/js/main.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>

    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="col-auto col-md-3 col-xl-3 px-sm-2 px-0 sidebar">
                <div class="d-flex flex-column align-items-center align-items-sm-start px-3 pt-2 text-white min-vh-100">
                    <a href="index" class="d-flex align-items-center pb-3 mb-md-0 me-md-auto text-white text-decoration-none">
                        <span class="fs-5 text-dark fw-bold" id="title">Kiosell</span>
                    </a>
                    <ul class="nav nav-pills flex-column mb-sm-auto mb-0 align-items-left align-items-sm-start" id="menu">
                        <li class="nav-item">
                            <div class="dropdown pb-4">
                                <a href="#" class="d-flex align-items-center text-decoration-none" id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                                    <span style='background: rgb(0,0,0,.5); color:white' class='inisial rounded-circle me-2'>
                                        <span class='text-uppercase position-absolute fw-bold coba'><?= substr($_SESSION["login"], 0, 1);; ?></span>
                                    </span>
                                    <div class="row">
                                        <span class="ms-1 fw-bold text-capitalize" id="nama"><?= $_SESSION["login"]; ?> <?php if ($_SESSION["status"] == "admin") : ?> <img src="assets/img/check-verifed.png" alt="" width="16"><?php endif; ?> </span>
                                        <span class="ms-1" id="sebagai"><?= $result["status"]; ?></span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-light text-small border-0 shadow-sm">
                                    <?php if ($_SESSION["status"] == "user") : ?>
                                        <li class="py-1">
                                            <a class="dropdown-item" href="akun?id_user=<?= $result["id_user"] ?>"><i class="fa fa-user-circle"></i> Akun</a>
                                        </li>
                                        <li class="py-1">
                                            <a class="dropdown-item" href="edit-akun?id_user=<?= $result["id_user"] ?>"><i class="fa fa-edit"></i> Edit Akun</a>
                                        </li>
                                    <?php endif; ?>
                                    <li class="py-1">
                                        <!-- <hr class="dropdown-divider"> -->
                                    </li>
                                    <li><a class="dropdown-item text-danger" href="logout"> <i class="fa fa-sign-out"></i> Keluar</a></li>
                                </ul>
                            </div>
                        </li>
                        <?php if ($_SESSION["status"] == "admin") : ?>
                            <li>
                                <a href="post-baru" class="nav-link px-0 align-middle" style="opacity: .5;">
                                    <i class="fs-4 bi-table"></i> <span class="ms-1" id="navigasi"><img src="assets/img/outline_post_add_black_24dp.png" class="me-2" width="35" alt=""> Post Baru</span>
                                </a>
                            </li>
                            <li>
                                <a href="postingan" class="nav-link px-0 align-middle" style="opacity: .5;">
                                    <i class="fs-4 bi-table"></i> <span class="ms-1" id="navigasi"><img src="assets/img/outline_fact_check_black_24dp.png" class="me-2" width="35" alt=""> Postingan</span>
                                </a>
                            </li>
                            <li>
                                <a href="daftar-user" class="nav-link px-0 align-middle" style="opacity: .5;">
                                    <i class="fs-4 bi-table"></i> <span class="ms-1" id="navigasi"><img src="assets/img/outline_people_black_24dp.png" class="me-2" width="35" alt=""> Daftar User</span>
                                </a>
                            </li>
                            <li>
                                <a href="pemberitahuan" class="nav-link px-0 align-middle" style="opacity: .5;">
                                    <i class="fs-4 bi-table"></i> <span class="ms-1" id="navigasi"><img src="assets/img/outline_notifications_active_black_24dp.png" class="me-2" width="35" alt=""> Pemberitahuan</span></a>
                            </li>
                        <?php else : ?>
                            <li>
                                <a href="pembelian" class="nav-link px-0 align-middle">
                                    <i class="fs-4 bi-table"></i> <span class="ms-1" id="navigasi"><img src="assets/img/outline_shopping_cart_black_24dp.png" class="me-2" width="35" alt=""> Pembelian</span></a>
                            </li>
                        <?php endif; ?>
                    </ul>
                    <hr>
                </div>
            </div>
            <span class="text-dark position-relative" style="width: 0!important; top:2em; cursor:pointer" id="btn-burger" onclick="btnBurger()">
                <div class="humberger"></div>
                <div class="humberger"></div>
                <div class="humberger"></div>
            </span>

            <div class="col py-3 mt-5" id="content">
                <h2 class="pt-5 text-capitalize">Daftar <b>Pembelian</b></h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Produk</th>
                            <th scope="col">Total Harga</th>
                            <th scope="col" class="text-center">Status</th>
                            <th scope="col">Detail</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1; ?>
                        <?php foreach ($transaksi as $result) : ?>
                            <tr>
                                <th scope="row"><?= $i++; ?></th>
                                <td><?= $result["nama_barang"]; ?></td>
                                <td class="fw-bold">Rp <?= $result["jml_tagihan"]; ?></td>
                                <td class="text-warning text-center">
                                    <?php
                                    if ($result["status"] == "true") {
                                        echo "<i style='transform: rotateY(180deg);' class='fa fa-truck text-success fa-2x'>_</i>" . "<br> <span class='text-success'>Dalam Perjalanan</span>";
                                    } elseif ($result["status"] == "false") {
                                        echo "<i style='transform: rotateY(180deg);' class='fa fa-clock-o fa-2x'></i>" . "<br> <span>Menunggu Pembayaran</span>";
                                    } else {
                                        echo "<i class='fa fa-check text-secondary fa-2x'></i>" . "<br> <span class='text-secondary'>Selesai</span>";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <a class="btn btn-sm btn-danger" onclick="return confirm('Batalkan pembelian <?= $result['nama_barang'] ?>? transaksi akan dihapus.')" href="hapus-transaksi?id_transaksi=<?= $result['id_transaksi'] ?>">Batal</a>
                                    <a class="btn btn-sm btn-primary" href="../transaksi?id_transaksi=<?= $result['id_transaksi'] ?>">Rincian</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if (mysqli_num_rows($transaksi) == 0) : ?>
                    <h5 class="text-center" style="margin-top: 10%;"><strong>Ooops!!</strong> Belum ada Transaksi <br><br>
                        <a href="../index" class="btn text-white text-center" style="background-color:#ff4500">Belanja Sekarang</a>
                    </h5>
                    <div class="d-flex justify-content-center">
                        <img src="../assets/img/empty.jpg" class="img-fluid col-md-5" alt="">
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <script>
            $(document).ready(function() {
                $("#btn-burger").click(function() {
                    $(".sidebar").toggle(250);
                });
            });
        </script>
        <!-- bootstrap js -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</body>

</html>