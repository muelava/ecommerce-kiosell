<?php


session_start();

include "koneksi.php";


if (!isset($_SESSION["login"])) {
    header("Location: ../login");
}


$username = $_SESSION["login"];


if ($_SESSION["status"] === "admin") {
    $admin = mysqli_query($conn, "SELECT *FROM admin WHERE username = '$username'");
    $result  = mysqli_fetch_assoc($admin);
} else {
    $user = mysqli_query($conn, "SELECT *FROM user WHERE username = '$username'");
    $result  = mysqli_fetch_assoc($user);
    $id_user = $result["id_user"];

    $caritransaksi = mysqli_query($conn, "SELECT *FROM transaksi WHERE id_user ='$id_user'");
    // $resultTransaksi = mysqli_fetch_assoc($caritransaksi);

    foreach ($caritransaksi as $status) {
        $status["status"];
    }
}




?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin</title>

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
                                            <a class="dropdown-item" href="akun?id_user=<?= $result['id_user'] ?>"><i class="fa fa-user-circle"></i> Akun</a>
                                        </li>
                                        <li class="py-1">
                                            <a class="dropdown-item" href="edit-akun?id_user=<?= $result['id_user'] ?>"><i class="fa fa-edit"></i> Edit Akun</a>
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
                                <a href="penjualan" class="nav-link px-0 align-middle" style="opacity: .5;">
                                    <i class="fs-4 bi-table"></i> <span class="ms-1" id="navigasi"><img src="assets/img/outline_notifications_active_black_24dp.png" class="me-2" width="35" alt=""> Penjualan</span></a>
                            </li>
                            <li>
                                <a href="ulasan" class="nav-link px-0 align-middle" style="opacity: .5;">
                                    <i class="fs-4 bi-table"></i> <span class="ms-1" id="navigasi"><img src="assets/img/outline_thumbs_up_down_black_24dp.png" class="me-2" width="35" alt=""> Ulasan</span></a>
                            </li>
                        <?php else : ?>
                            <li>
                                <a href="pembelian" class="nav-link px-0 align-middle" style="opacity: .5;">
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
                <h2 class="pt-5 text-capitalize">Hello, <b><?= $result["username"]; ?>!</b></h2>
                <br>
                <p>"Segudang uang tidak akan mampu membeli kesempatan kedua." <strong>Enjoyy!!</strong></p>
                <a class="btn btn-outline-success" href="../">Shopping Now!</a>
                <div class="text-center">
                    <img src="../assets/img/shopping.jpg" class="img-fluid" alt="">
                </div>


            </div>
        </div>


        <!-- reminder -->
        <?php if ($_SESSION["status"] != "admin" && $status["status"] == "false") : ?>
            <div id="reminder" class="d-none text-center" style="position: fixed; z-index:99; top:0%; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
                <h3 class="text-light mt-3 text-capitalize"> Hai <strong><?= $_SESSION["login"]; ?></strong>, Ada pembayaran yang menunggu nih. <br> Segera selesaikan.</h3>
                <img src="../assets/img/reminder.jpg" width="60%" class="rounded" style="margin:0;" alt="">
            </div>
        <?php endif; ?>
        <!-- /reminder -->



        <script>
            // reminder 3detik
            setTimeout(() => {
                $("#reminder").removeClass("d-none");

            }, 2000)

            $("#reminder").on("click", () => {
                $("#reminder").addClass("d-none");
            })
            // /reminder




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