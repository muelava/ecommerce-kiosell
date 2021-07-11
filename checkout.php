<?php

session_start();

include "admin/koneksi.php";

if (!isset($_SESSION["login"])) {
    echo "<script>alert('Silakan masuk ke dalam akun Kamu.'); document.location.href = 'login'</script>";
    return false;
}


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


// cari data penjual
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



// cari data user(pembeli) / data rajaongkir
if ($_SESSION["status"] == "user") {
    $username = $_SESSION["login"];
    $user = mysqli_query($conn, "SELECT  *FROM user where username = '$username'");
    $result_user = mysqli_fetch_assoc($user);

    $kota_pembeli = $result_user['distrik'];


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
        CURLOPT_POSTFIELDS => "origin=" . $kota_pembeli . "&destination=" . $kota_pembeli . "&weight=500" . "&courier=jne",
        CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded",
            "key: 81d4424e2b099f8b8ea33708087f4b8c"
        ),
    ));
    $response = curl_exec($curl);
    $err = curl_error($curl);
    curl_close($curl);
    $data = json_decode($response, true);
    $kotaAsalPembeli = $data['rajaongkir']['origin_details']['city_name'];
    $provinsiAsalPembeli = $data['rajaongkir']['origin_details']['province'];
}




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

    <style>
        #subtotal::before {
            content: "Rp. ";
        }
    </style>

    <title>Checkout</title>
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
                                    <?php if ($_SESSION["status"] == "user") : ?>
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

        <div class="row justify-content-center mt-3">

            <div class="kanan col-md-8">
                <div class="info-penjual bg-light row align-items-center">
                    <div class="penjual shadow-sm row col-sm p-3">
                        <table class="table caption-top">
                            <caption>
                                <p><i class="fa fa-user"></i> <?= $result_admin["username"]; ?> <img src="admin/assets/img/check-verifed.png" alt="" width="14"></p>
                                <p>Dikirim Dari : <i class="fa fa-map-marker"></i> <strong><?= $kotaAsalPenjual . ", " . $provinsiAsalPenjual; ?></strong></p>
                            </caption>
                            <thead>
                                <tr>
                                    <th>
                                        <img src="admin/assets/img/post/<?= $result['gambar1'] ?>" width="50" alt="">
                                    </th>
                                    <th>
                                        <h5 class="fw-bold">
                                            <?= $result["nama_barang"]; ?><br>
                                        </h5>
                                        <p id="mycolor-text">Rp. <?= $result["harga"]; ?></p>
                                        <small>Stok : <?= $result["jml_barang"]; ?></small>
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Catatan </td>
                                    <td class="text-end">
                                        <textarea class="form-control" name="catatan" id="catatan" rows="3"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Berat </td>
                                    <td class="text-end"><?= number_format($result["berat"], '0', ',', '.'); ?> <sub>gram</sub></td>
                                </tr>
                                <tr>
                                    <td>Quantity </td>
                                    <td class="text-end"><input type="number" min="1" max="<?= $result["jml_barang"]; ?>" value="1" style="border:none; max-width:15%" id="quantity"></td>
                                </tr>
                                <tr>
                                    <td>Subtotal </td>
                                    <td class="text-end" id="subtotal"><?= $result["harga"]; ?></td>
                                </tr>
                            </tbody>
                        </table>

                        <?php if ($_SESSION["status"] == "user") : ?>
                            <div class="col" style="font-size: 14px;">

                                <h5 class="mt-3 mb-2 fw-bold" id="mycolor-text">Pengiriman :</h5>

                                <p class="fw-bold" style="font-size: 12px;">Alamat</p>
                                <p class="mb-3" style="font-size: 14px;"><?= $result_user['alamat']; ?></p>

                                <p class="fw-bold" style="font-size: 12px;">Kode Pos</p>
                                <h6 class="mb-3"><?= $result_user['kode_pos']; ?></h6>

                                <p class="fw-bold" style="font-size: 12px;">Kota / Kabupaten</p>
                                <h6 class="mb-3"><?= $kotaAsalPembeli; ?></h6>

                                <p class="fw-bold">Provinsi</p>
                                <h6><?= $provinsiAsalPembeli; ?></h6>

                            </div>
                            <div class="col">
                                <label>Kurir</label><br>
                                <select class="form-control form-control-sm" id="kurir" name="kurir">
                                    <option value="">Pilih Kurir</option>
                                    <option value="jne">JNE</option>
                                    <option value="tiki">TIKI</option>
                                    <option value="pos">POS INDONESIA</option>
                                </select>
                                <div id="tampil_ongkir"></div>
                            </div>

                            <div class="text-end">
                                <button class="btn btn-danger tombol-beli" id="mycolor-bg" data-bs-toggle="modal" data-bs-target="#exampleModal">Lanjutkan</button>
                            </div>
                        <?php else : echo "<script> alert('Kamu masuk sebagai admin');</script> " ?>
                        <?php endif; ?>

                    </div>

                </div>
            </div>

        </div>

    </div>



    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Apakah sudah yakin ?
                    <div id="tes"></div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" id="mycolor-bg" class="btn btn-primary Bayar">Bayar</button>
                </div>
            </div>
        </div>
    </div>
    <!-- end modal -->


    <script>
        $(".tombol-beli").on("click", function() {

            // if ($(this).find('option:selected').length == 0) {
            //     //some code 
            // }

            var aaku = "coba";

            $.ajax({
                type: 'POST',
                url: 'pembayaran.php',
                data: {
                    'coba': aaku
                },
                success: function(data) {
                    $("#tes").html(data);
                }
            });
        })
    </script>

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

        $('#quantity').on('click keyup', function() {
            var q = $(this).val();
            var j = <?= $result["harga"]; ?>;
            var jml = q * j;
            $('#subtotal').html(jml);
        });


        // ongkir breakpoint

        $('#kurir').on("change", function() {

            //Mengambil value dari option select provinsi asal, kabupaten, kurir kemudian parameternya dikirim menggunakan ajax
            var kab = <?= $kota_pembeli; ?>;
            var kurir = $('#kurir').val();

            if (kurir == "") {
                $('#tampil_ongkir').text("");
            } else {

                var subtotal = parseInt($('#subtotal').text());
                var asal_distrik = <?= $result_admin['distrik'] ?>;
                var berat = <?= $result['berat'] ?>;

                $.ajax({
                    type: 'POST',
                    url: 'fitur-ongkir/tabel-ongkir.php',
                    data: {
                        'kab_id': kab,
                        'kurir': kurir,
                        'asal_distrik': asal_distrik,
                        'harga_barang': subtotal,
                        'berat': berat
                    },
                    success: function(data) {
                        //jika data berhasil didapatkan, tampilkan ke dalam element div tampil_ongkir
                        $("#tampil_ongkir").html(data);
                    }
                });
            }
        });
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