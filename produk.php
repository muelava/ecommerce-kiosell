<?php
session_start();

include "admin/koneksi.php";

$id_barang = $_GET["id"];
if (!$id_barang) {

    header("Location:index");
    return false;
}


// cari barang
$barang = mysqli_query($conn, "SELECT *FROM barang where id_barang = '$id_barang' ORDER BY id_barang DESC");
$result = mysqli_fetch_assoc($barang);

// cek apakah tidak menemukan id_barang
if (mysqli_num_rows($barang) === 0) {
    header("Location:index");
    return false;
}

// dapatkan kategori barang
$kategori = $result["kategori"];
$barang_ktgr = mysqli_query($conn, "SELECT *FROM barang where kategori = '$kategori' ORDER BY id_barang DESC");


$id_admin = $result["id_admin"];


// cari data penjual dulu
$admin = mysqli_query($conn, "SELECT *FROM admin where id_admin = '$id_admin'");
$result_admin = mysqli_fetch_assoc($admin);

$kota_asal = $result_admin["distrik"];

// data api rajongkir
// cari kota tujuan
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "http://api.rajaongkir.com/starter/cost",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "origin=" . $kota_asal . "&destination=" . $kota_asal . "&weight=500" . "&courier=jne",
    CURLOPT_HTTPHEADER => array(
        "content-type: application/x-www-form-urlencoded",
        "key: 81d4424e2b099f8b8ea33708087f4b8c"
    ),
));
$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
$data = json_decode($response, true);
$kotaAsalPenjual = $data['rajaongkir']['origin_details']['city_name'];
$provinsiAsalPenjual = $data['rajaongkir']['origin_details']['province'];





// jumlah ulasan id barang
$jmlUlasan = mysqli_query($conn, "SELECT COUNT(*) AS jumlah FROM ulasan WHERE id_barang = '$id_barang'");
$resultJmlUlasan = mysqli_fetch_assoc($jmlUlasan);

// cari data ulasan dan namau user dengan join
$ulasan = mysqli_query($conn, "SELECT nama_user, komentar, wkt_ulasan, rating FROM user join ulasan using(id_user) WHERE id_barang = '$id_barang' ORDER BY id_ulasan DESC");



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


    <style>
        #inisial {
            display: -webkit-box;
            display: -ms-flexbox;
            display: flex;
            -webkit-box-align: center;
            -ms-flex-align: center;
            align-items: center;
            -webkit-box-pack: center;
            -ms-flex-pack: center;
            justify-content: center;
            padding: 20px;
            line-height: initial;
        }
    </style>
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

        <div class="row my-5">
            <div class="kiri col-md">
                <div id="carouselExampleIndicators" class="mb-5 carousel slide" data-bs-ride="carousel">
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
                    <p><i class="fa fa-star text-warning"></i> (<?= $resultJmlUlasan["jumlah"]; ?>) Ulasan</p>
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
                        <h6 class="mt-3 fw-bold">Penjual :</h6>
                        <p><i class="fa fa-user"></i> <?= $result_admin["username"]; ?></p>
                        <small><?= $result_admin["status"]; ?> <img src="admin/assets/img/check-verifed.png" alt="" width="14"></small>
                        <p class="my-1">Dikirim dari <strong><?= $kotaAsalPenjual . ", " . $provinsiAsalPenjual; ?></strong></p>
                    </div>
                    <div class="beli text-center col-sm p-3">
                        <a href="pembelian?id=<?= $result["id_barang"] ?>" class="btn btn-outline-danger tombol-beli">Beli Sekarang</a>
                    </div>
                </div>
            </div>
        </div>
        <h6 class="mb-5 fw-bold">Semua Ulasan Pembeli (<?= $resultJmlUlasan["jumlah"]; ?>)</h6>

        <div class="row" style="max-height: 100vh; overflow-y:scroll; position:relative">
            <?php foreach ($ulasan as $resultUlasan) : ?>

                <div class="col-md-3">
                    <p id="mycolor-text" class="fw-bold mb-3 text-capitalize row justify-content-center">
                        <span class="inisial rounded-circle col-3">
                            <span class="text-white position-absolute fw-bold coba">
                                <?php
                                $nama = substr($resultUlasan["nama_user"], 0, 1);
                                if ($nama == "A" || $nama == "D" || $nama == "H" || $nama == "L" || $nama == "P" || $nama == "T" || $nama == "X") {
                                    echo "
                                                <span style='background: rgb(255,0,0,.5); color:red' class='rounded-circle me-2' id='inisial'>
                                                <span class='text-uppercase position-absolute fw-bold '>$nama</span>
                                                </span>
                                                ";
                                } else if ($nama == "B" || $nama == "E" || $nama == "I" || $nama == "M" || $nama == "Q" || $nama == "U" || $nama == "Y") {
                                    echo "
                                                <span style='background: rgb(0,255,68,0.5);color:#007D21' class='rounded-circle me-2' id='inisial'>
                                                <span class='text-uppercase position-absolute fw-bold'>$nama</span>
                                                </span>
                                                ";
                                } else if ($nama == "C" || $nama == "F" || $nama == "J" || $nama == "N" || $nama == "R" || $nama == "V" || $nama == "Z") {
                                    echo "
                                                <span style='background: rgb(255,180,4,.5); color:#D29B19' class='rounded-circle me-2' id='inisial'>
                                                <span class='text-uppercase position-absolute fw-bold'>$nama</span>
                                                </span>
                                                ";
                                } else {
                                    echo "
                                                <span style='background: rgb(8,0,255,.5); color:#0700DC' class='rounded-circle me-2' id='inisial'>
                                                <span class='text-uppercase position-absolute fw-bold'>$nama</span>
                                                </span>
                                                ";
                                }

                                ?>
                            </span>
                        </span>
                        <span class="col">
                            <?= $resultUlasan["nama_user"]; ?><br>
                            <small style="font-size: .7em;" class="text-secondary fw-normal"><?= $resultUlasan["wkt_ulasan"]; ?></small>
                        </span>
                    </p>
                </div>
                <div class="col-md-7 mb-3">
                    <p>
                        <?php
                        if ($resultUlasan["rating"] == 1) {
                            for ($i = 1; $i <= $resultUlasan["rating"]; $i++) {
                                echo "<i class= 'fa fa-star text-warning'></i> ";
                            }
                        } elseif ($resultUlasan["rating"] == 2) {
                            for ($i = 1; $i <= $resultUlasan["rating"]; $i++) {
                                echo "<i class= 'fa fa-star text-warning'></i> ";
                            }
                        } elseif ($resultUlasan["rating"] == 3) {
                            for ($i = 1; $i <= $resultUlasan["rating"]; $i++) {
                                echo "<i class= 'fa fa-star text-warning'></i> ";
                            }
                        } elseif ($resultUlasan["rating"] == 4) {
                            for ($i = 1; $i <= $resultUlasan["rating"]; $i++) {
                                echo "<i class= 'fa fa-star text-warning'></i> ";
                            }
                        } elseif ($resultUlasan["rating"] == 5) {
                            for ($i = 1; $i <= $resultUlasan["rating"]; $i++) {
                                echo "<i class= 'fa fa-star text-warning'></i> ";
                            }
                        }
                        ?>
                    </p>
                    <p><?= $resultUlasan["komentar"]; ?></p>
                    <hr>
                </div>
            <?php endforeach; ?>
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
                            <p class="lokasi mb-1"><i class="fa fa-bookmark"></i> <?= $rst_terbaru["kategori"]; ?></p>
                            <p class="fw-bold harga">Rp <?= number_format($rst_terbaru["harga"], 0, ',', '.'); ?></p>
                            <?php
                            $id_admin = $rst_terbaru["id_admin"];
                            $query = mysqli_query($conn, "SELECT *FROM admin where id_admin = '$id_admin'");
                            $result = mysqli_fetch_assoc($query);
                            ?>
                            <p class="stok">Stok : <strong><?= $rst_terbaru["jml_barang"]; ?></strong> </p>
                            <p class="lokasi mb-1"><i class="fa fa-user"></i> <?= $result["username"]; ?></p>
                            <p class="penjual mb-1"><i class="fa fa-map-marker"></i> <?= $kotaAsalPenjual . ", " . $provinsiAsalPenjual; ?></p>

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
                        <p><i class="fa fa-facebook me-3 fa-2x text-primary"></i>Kiosell
                        </p>
                        <p>
                            <i class="fa fa-instagram fa-2x me-3 text-danger"></i>Kiosell
                        </p>
                        <p><i class="fa fa-whatsapp me-3 fa-2x text-success"></i>0882-1053-4512</p>
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
            <a class="text-reset fw-bold" href="https://github.com/muelava" target="_blank">Kiosell</a>
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