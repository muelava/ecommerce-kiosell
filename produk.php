<?php
session_start();

$id_barang = $_GET["id"];

$conn = mysqli_connect("localhost", "root", "", "kiosell");

// cari barang
$barang = mysqli_query($conn, "SELECT *FROM barang where id_barang = '$id_barang' ORDER BY id_barang DESC");
$result = mysqli_fetch_assoc($barang);


// dapatkan kategori barang
$kategori = $result["kategori"];
$barang_ktgr = mysqli_query($conn, "SELECT *FROM barang where kategori = '$kategori' ORDER BY id_barang DESC");


$id_admin = $result["id_admin"];


// cari data penjual
$admin = mysqli_query($conn, "SELECT *FROM admin where id_admin = '$id_admin'");
$result_admin = mysqli_fetch_assoc($admin);



?>

<!doctype html>
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

    <title><?= $result["nama_barang"]; ?></title>
</head>

<body>

    <button class="btn shadow-sm" id="btnTop">
        <i class="fa fa-chevron-up"></i>
    </button>

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
                                    <?php if ($_SESSION["status"] == "user") :
                                        $username = $_SESSION['login'];
                                        $user = mysqli_query($conn, "SELECT *FROM user WHERE username = '$username'");
                                        $result_user = mysqli_fetch_assoc($user);
                                    ?>
                                        <li class="py-1">
                                            <a class="dropdown-item" href="admin/akun?id_user=<?= $result_user["id_user"] ?>"><i class="fa fa-user-circle"></i> Akun</a>

                                        </li>
                                    <?php endif; ?>
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

    <!-- container -->
    <div class="container" id="container-produk">

        <div class="row mt-5">
            <div class="kiri col-md">
                <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel">
                    <div class="carousel-indicators">
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                        <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                    </div>
                    <div class="carousel-inner bg-light d-flex align-items-center" style="height: 500px;">
                        <div class="carousel-item active">
                            <img src="admin/assets/img/post/<?= $result['gambar1'] ?>" class="d-block w-100 img-fluid" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="admin/assets/img/post/<?= $result['gambar2'] ?>" class="d-block w-100 img-fluid" alt="...">
                        </div>
                        <div class="carousel-item">
                            <img src="admin/assets/img/post/<?= $result['gambar3'] ?>" class="d-block w-100 img-fluid" alt="...">
                        </div>
                    </div>
                    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Previous</span>
                    </button>
                    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                        <span class="carousel-control-next-icon" aria-hidden="true"></span>
                        <span class="visually-hidden">Next</span>
                    </button>
                </div>
            </div>
            <div class="kanan col-md">
                <div class="detail">
                    <h3 class="fw-bold"><?= $result["nama_barang"]; ?></h3>
                    <p>0 ulasan</p>
                    <h3 class="fw-bold mb-3">Rp<?= number_format($result["harga"], 0, ',', '.'); ?></h3>
                    <p>Kondisi : <strong><?= $result["kondisi"]; ?></strong></p>
                    <p>Stok : <strong><?= $result["jml_barang"]; ?></strong></p>
                    <p>Berat : <strong><?= number_format($result["berat"], '0', ',', '.'); ?></strong><sub>gram</sub></p>
                    <p>Kategori : <strong><?= $result["kategori"]; ?></strong></p>
                    <p class="mb-5 text-secondary">Di Post. <span><?= $result["wkt_post"]; ?></span></p>
                    <div class="detail-produk" style="max-height: 300px; overflow:hidden; position:relative">
                        <?= $result["deskripsi"]; ?>
                        <p class="tombol-show">Lihat Selengkapnya <i class="fa fa-chevron-down"></i></p>
                    </div>
                </div>
                <hr class="mb-4">
                <div class="info-penjual bg-light row align-items-center">
                    <div class="penjual shadow-sm col-sm p-3">
                        <p><i class="fa fa-user"></i> <?= $result_admin["username"]; ?></p>
                        <small><?= $result_admin["status"]; ?> <img src="admin/assets/img/check-verifed.png" alt="" width="14"></small>
                        <h6 class="mt-3 fw-bold">Pengiriman :</h6>
                        <p>Dikirim Dari <strong><?= $result_admin["alamat"]; ?></strong></p>
                    </div>
                    <div class="beli text-center col-sm p-3">
                        <a href="checkout?id=<?= $result["id_barang"] ?>" class="btn btn-outline-danger tombol-beli">Beli Sekarang</a>
                    </div>
                </div>
            </div>
        </div>

        <section id="section2" class="mt-5">
            <h5 class="fw-bold mb-4 subheading" id="terbaru">Produk Lainnya</h5>
            <div class="row">
                <?php foreach ($barang_ktgr as $rst_terbaru) : ?>
                    <a href="produk?id=<?= $rst_terbaru['id_barang'] ?>" target="_blank" class="btn shadow-sm col-sm-2">
                        <div class="img-content">
                            <img class="img-fluid" width="100" src="admin/assets/img/post/<?= $rst_terbaru['gambar1'] ?>" alt="">
                        </div>
                        <h6 class="my-3"><?= $rst_terbaru["nama_barang"]; ?></h6>
                        <div class="text-start">
                            <p class="fw-bold harga">Rp <?= number_format($rst_terbaru["harga"], 0, ',', '.'); ?></p>
                            <?php
                            $id_admin = $rst_terbaru["id_admin"];
                            $query = mysqli_query($conn, "SELECT *FROM admin where id_admin = '$id_admin'");
                            $result = mysqli_fetch_assoc($query);
                            ?>
                            <p class="stok">Stok : <strong><?= $rst_terbaru["jml_barang"]; ?></strong> </p>
                            <p class="lokasi mb-1"><i class="fa fa-user"></i> <?= $result["username"]; ?></p>
                            <p class="penjual mb-1"><i class="fa fa-map-marker"></i> <?= $result["alamat"]; ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

    </div>


    <!-- Footer -->
    <footer class="text-center text-lg-start bg-light text-muted">
        <hr class="my-5">
        <!-- Section: Links  -->
        <section class="foot">
            <div class="container text-center text-md-start mt-5">
                <!-- Grid row -->
                <div class="row mt-3">
                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-4 col-xl-3 mx-auto mb-4 foot-brand">
                        <!-- Content -->
                        <h6 class="text-capitlize fw-bold mb-4">Kiosell</h6>
                        <p>
                            Nikmati kebebasan dan dapatkan semua kebutuhan anda.
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-3 col-lg-2 col-xl-2 mx-auto mb-4">
                        <!-- Links -->
                        <h6 class="text-capitalize fw-bold mb-4">
                            Support
                        </h6>
                        <p>
                            <a href="#!" class="text-reset">Bantuan</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">About</a>
                        </p>
                        <p>
                            <a href="#!" class="text-reset">Syarat & Ketentuan</a>
                        </p>
                    </div>
                    <!-- Grid column -->

                    <!-- Grid column -->
                    <div class="col-md-4 col-lg-3 col-xl-3 mx-auto mb-md-0 mb-4">
                        <!-- Links -->
                        <h6 class="text-capitalize fw-bold mb-4">
                            Sosial Media
                        </h6>
                        <p><i class="fa fa-facebook me-3 fa-2x text-primary"></i>Hokisell
                        </p>
                        <p>
                            <i class="fa fa-instagram fa-2x me-3 text-danger"></i>Hokisell
                        </p>
                        <p><i class="fa fa-whatsapp me-3 fa-2x text-success"></i>082115100979</p>
                    </div>
                    <!-- Grid column -->
                </div>
                <!-- Grid row -->
            </div>
        </section>
        <!-- Section: Links  -->
        <!-- Copyright -->
        <div class="text-center p-4">
            © 2021 Copyright :
            <a class="text-reset fw-bold" href="https://muelava.github.io" target="_blank">Muelava</a>
        </div>
        <!-- Copyright -->
    </footer>
    <!-- Footer -->




    <script>
        $(".tombol-show").click(function() {
            $(this).hide();
            $(".detail-produk").css("maxHeight", "initial");
        })
    </script>


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