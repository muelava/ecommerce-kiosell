<?php

session_start();


$conn = mysqli_connect("localhost", "root", "", "kiosell");

$barang = mysqli_query($conn, "SELECT *FROM barang ORDER BY id_barang DESC");

$kategori_pakaian = mysqli_query($conn, "SELECT *FROM barang where kategori='pakaian' ORDER BY id_barang DESC");
$kategori_elektro = mysqli_query($conn, "SELECT *FROM barang where kategori='elektronik' ORDER BY id_barang DESC");
$kategori_otomotif = mysqli_query($conn, "SELECT *FROM barang where kategori='otomotif' ORDER BY id_barang DESC");

$result_barang = mysqli_fetch_assoc($barang);


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

    <title>Kiosell - Tempatnya Belanja Onine</title>
</head>

<body>

    <button class="btn shadow-sm" id="btnTop">
        <i class="fa fa-chevron-up"></i>
    </button>

    <nav class="navbar navbar-expand-lg navbar-light bg-light shadow-sm">
        <div class="container-fluid mx-5">
            <a class="navbar-brand fw-bold" href="#">Kiosell</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText" aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" aria-current="page" href="#kategori">Kategori</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#terbaru">Terbaru</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#otomotif">Otomotif</a>
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
                                    <?php if ($_SESSION["status"] == "user") : ?>
                                        <li class="py-1">
                                            <a class="dropdown-item" href="akun"><i class="fa fa-user-circle"></i> Akun</a>
                                        </li>
                                        <li class="py-1">
                                            <a class="dropdown-item" href="edit-akun"><i class="fa fa-edit"></i> Edit Akun</a>
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
    <div class="container">

        <div id="carouselExampleIndicators" class="carousel slide my-5" data-bs-ride="carousel">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
            </div>
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="assets/img/banner.png" class="d-block w-100" alt="assets/img/banner.png">
                </div>
                <div class="carousel-item">
                    <img src="assets/img/banner.png" class="d-block w-100" alt="assets/img/banner.png">
                </div>
                <div class="carousel-item">
                    <img src="assets/img/banner.png" class="d-block w-100" alt="assets/img/banner.png">
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

        <section id="section1" class="mt-5">
            <h5 class="fw-bold mb-4 subheading" id="kategori">Kategori Pilihan</h5>
            <div class="row">
                <div class="col">
                    <a class="btn shadow-sm fw-bold py-0 px-4 mx-2 mb-3" href="#pakaian">Pakaian</a>
                    <a class="btn shadow-sm fw-bold py-0 px-4 mx-2 mb-3" href="#elektronik">Elektronik</a>
                    <a class="btn shadow-sm fw-bold py-0 px-4 mx-2 mb-3" href="#otomotif">Otomotif</a>
                </div>
            </div>
        </section>

        <section id="section2" class="mt-5">
            <h5 class="fw-bold mb-4 subheading" id="terbaru">Terbaru</h5>
            <div class="row">
                <?php foreach ($barang as $rst_terbaru) : ?>
                    <a href="produk?id=<?= $rst_terbaru['id_barang'] ?>" class="btn shadow-sm col-sm-2">
                        <div class="img-content">
                            <img class="img-fluid" width="100" src="admin/assets/img/post/<?= $rst_terbaru['gambar1'] ?>" alt="">
                        </div>
                        <h6 class="my-3"><?= $rst_terbaru["nama_barang"]; ?></h6>
                        <div class="text-start">
                            <p class="fw-bold harga mb-2">Rp <?= number_format($rst_terbaru["harga"], 0, ',', '.'); ?></p>
                            <?php
                            $id_admin = $rst_terbaru["id_admin"];
                            $query = mysqli_query($conn, "SELECT *FROM admin where id_admin = '$id_admin'");
                            $result = mysqli_fetch_assoc($query);
                            ?>
                            <p class="lokasi mb-1"><i class="fa fa-user"></i> <?= $result["username"]; ?></p>
                            <p class="penjual mb-1"><i class="fa fa-map-marker"></i> <?= $result["alamat"]; ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="section3" class="mt-5">
            <h5 class="fw-bold mb-4 subheading" id="pakaian">Pakaian</h5>
            <div class="row">
                <?php foreach ($kategori_pakaian as $pakaian) : ?>
                    <a href="produk?id=<?= $pakaian['id_barang'] ?>" class="btn shadow-sm col-sm-2">
                        <div class="img-content">
                            <img class="img-fluid" width="100" src="admin/assets/img/post/<?= $pakaian['gambar1'] ?>" alt="">
                        </div>
                        <h6 class="my-3"><?= $pakaian["nama_barang"]; ?></h6>
                        <div class="text-start">
                            <p class="fw-bold harga mb-2">Rp <?= number_format($pakaian["harga"], 0, ',', '.'); ?></p>
                            <?php
                            $id_admin = $pakaian["id_admin"];
                            $query = mysqli_query($conn, "SELECT *FROM admin where id_admin = '$id_admin'");
                            $result = mysqli_fetch_assoc($query);
                            ?>
                            <p class="lokasi mb-1"><i class="fa fa-user"></i> <?= $result["username"]; ?></p>
                            <p class="penjual mb-1"><i class="fa fa-map-marker"></i> <?= $result["alamat"]; ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="section4" class="mt-5">
            <h5 class="fw-bold mb-4 subheading" id="elektronik">Elektronik</h5>
            <div class="row">
                <?php foreach ($kategori_elektro as $elektronik) : ?>
                    <a href="produk?id=<?= $elektronik['id_barang'] ?>" class="btn shadow-sm col-sm-2">
                        <div class="img-content">
                            <img class="img-fluid" width="100" src="admin/assets/img/post/<?= $elektronik['gambar1'] ?>" alt="">
                        </div>
                        <h6 class="my-3"><?= $elektronik["nama_barang"]; ?></h6>
                        <div class="text-start">
                            <p class="fw-bold harga mb-2">Rp <?= number_format($elektronik["harga"], 0, ',', '.'); ?></p>
                            <?php
                            $id_admin = $elektronik["id_admin"];
                            $query = mysqli_query($conn, "SELECT *FROM admin where id_admin = '$id_admin'");
                            $result = mysqli_fetch_assoc($query);
                            ?>
                            <p class="lokasi mb-1"><i class="fa fa-user"></i> <?= $result["username"]; ?></p>
                            <p class="penjual mb-1"><i class="fa fa-map-marker"></i> <?= $result["alamat"]; ?></p>
                        </div>
                    </a>
                <?php endforeach; ?>
            </div>
        </section>

        <section id="section4" class="mt-5">
            <h5 class="fw-bold mb-4 subheading" id="otomotif">Otomotif</h5>
            <div class="row">
                <?php foreach ($kategori_otomotif as $otomotif) : ?>
                    <a href="produk?id=<?= $otomotif['id_barang'] ?>" class="btn shadow-sm col-sm-2">
                        <div class="img-content">
                            <img class="img-fluid" width="100" src="admin/assets/img/post/<?= $otomotif['gambar1'] ?>" alt="">
                        </div>
                        <h6 class="my-3"><?= $otomotif["nama_barang"]; ?></h6>
                        <div class="text-start">
                            <p class="fw-bold harga mb-2">Rp <?= number_format($otomotif["harga"], 0, ',', '.'); ?></p>
                            <?php
                            $id_admin = $otomotif["id_admin"];
                            $query = mysqli_query($conn, "SELECT *FROM admin where id_admin = '$id_admin'");
                            $result = mysqli_fetch_assoc($query);
                            ?>
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