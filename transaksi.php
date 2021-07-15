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

// cari barang : 
$id_barang = $result_transaksi["id_barang"];
$barang = mysqli_query($conn, "SELECT *FROM barang where id_barang = '$id_barang'");
$result_barang = mysqli_fetch_assoc($barang);
if ($result_barang["jml_barang"] == 0) {
    $result_barang["jml_barang"] = "Habis";
}


// cari penjual : 
$id_admin = $result_transaksi["id_admin"];
$penjual = mysqli_query($conn, "SELECT *FROM admin where id_admin = '$id_admin'");
$result_penjual = mysqli_fetch_assoc($penjual);


// cari pembeli :
$id_user = $result_transaksi["id_user"];
$user = mysqli_query($conn, "SELECT *FROM user where id_user = '$id_user'");
$result_user = mysqli_fetch_assoc($user);


// jika bukan user pembeli 
if ($_SESSION["status"] == "user") {
    $username = $_SESSION["login"];
    $pembeli = mysqli_query($conn, "SELECT *FROM user where username = '$username'");
    $result_pembeli = mysqli_fetch_assoc($pembeli);

    if (($result_pembeli["id_user"] != $result_transaksi["id_user"])) {
        header("Location:admin/pesanan");
    }
}



if (mysqli_num_rows($transaksi) === 0) {
    header("Location:login");
    return false;
}


if ($result_transaksi["status"] == "false") {
    $result_transaksi["status"] = "Menunggu Pembayaran";
}


if ($result_transaksi["rekening"] == "bri") {
    $norek = "BRI-08132456789";
    $atasNama = "PT. Kiosell ";
} elseif ($result_transaksi["rekening"] == "bca") {
    $norek = "BCA-1234567";
    $atasNama = "PT. Kiosell ";
} elseif ($result_transaksi["rekening"] == "mandiri") {
    $norek = "MANDIRI-32100012";
    $atasNama = "PT. Kiosell ";
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

    <!-- fontawesome -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free@5.15.3/css/fontawesome.min.css" integrity="sha384-wESLQ85D6gbsF459vf1CiZ2+rr+CsxRY0RpiF1tLlQpDnAgg6rwdsUF1+Ics2bni" crossorigin="anonymous">

    <!-- jquery -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <title>Rincian Pembelian</title>

    <style>
        #detik,
        #jam,
        #menit,
        #hari {
            position: relative;
        }

        #detik::after {
            content: "(Detik)";
            font-size: .5em;
            position: absolute;
            left: 0%;
            bottom: -50%;
            font-weight: normal;
        }

        #menit::after {
            content: "(Menit)";
            font-size: .5em;
            position: absolute;
            left: 0%;
            bottom: -50%;
            font-weight: normal;
        }

        #jam::after {
            content: "(Jam)";
            font-size: .5em;
            position: absolute;
            left: 0%;
            bottom: -50%;
            font-weight: normal;
        }

        #hari::after {
            content: "(Hari)";
            font-size: .5em;
            position: absolute;
            left: 0%;
            bottom: -50%;
            font-weight: normal;
        }
    </style>
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
    <div class="container bg-white px-5 py-3" id="container-produk">

        <div class="row justify-content-between">
            <div class="col-md-8 mb-5 d-flex align-items-center">
                <div class="me-3">
                    <img src="admin/assets/img/post/<?= $result_barang["gambar1"]; ?>" width="100" alt="">
                </div>
                <h4 class="fw-bold d-inline"><?= $result_transaksi["nama_barang"]; ?>
                    <br> <span style="font-size:.6em;" id="mycolor-text">Rp<?= number_format($result_barang["harga"], '0', ',', '.'); ?></span>
                    <br> <small style="font-size:.5em;">Stok : <?= $result_barang["jml_barang"]; ?></small>
                </h4>
            </div>
            <div class="col-md-2">
                <h2 class="fw-bold text-secondary text-uppercase">kiosell</h2>
                <p>K-<?= $result_transaksi["kode_transaksi"]; ?></p>
                <p>Date : <span class="dateInvoice"></span></p>
            </div>
        </div>
        <div class="text-center mt-3" id="tagihan">
            <p class="fw-bold text-secondary">Batas Waktu Pembayaran :</p>
            <div class="mb-3 text-center"><i class="fa fa-clock-o"></i> <span id="waktu">jam</span> &nbsp; &nbsp; &nbsp; <i class="fa fa-calendar-o"></i> <span id="tanggal-berakhir">tanggal berakhir</span></div>
            <div class="mb-5 w-50 mx-auto d-flex justify-content-evenly" id="mycolor-text">
                <h5 class="fw-bold" id="hari"></h5>
                <h5 class="fw-bold" id="jam"></h5>
                <h5 class="fw-bold" id="menit"></h5>
                <h5 class="fw-bold" id="detik"></h5>
            </div>
            <p class="text-secondary">Jumlah Tagihan</p>
            <h2 class="fw-bold shadow-sm d-inline-block p-1 rounded-circle" id="mycolor-text">Rp <?= $result_transaksi["jml_tagihan"]; ?></h2>
            <p class="mt-4 text-secondary">Metode : Transfer <strong class="text-uppercase">BANK</strong></p>
            <p class="mb-1"><strong class="text-uppercase"><?= $atasNama; ?></strong></p>
            <h5 class="fw-bold shadow-sm d-inline-block p-1 mb-4"><?= $norek ?></h5>
        </div>
        <div class="mb-3 text-center">
            <div class="d-none mb-4" id="waktu-habis">
                <p class="mb-0"></p>
                <img src="assets/img/shock.jpg" width="20%" alt=""><br>
            </div>
            <button id="status" class="btn fw-bold btn-outline-warning text-capitalize" style="pointer-events:none"><?= $result_transaksi["status"]; ?></button>
        </div>
        <?php if ($result_transaksi["status"] == "true") : ?>
            <div class="text-center my-5">
                <p id="copy">Nomor Resi :</p>
                <h5 id="no_resi" class="fw-bold text-secondary"><?= $result_transaksi["no_resi"]; ?></h5>
            </div>
        <?php endif; ?>

        <h5 class="mt-5 fw-bold text-center">Rincian Pembelian</h5>

        <table class="table">
            <tbody>
                <tr>
                    <td>Kode Transaksi</td>
                    <th scope="row">K-<?= $result_transaksi["kode_transaksi"]; ?></th>
                </tr>
                <tr>
                    <td>Tanggal Order</td>
                    <th scope="row" class="dateInvoice"></th>
                </tr>
                <tr>
                    <td>Produk</td>
                    <th scope="row"><?= $result_transaksi["nama_barang"]; ?></th>
                </tr>
                <tr>
                    <td>Harga</td>
                    <th scope="row">Rp <?= number_format($result_transaksi["harga"], "0", ",", "."); ?></th>
                </tr>
                <tr>
                    <td>Jumlah</td>
                    <th scope="row"><?= $result_transaksi["jml_barang"]; ?></th>
                </tr>
                <tr>
                    <td>Sub Total</td>
                    <th scope="row">Rp <?= number_format($result_transaksi["subtotal"], "0", ",", "."); ?></th>
                </tr>
                <tr>
                    <td>Total Berat</td>
                    <th scope="row"><?= number_format($result_transaksi["total_berat"], "0", ",", "."); ?>(g)</th>
                </tr>
                <tr>
                    <td>Ongkos Kirim</td>
                    <th scope="row">Rp <?= $result_transaksi["ongkir"]; ?></th>
                </tr>
                <tr>
                    <td>Total</td>
                    <th scope="row">Rp <?= $result_transaksi["jml_tagihan"]; ?></th>
                </tr>
                <tr>
                    <td>Catatan</td>
                    <th scope="row" class="fst-italic"><?= $result_transaksi["catatan"]; ?></th>
                </tr>
            </tbody>
        </table>

        <h5 class="mb-4 fw-bold mt-5 text-center">Pengiriman</h5>
        <div class="row justify-content-between">
            <div class="col-md-4">
                <strong>Pengirim : </strong>
                <p><?= $result_penjual["username"]; ?></p>
                <small>Telepon :</small>
                <p><?= $result_penjual["nomor_hp"]; ?></p>
                <small>Dikirim Dari :</small>
                <p><?= $result_transaksi["alamat_penjual"] ?></p>
            </div>
            <div class="col-md-4">
                <strong>Penerima :</strong>
                <p class="text-capitalize"><?= $result_user["nama_user"]; ?></p>
                <small>Telepon :</small>
                <p><?= $result_user["nomor_hp"]; ?></p>
                <small>Alamat :</small>
                <p><?= $result_transaksi["alamat_pembeli"] . ", <br>" . $result_transaksi["kota_kab"] . ", Prov. " . $result_transaksi["provinsi"] . ". <br>" . $result_transaksi["kode_pos"]; ?></p>
                <small>Kurir :</small>
                <p class="text-uppercase"><?= $result_transaksi["kurir"]; ?></p>
            </div>
        </div>

        <div class="row">
            <div class="text-success col-5">
                <strong>Note :</strong>
                <ul>
                    <li>
                        <p>Silakan lakukan pembayaran dengan memasukan jumlah tagihan yang benar agar kami bisa mengkonfirmasi pembayaran anda.</p>
                    </li>
                    <li>
                        <p>Setelah melakukan pembayaran, silakan tunggu. Kami akan langsung mengkonfirmasi dan Mengirim pesanan anda.</p>
                    </li>
                    <li>
                        <p>Jika waktu pembayaran anda telah habis, maka Kosell otomatis membatalkan transaksi.</p>
                        <!-- <p>Pastikan memasukan dengan benar 3 <i class="text-secondary">(contoh : Rp 1.000.<mark class="bg-info rounded">123</mark>)</i> digit angka pada Jumlah Tagihan. Agar lebih mudah konfirmasi transaksi Anda.</p> -->
                    </li>
                </ul>
            </div>
        </div>

    </div>


    <!-- reminder -->
    <div id="reminder" class="d-none text-center" style="position: fixed; z-index:99; top:0%; width:100%; height:100%; background-color:rgba(0,0,0,0.5);">
        <p>Yukk!! Bayar sekarang, sebelum kehabisan</p>
        <img src="assets/img/reminder.jpg" width="50%" class="rounded" style="margin:5%;" alt="">
    </div>



    <script>
        // // reminder
        // setTimeout(() => {
        //     $("#reminder").removeClass("d-none");

        // }, 3000)

        // $("#reminder").on("click", () => {
        //     $("#reminder").addClass("d-none");
        // })




        let dapatWaktu = parseInt("<?= $result_transaksi['wkt_beli']; ?>")
        let dateInv = new Date(dapatWaktu - 86400000);

        $(".dateInvoice").text(dateInv.getDay() + "/" + dateInv.getMonth() + "/" + dateInv.getFullYear());
        let ambilMenit = new Date(dapatWaktu).getMinutes();
        let ambilJam = new Date(dapatWaktu).getHours();
        let ambilTgl = new Date(dapatWaktu).getDay();
        var month = new Array();
        month[0] = "Januari";
        month[1] = "Februari";
        month[2] = "Maret";
        month[3] = "April";
        month[4] = "Mei";
        month[5] = "Juni";
        month[6] = "Juli";
        month[7] = "Agustus";
        month[8] = "September";
        month[9] = "Oktober";
        month[10] = "November";
        month[11] = "Desember";
        let ambilBulan = month[new Date(dapatWaktu).getMonth()];
        let ambilTahun = new Date(dapatWaktu).getFullYear();

        $("#waktu").text(ambilJam + " : " + ambilMenit);
        $("#tanggal-berakhir").text(ambilTgl + ", " + ambilBulan + " " + ambilTahun);

        let status = '<?= $result_transaksi['status'] ?>';
        if (status == "true") {
            $("#detik,#menit,#jam,#hari,#waktu,#tanggal-berakhir, #tagihan").hide();
            $("#status").text("pesanan Kamu Dalam Perjalanan");
            $("#status").removeClass("btn-outline-warning");
            $("#status").addClass("btn-outline-success");
            clearInterval(hitungMundur);
        }

        // hitung mundur / countdown :
        let hitungMundur = setInterval(() => {

            // Cari jarak antara sekarang adan hitung mundur
            let sekarang = new Date().getTime();

            let jarak = dapatWaktu - sekarang;

            // kalkulasi untuk hari, jam, menit and detik
            let hari = Math.floor(jarak / (1000 * 60 * 60 * 24));
            let jam = Math.floor((jarak % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
            let menit = Math.floor((jarak % (1000 * 60 * 60)) / (1000 * 60));
            let detik = Math.floor((jarak % (1000 * 60)) / 1000);


            // tampilkan
            $("#detik").text(detik);
            $("#menit").text(menit);
            $("#jam").text(jam);
            $("#hari").text(hari);

            // Jika hitung mundur sudah habis maka lakukan ini :
            if (jarak < 0) {
                clearInterval(hitungMundur);
                $("#detik,#menit,#jam,#hari, #waktu, #tanggal-berakhir, #tagihan, .table").hide();
                $("#waktu-habis").removeClass("d-none");
                $("#waktu-habis p").html("<b>Upps..</b> Batas waktu Pembayaran kamu sudah habis.<br> Jangan khawatir, kamu bisa pesan lagi kok.<br><br><i>Dalam 10 detik akan dibatalkan</i>");

                $("#status").text("expired");
                let status = $("#status").text().toLowerCase();

                if (status == "expired") {

                    // jika expired, lakukan ini dalam ( waktu yang ditentukan)
                    setTimeout(() => {
                        setInterval(hitungMundur);
                        // $("#status").text("data dihapus");
                        var id_trans = <?= $result_transaksi['id_transaksi'] ?>;

                        $.ajax({
                            type: 'GET',
                            url: 'admin/hapus-transaksi.php',
                            data: 'id_transaksi=' + id_trans,

                            success: function(data) {
                                alert("Pesanan dibatalkan!");
                                window.location.href = "admin";
                            }
                        });
                    }, 10000)

                }

            }


        }, 1000)
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