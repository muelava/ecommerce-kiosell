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





// ketika tombol pilihan pembayaran ditekan
if (isset($_POST["rincian_pembelian"])) {

    $kode_transaksi = rand(1000, 9999);
    $id_admin = $_POST["id_admin"];
    $alamat_penjual = $_POST["alamat_penjual"];
    $id_user = $_POST['id_user'];
    $rekening = $_POST["pilih_bank"];


    $nama_barang = $_POST["nama_barang"];
    $harga = $_POST["harga"];
    $subtotal = $_POST["subtotal"];
    $jml_tagihan = $_POST["jml_tagihan"];
    $jml_barang = $_POST["jml_barang"];
    $total_berat = $_POST["total_berat"];


    $catatan = strip_tags($_POST["catatan"]);
    $alamat_pembeli = $_POST["alamat"];
    $kode_pos = $_POST["kode-pos"];
    $kota_kab = $_POST["kota-kab"];
    $provinsi = $_POST["provinsi"];

    $kurir = $_POST["kurir"];
    $ongkir = $_POST["ongkir"];

    $wkt_beli = $_POST["wkt_beli"];

    if (empty($jml_tagihan)) {
        echo "<script>alert('Lengkapi Pengiriman Anda!'); window.location.href='pembelian?id=$id_barang'</script>";
        return false;
        die();
    }

    mysqli_query($conn, "INSERT INTO transaksi VALUES('','$kode_transaksi','$id_barang','$id_admin','$id_user','$rekening','$nama_barang','$jml_tagihan','$jml_barang','$harga','$subtotal','$total_berat','$catatan','$alamat_pembeli','$alamat_penjual','$kode_pos','$kota_kab','$provinsi','$kurir','$ongkir','false','$wkt_beli','')");


    $carTransaksi = mysqli_query($conn, "SELECT *FROM transaksi WHERE kode_transaksi = '$kode_transaksi'");
    $resultTransaksi = mysqli_fetch_assoc($carTransaksi);
    $id_transaksi = $resultTransaksi["id_transaksi"];


    echo "<script>alert('Berhasil!'); window.location.href='transaksi?id_transaksi=$id_transaksi'</script>";
    return mysqli_affected_rows($conn);
    die();
}
// /ketika tombol pilihan pembayaran ditekan





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

        #berat::after {
            content: "gram";
            font-size: 12px;
        }
    </style>

    <title>Pembelian</title>
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
                                <p>Dikirim Dari : <i class="fa fa-map-marker"></i> <strong id="alamat_penjual"><?= $kotaAsalPenjual . ", " . $provinsiAsalPenjual; ?></strong></p>
                            </caption>
                            <thead>
                                <tr>
                                    <th>
                                        <img src="admin/assets/img/post/<?= $result['gambar1'] ?>" width="100" alt="">
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
                                        <textarea class="form-control" name="catatan" id="catatan" rows="3" placeholder="Warna, Ukuran, dsb"></textarea>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Berat </td>
                                    <td class="text-end" id="berat"><?= $result["berat"]; ?><sub></sub></td>
                                </tr>
                                <tr>
                                    <td>Quantity </td>
                                    <td class="text-end"><input type="number" min="1" value="1" style="border:none; max-width:15%" id="quantity"></td>
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
                                <p class="mb-3" style="font-size: 14px;" id="alamat"><?= $result_user['alamat']; ?></p>

                                <p class="fw-bold" style="font-size: 12px;">Kode Pos</p>
                                <h6 class="mb-3" id="kode-pos"><?= $result_user['kode_pos']; ?></h6>

                                <p class="fw-bold" style="font-size: 12px;">Kota / Kabupaten</p>
                                <h6 class="mb-3" id="kota-kab"><?= $kotaAsalPembeli; ?></h6>

                                <p class="fw-bold">Provinsi</p>
                                <h6 id="provinsi"><?= $provinsiAsalPembeli; ?></h6>

                            </div>
                            <div class="col-md">
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
                                <button class="btn btn-danger pilih-pembayaran" data-bs-toggle="modal" data-bs-target="#exampleModal" id="mycolor-bg">Pilih Pembayaran</button>
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
                    <h5 class="modal-title" id="exampleModalLabel">Pilih Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">

                    <input type="hidden" name="id_admin" id="id_admin">
                    <input type="hidden" name="alamat_penjual" id="almt_penjual">

                    <input type="hidden" name="nama_barang" id="nama_barang">
                    <input type="hidden" name="harga" id="harga">
                    <input type="hidden" name="subtotal" id="sub_total">
                    <input type="hidden" name="jml_tagihan" id="jumlah_tagihan">
                    <input type="hidden" name="jml_barang" id="jml_barang">
                    <input type="hidden" name="total_berat" id="total_berat">
                    <input type="hidden" name="id_user" id="id_user">
                    <input type="hidden" name="catatan" id="note">
                    <input type="hidden" name="alamat" id="almt">
                    <input type="hidden" name="kode-pos" id="kodepos">
                    <input type="hidden" name="kota-kab" id="kotakab">
                    <input type="hidden" name="provinsi" id="prov">
                    <input type="hidden" name="kurir" id="krr">
                    <input type="hidden" name="ongkir" id="plh_ongkir">
                    <input type="hidden" name="wkt_beli" id="waktu_beli">

                    <div class="modal-body">
                        <div class="d-flex justify-content-around align-items-center mb-3">
                            <div>Transfer Bank</div>
                            <div class="mb-1">
                                <select class="form-select" name="pilih_bank" id="">
                                    <option value="bca">BANK BCA</option>
                                    <option value="bri">BANK BRI</option>
                                    <option value="mandiri">BANK MANDIRI</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" id="mycolor-bg" class="btn btn-primaryr" name="rincian_pembelian">Rincian Pembelian</button>
                    </div>

                </form>
            </div>
        </div>
    </div>
    <!-- end modal -->


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
                        <p><i class="fa fa-whatsapp me-3 fa-2x text-success"></i>0882-1053-4512
                        </p>
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
            <a class="text-reset fw-bold" href="https://github.com/muelava" target="_blank">Kiosel</a>
        </div>
        <!-- Copyright -->
    </footer>
    <!-- Footer -->




    <script>
        $(".tombol-show").click(function() {
            $(this).hide();
            $(".detail-produk").css("maxHeight", "initial");
        })


        // ongkir breakpoint

        $('#kurir, #quantity').on("change keyup", function() {

            // value default jika user tidak mengunput atau menginput 0 atau bahkan
            if ($("#quantity").val() == "" || $("#quantity").val() == "0") {
                var q = 1;
            } else if (parseInt($("#quantity").val()) > parseInt(<?= $result['jml_barang'] ?>)) {
                var max = parseInt(<?= $result["jml_barang"]; ?>)
                var q = parseInt($("#quantity").val(max));
            } else {
                var q = parseInt($("#quantity").val());
            }

            var b = parseInt(<?= $result["berat"]; ?>)
            var berat = q * b;
            $("#berat").html(berat);

            var j = <?= $result["harga"]; ?>;
            var jml = q * j;
            $('#subtotal').html(jml);

            //Mengambil value dari option select provinsi asal, kabupaten, kurir kemudian parameternya dikirim menggunakan ajax
            var kab = <?= $kota_pembeli; ?>;
            var kurir = $('#kurir').val();

            if (kurir == "") {
                $('#tampil_ongkir').text("");
            } else {

                var subtotal = parseInt($('#subtotal').text());
                var asal_distrik = <?= $result_admin['distrik'] ?>;
                var berat = parseInt($("#berat").html());

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



        // jika tombol pilih-pembayaran ditekan
        $(".pilih-pembayaran").on("click", function() {

            $("#almt_penjual").val($("#alamat_penjual").text());

            if ($("#total").text() == "") {
                var timpa = 0;
            } else {
                let str = $("#total").text();
                let ambilNol = str.substring(str.length - 3);
                let acak = Math.floor((Math.random() * 900) + 100);
                var timpa = str.replace(ambilNol, acak);
            }

            $("#jumlah_tagihan").val(timpa);


            $("#harga").val("<?= $result['harga'] ?>")

            $("#sub_total").val($("#subtotal").text());


            $("#id_admin").val("<?= $id_admin ?>")
            $("#id_user").val("<?= $result_user["id_user"]; ?>")


            $("#nama_barang").val("<?= $result["nama_barang"]; ?>")
            $("#jml_barang").val($("#quantity").val())



            $("#total_berat").val($("#berat").text())
            $("#almt").val("<?= $result_user['alamat']; ?>")
            $("#kodepos").val($("#kode-pos").text())
            $("#kotakab").val($("#kota-kab").text())
            $("#prov").val($("#provinsi").text())
            $("#krr").val($('#kurir').val())
            $("#plh_ongkir").val($('#ongkir').text())


            let ambilWaktu = new Date().getTime();
            let dapatWaktu = ambilWaktu + 3000; //+ 86400000 = 24 jam
            $("#waktu_beli").val(dapatWaktu);



            $("#note").val($('#catatan').val())

            // var aaku = "coba";

            // $.ajax({
            //     type: 'POST',
            //     url: 'pembayaran.php',
            //     data: {
            //         'coba': aaku
            //     },
            //     success: function(data) {
            //         $("#tes").html(data);
            //     }
            // });

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