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


// cari ulasan, barang dan  user
$ulasan = mysqli_query($conn, "SELECT gambar1, id_ulasan, id_barang, nama_barang, komentar, username, rating, wkt_ulasan FROM ulasan JOIN barang USING(id_barang) JOIN user USING(id_user) ORDER BY id_ulasan DESC;");



// cari data form pencari
if (isset($_POST["cari"])) {
    $ulasan = cari($_POST["keyword"]);
}

function query($query)
{
    global $conn;
    $result2 = mysqli_query($conn, $query);
    $rows = [];
    while ($row = mysqli_fetch_assoc($result2)) {
        $rows[] = $row;
    }
    return $rows;
}

function cari($keyword)
{
    $query = "SELECT *FROM barang JOIN ulasan USING(id_barang) JOIN user USING(id_user)  WHERE nama_barang LIKE '%$keyword%' OR rating LIKE '%$keyword%' OR username LIKE '%$keyword%' OR komentar LIKE '%$keyword%' OR wkt_ulasan LIKE '%$keyword%' ORDER BY id_ulasan";
    return query($query);
}
// end cari


if ($_SESSION["status"] === "admin") {
    $admin = mysqli_query($conn, "SELECT *FROM admin WHERE username = '$username'");
    $result  = mysqli_fetch_assoc($admin);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Semua Ulasan</title>

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
                                <a href="ulasan" class="nav-link px-0 align-middle">
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
                <form action="" class="form-cari" method="POST">
                    <div class="mb-3 d-flex p-3">
                        <input class="form-control mainLoginInput me-2" type="text" placeholder="&#61442; Cari Nama, Ulasan, dsb" name="keyword" autocomplete="off" autofocus />
                        <button type="submit" class="form-control btncari text-white fw-bold" name="cari">Cari</button>
                    </div>
                </form>
                <table class="table table-borderless">
                    <thead>
                        <tr style="border-bottom: 1px solid #C9C9C9;">
                            <th scope="col">No</th>
                            <th scope="col">Produk</th>
                            <th scope="col">Rating</th>
                            <th scope="col">Komentar</th>
                            <th scope="col">Username</th>
                            <th scope="col">Tanggal</th>
                            <th scope="col">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1 ?>
                        <?php foreach ($ulasan as $crs) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td class="d-flex align-items-center text-capitalize">
                                    <img src="assets/img/post/<?= $crs['gambar1'] ?>" class="me-3" width="50" alt="">
                                    <?= $crs["nama_barang"]; ?>
                                </td>
                                <td class="text-center"><i class="fa fa-star text-warning"></i> (<?= $crs["rating"]; ?>)</td>
                                <td class="fst-italic"><?= $crs["komentar"]; ?></td>
                                <td><?= $crs["username"]; ?></td>
                                <td><?= $crs["wkt_ulasan"]; ?></td>
                                <td>
                                    <a href="hapus-ulasan?id_ulasan=<?= $crs['id_ulasan'] ?>" class="btn btn-outline-danger btn-sm" title="" onclick=" return confirm('Yakin ingin menghapus ulasan <?= $crs['username'] ?> ?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <a href="../produk?id=<?= $crs['id_barang'] ?>" class="btn btn-outline-success btn-sm" title="Lihat <?= $crs['nama_barang'] ?>">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>

            </div>
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