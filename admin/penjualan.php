<?php


session_start();

include "koneksi.php";


if (!isset($_SESSION["login"])) {
    header("Location: ../login");
    return false;
} elseif ($_SESSION["status"] != "admin") {
    header("Location: index");
    return false;
}


$username = $_SESSION["login"];

$transaksi = mysqli_query($conn, "SELECT *FROM transaksi ORDER BY id_transaksi DESC");
// $result_penjualan = mysqli_fetch_assoc($transaksi);


// cari data
if (isset($_POST["cari"])) {
    $transaksi = cari($_POST["keyword"]);
}

function query($query)
{
    global $conn;
    $result2 = mysqli_query($conn, $query);
    // jika tidak ditemukan
    if (mysqli_num_rows($result2) == 0) {
        echo "<script>alert('Tidak ditemukan!')</script>";
    }
    $rows = [];
    while ($row = mysqli_fetch_assoc($result2)) {
        $rows[] = $row;
    }
    return $rows;
}

function cari($keyword)
{
    $query = "SELECT *FROM transaksi WHERE nama_barang LIKE '%$keyword%' OR kode_transaksi LIKE '%$keyword%' ORDER BY id_transaksi DESC";

    return query($query);
}
// end cari


if ($_SESSION["status"] === "admin") {
    $admin = mysqli_query($conn, "SELECT *FROM admin WHERE username = '$username'");
    $result  = mysqli_fetch_assoc($admin);
} else {
    $user = mysqli_query($conn, "SELECT *FROM user WHERE username = '$username'");
    $result  = mysqli_fetch_assoc($user);
}



// ketika tombol konfirm ditekan
if (isset($_POST["konfirm"])) {
    $id_transaksi = $_POST["id_transaksi"];
    $no_resi = $_POST["no_resi"];
    $status = "true";

    // cari jumlah order dan id barang di table transaksi
    $dataTransaksi = mysqli_query($conn, "SELECT *FROM transaksi WHERE id_transaksi = '$id_transaksi'");
    $resultDataTransaksi = mysqli_fetch_assoc($dataTransaksi);
    $jumlahBarang = $resultDataTransaksi["jml_barang"];
    $idBarang = $resultDataTransaksi["id_barang"];


    // cari stok barang
    $stokBarang = mysqli_query($conn, "SELECT jml_barang FROM barang WHERE id_barang = '$idBarang'");
    $resultStokBarang = mysqli_fetch_assoc($stokBarang);
    $jumlahStok = $resultStokBarang["jml_barang"];

    $totalStok = $jumlahStok - $jumlahBarang;

    if ($totalStok < 0) {
        echo "<script>alert('Stok Barang tidak mencukupi'); window.location.href='penjualan';</script>";
        return false;
    }

    // update data
    mysqli_query($conn, "UPDATE transaksi SET status = '$status', no_resi = '$no_resi' WHERE id_transaksi = '$id_transaksi'");
    mysqli_query($conn, "UPDATE barang SET jml_barang = '$totalStok' WHERE id_barang = '$idBarang'");


    echo "<script> alert('Pesanan telah dikonfirmasi'); window.location.href='penjualan'</script>";
    return mysqli_affected_rows($conn);
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Penjualan</title>

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
                                    <li><a class="dropdown-item text-danger" href="logout"> <i class="fa fa-sign-out"></i> Keluar</a></li>
                                </ul>
                            </div>
                        </li>
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
                        <?php if ($_SESSION["status"] == "admin") : ?>
                            <li>
                                <a href="daftar-user" class="nav-link px-0 align-middle" style="opacity: .5;">
                                    <i class="fs-4 bi-table"></i> <span class="ms-1" id="navigasi"><img src="assets/img/outline_people_black_24dp.png" class="me-2" width="35" alt=""> Daftar User</span>
                                </a>
                            </li>
                            <li>
                                <a href="penjualan" class="nav-link px-0 align-middle">
                                    <i class="fs-4 bi-table"></i> <span class="ms-1" id="navigasi"><img src="assets/img/outline_notifications_active_black_24dp.png" class="me-2" width="35" alt=""> Penjualan</span></a>
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

            <!-- container -->
            <div class="col py-3 mt-5" id="content">

                <form action="" class="form-cari" method="POST">
                    <div class="mb-3 d-flex p-3">
                        <input class="form-control mainLoginInput me-2" type="text" placeholder="&#61442; Cari nama atau kode transaksi" name="keyword" autocomplete="off" autofocus />
                        <button type="submit" class="form-control btncari text-white fw-bold" name="cari">Cari</button>
                    </div>
                </form>

                <h2 class="pt-5 text-capitalize">Data <b>Penjualan</b></h2>
                <table class="table">
                    <thead>
                        <tr>
                            <th scope="col">No.</th>
                            <th scope="col">Produk</th>
                            <th scope="col">Kode</th>
                            <th scope="col">Total</th>
                            <th scope="col">Status</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1 ?>
                        <?php foreach ($transaksi as $result) : ?>
                            <tr>
                                <th scope="row"><?= $i++; ?></th>
                                <td><?= $result["nama_barang"] ?></td>
                                <td>K-<?= $result["kode_transaksi"]; ?></td>
                                <td>Rp <?= $result["jml_tagihan"]; ?></td>
                                <td class="text-primary">
                                    <?php
                                    if ($result["status"] == "true") {
                                        echo "<span class='text-success'>Dikirim</span>" . " <i class='fa fa-check-circle text-success'></i>";
                                    } else {
                                        echo "<i class='fa fa-clock-o'></i> Menunggu <br> Pembayaran";
                                    }
                                    ?>
                                </td>
                                <td>
                                    <?php
                                    if ($result["status"] == "false") : ?>
                                        <button data-bs-toggle="modal" data-bs-target="#exampleModal" id_transaksi="<?= $result['id_transaksi'] ?>" class="btn btn-sm btn-success konfirmasi">Konfirmasi</button><br>
                                    <?php endif; ?>
                                    <a onclick="return confirm('Yakin hapus transaksi <?= $result["nama_barang"] ?>')" class="btn btn-sm btn-danger" href="hapus-transaksi?id_transaksi=<?= $result['id_transaksi'] ?>">Hapus</a><br>
                                    <a class="btn btn-sm btn-info" href="../transaksi?id_transaksi=<?= $result['id_transaksi'] ?>">Detail</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php
                $transaksi = mysqli_query($conn, "SELECT *FROM transaksi");
                if (mysqli_num_rows($transaksi) == 0) : ?>
                    <h5 class="text-center" style="margin-top: 15%;"><strong>Lapor,</strong> Belum ada Penjualan Boss!!</h5>
                    <div class="d-flex justify-content-center">
                        <img src="../assets/img/empty.jpg" class="img-fluid col-md-5" alt="">
                    </div>
                <?php endif; ?>

            </div>
        </div>
    </div>


    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Konfirmasi Pembayaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form action="" method="POST">
                    <div class="modal-body">
                        <div class="d-flex justify-content-around">
                            <p>Masukkan Resi :</p>
                            <input class="form-control w-50" type="text" name="no_resi" id="nomor-resi" required>
                            <input class="form-control w-50" type="hidden" name="id_transaksi" id="id-transaksi" required>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-success" name="konfirm" id="konfirm">Konfirm</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end modal -->


    <script>
        $(document).ready(function() {
            $("#btn-burger").click(function() {
                $(".sidebar").toggle(250);
            });
        });


        $(".konfirmasi").on("click", function() {
            $("#id-transaksi").val($(this).attr("id_transaksi"));
        });
    </script>
    <!-- bootstrap js -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>
</body>

</html>