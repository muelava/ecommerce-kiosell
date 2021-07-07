<?php


session_start();

if (!isset($_SESSION["login"])) {
    header("Location: ../login");
    return false;
} elseif ($_SESSION["status"] != "admin") {
    header("Location: index");
    return false;
}


$username = $_SESSION["login"];


$conn = mysqli_connect('localhost', 'root', '', 'kiosell');


$barang = mysqli_query($conn, "SELECT *FROM barang ORDER BY id_barang DESC");



// cari data
if (isset($_POST["cari"])) {
    $barang = cari($_POST["keyword"]);
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
    $query = "SELECT *FROM barang WHERE nama_barang LIKE '%$keyword%' OR harga LIKE '%$keyword%' OR kategori LIKE '%$keyword%' OR wkt_post LIKE '%$keyword%' ORDER BY id_barang DESC";
    return query($query);
}
// end cari

// tentukan status admin atau user
if ($_SESSION["status"] === "admin") {
    $admin = mysqli_query($conn, "SELECT *FROM admin WHERE username = '$username'");
    $result  = mysqli_fetch_assoc($admin);
} else {
    $user = mysqli_query($conn, "SELECT *FROM user WHERE username = '$username'");
    $result  = mysqli_fetch_assoc($user);
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Postingan</title>

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
                            <a href="postingan" class="nav-link px-0 align-middle">
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
                                <a href="pemberitahuan" class="nav-link px-0 align-middle" style="opacity: .5;">
                                    <i class="fs-4 bi-table"></i> <span class="ms-1" id="navigasi"><img src="assets/img/outline_notifications_active_black_24dp.png" class="me-2" width="35" alt=""> Pemberitahuan</span></a>
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
                        <input class="form-control mainLoginInput me-2" type="text" placeholder="&#61442; Cari Postingan" name="keyword" autocomplete="off" autofocus />
                        <button type="submit" class="form-control btncari text-white fw-bold" name="cari">Cari</button>
                    </div>
                </form>

                <table class="table table-borderless">
                    <thead>
                        <tr style="border-bottom: 1px solid #C9C9C9;">
                            <th scope="col">No</th>
                            <th scope="col"><i class="fa fa-history"></i></th>
                            <th scope="col">Judul</th>
                            <th scope="col">Harga</th>
                            <th scope="col">Kategori</th>
                            <th scope="col">Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $i = 1 ?>
                        <?php foreach ($barang as $crs) : ?>
                            <tr>
                                <td><?= $i++; ?></td>
                                <td><small style="font-size: 10px;"><?= $crs["wkt_post"] ?></small></td>
                                <td class="d-flex align-items-center text-capitalize">
                                    <?= $crs["nama_barang"]; ?>
                                </td>
                                <td><?= number_format($crs["harga"], '0', '', '.'); ?></td>
                                <td><?= $crs["kategori"]; ?></td>
                                <td>
                                    <a href="../produk?id=<?= $crs["id_barang"] ?>" class="btn btn-outline-success btn-sm" target="_blank" title="Lihat <?= $crs['nama_barang'] ?>">
                                        <i class="fa fa-eye"></i>
                                    </a>
                                    <a href="hapus-post?id_post=<?= $crs["id_barang"] ?>" class="btn btn-outline-danger btn-sm" title="Hapus <?= $crs['nama_barang'] ?>" onclick=" return confirm('Yakin Ingin Menghapus <?= $crs["nama_barang"] ?>  ?')">
                                        <i class="fa fa-trash"></i>
                                    </a>
                                    <a href="edit-post?id_post=<?= $crs["id_barang"] ?>" class="btn btn-outline-primary btn-sm" title="Lihat <?= $crs['nama_barang'] ?>">
                                        <i class="fa fa-edit"></i>
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