<?php


session_start();

if (!isset($_SESSION["login"])) {
    header("Location: login");
}

$username = $_SESSION["login"];

$conn = mysqli_connect('localhost', 'root', '', 'kiosell');

$admin = mysqli_query($conn, "SELECT *FROM admin WHERE username = '$username'");
$result  = mysqli_fetch_assoc($admin);

// inisialisasi waktu
date_default_timezone_set('Asia/Jakarta');

function hari_ini()
{
    $hari = date("D");

    switch ($hari) {
        case 'Sun':
            $hari_ini = "Minggu";
            break;

        case 'Mon':
            $hari_ini = "Senin";
            break;

        case 'Tue':
            $hari_ini = "Selasa";
            break;

        case 'Wed':
            $hari_ini = "Rabu";
            break;

        case 'Thu':
            $hari_ini = "Kamis";
            break;

        case 'Fri':
            $hari_ini = "Jumat";
            break;

        case 'Sat':
            $hari_ini = "Sabtu";
            break;

        default:
            $hari_ini = "Tidak di ketahui";
            break;
    }

    return "<b>" . $hari_ini . "</b>";
}

$waktu = strip_tags(hari_ini() . ", " . date('d/m/Y') . " " . date("H:i:s"));


if (isset($_POST['publish'])) {
    $id_admin = $result["id_admin"];
    $judul = strip_tags($_POST['judul']);
    // $gambar1 = strip_tags($_POST['gambar1']);
    // $gambar2 = strip_tags($_POST['gambar2']);
    // $gambar3 = strip_tags($_POST['gambar3']);
    $harga = strip_tags($_POST['harga']);
    $kategori = strip_tags($_POST['kategori']);
    $kondisi = strip_tags($_POST['kondisi']);
    $jml_barang = strip_tags($_POST['jml_barang']);
    $deskripsi = $_POST['deskripsi'];

    $gambar1 = upload1();
    $gambar2 = upload2();
    $gambar3 = upload3();

    if (!$gambar1) {
        return false;
    }

    $query = "INSERT INTO barang VALUES('','$id_admin','$gambar1','$gambar2','$gambar3','$judul','$harga','$kondisi','$deskripsi','$kategori','$jml_barang','$waktu')";
    $result = mysqli_query($conn, $query);
    echo "<script>alert('Publish Successfully!'); document.location.href = 'postingan'</script>";

    return mysqli_affected_rows($conn);
}


function upload1()
{
    $nameFile = $_FILES["gambar1"]["name"];

    $sizeFile = $_FILES["gambar1"]["size"];

    $error = $_FILES["gambar1"]["error"];

    $tmpName = $_FILES["gambar1"]["tmp_name"];

    if ($error === 4) {
        echo "<script>alert('Tidak ada gambar Thumbnail yang dipilih');</script>";
        return false;
    }

    $imageValid = ["jpg", "jpeg", "png"];
    $extImage = explode(".", $nameFile);

    $extImage = strtolower(end($extImage));

    if (!in_array($extImage, $imageValid)) {
        echo "<script>alert('Yang anda upload bukan gambar');</script>";
        return false;
    }

    if ($sizeFile > 2000000) {
        echo "<script>alert('File gambar terlalu besar. Maximal 2mb!');</script>";
        return false;
    }

    $newNameFile = uniqid();

    $newNameFile .= ".";

    $newNameFile .= $extImage;

    // cari jarum dalam jerami
    move_uploaded_file($tmpName, 'assets/img/post/' . $newNameFile);

    return $newNameFile;
}

function upload2()
{
    $nameFile = $_FILES["gambar2"]["name"];

    $sizeFile = $_FILES["gambar2"]["size"];

    $error = $_FILES["gambar2"]["error"];

    $tmpName = $_FILES["gambar2"]["tmp_name"];

    if ($error === 4) {
        echo "<script>alert('Tidak ada gambar 2 yang dipilih');</script>";
        return false;
    }

    $imageValid = ["jpg", "jpeg", "png"];
    $extImage = explode(".", $nameFile);

    $extImage = strtolower(end($extImage));

    if (!in_array($extImage, $imageValid)) {
        echo "<script>alert('Yang anda upload bukan gambar');</script>";
        return false;
    }

    if ($sizeFile > 2000000) {
        echo "<script>alert('File gambar terlalu besar. Maximal 2mb!');</script>";
        return false;
    }

    $newNameFile = uniqid();

    $newNameFile .= ".";

    $newNameFile .= $extImage;

    // cari jarum dalam jerami
    move_uploaded_file($tmpName, 'assets/img/post/' . $newNameFile);

    return $newNameFile;
}

function upload3()
{
    $nameFile = $_FILES["gambar3"]["name"];

    $sizeFile = $_FILES["gambar3"]["size"];

    $error = $_FILES["gambar3"]["error"];

    $tmpName = $_FILES["gambar3"]["tmp_name"];

    if ($error === 4) {
        echo "<script>alert('Tidak ada gambar 3 yang dipilih');</script>";
        return false;
    }

    $imageValid = ["jpg", "jpeg", "png"];
    $extImage = explode(".", $nameFile);

    $extImage = strtolower(end($extImage));

    if (!in_array($extImage, $imageValid)) {
        echo "<script>alert('Yang anda upload bukan gambar');</script>";
        return false;
    }

    if ($sizeFile > 2000000) {
        echo "<script>alert('File gambar terlalu besar. Maximal 2mb!');</script>";
        return false;
    }

    $newNameFile = uniqid();

    $newNameFile .= ".";

    $newNameFile .= $extImage;

    // cari jarum dalam jerami
    move_uploaded_file($tmpName, 'assets/img/post/' . $newNameFile);

    return $newNameFile;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post Baru</title>

    <script src="assets/js/main.js"></script>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

    <script src="//cdn.ckeditor.com/4.16.1/basic/ckeditor.js"></script>

    <link rel="stylesheet" href="assets/css/main.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">
</head>

<body>

    <div class="container-fluid">
        <div class="row flex-nowrap">
            <!-- navbar -->
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
                                        <span class="ms-1 fw-bold text-capitalize" id="nama"><?= $_SESSION["login"]; ?> <?php if ($_SESSION["status"] == "admin") : ?> <img src="assets/img/check-verifed.png" alt="" width="16"><?php endif; ?></span>
                                        <span class="ms-1" id="sebagai"><?= $_SESSION["status"] ?></span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-light text-small shadow">
                                    <li><a class="dropdown-item" href="#">Profile</a></li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="logout">Sign out</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="post-baru" class="nav-link px-0 align-middle">
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

            <!-- container -->
            <div class="col py-3 mt-5" id="content">


                <br><br>
                <h3>Post Produk Baru</h3>

                <form action="" class="" method="POST" enctype="multipart/form-data">
                    <div class="mb-3 form-floating">
                        <input class="form-control form-control-sm" type="text" name="judul" required>
                        <label for="floatingInputInvalid">Judul atau Nama Produk</label>
                    </div>
                    <div class="mb-3 form-floating w-50">
                        <input class="form-control form-control-sm" type="file" name="gambar1" required>
                        <label for="floatingInputInvalid">Gambar 1</label>
                    </div>
                    <div class="mb-3 form-floating w-50">
                        <input class="form-control form-control-sm" type="file" name="gambar2">
                        <label for="floatingInputInvalid">Gambar 2</label>
                    </div>
                    <div class="mb-3 form-floating w-50">
                        <input class="form-control form-control-sm" type="file" name="gambar3">
                        <label for="floatingInputInvalid">Gambar 3</label>
                    </div>
                    <div class="mb-3 form-floating w-50">
                        <input class="form-control form-control-sm" type="text" name="harga" required>
                        <label for="floatingInputInvalid">Harga</label>
                    </div>
                    <div class="mb-3 d-flex form-floating w-100">
                        <select class="form-select form-select-sm" aria-label="Default select example" name="kategori">
                            <option value="Elektronik">Elektronik</option>
                            <option value="Pakaian">Pakaian</option>
                            <option value="Otomotif">Otomotif</option>
                        </select>
                        <label for="floatingInputInvalid">Kategori</label>

                        <div class="mb-3 w-100 form-floating mx-2">
                            <select class="form-select form-select-sm" aria-label="Default select example" name="kondisi">
                                <option value="Baru">Baru</option>
                                <option value="Bekas">Bekas</option>
                            </select>
                            <label for="floatingInputInvalid">Kondisi</label>
                        </div>
                        <div class="mb-3 form-floating w-100">
                            <input class="form-control form-control-sm" type="number" name="jml_barang" min="0" required>
                            <label for="floatingInputInvalid">Jumlah</label>
                        </div>
                    </div>
                    <div class="mb-3 form-floating">
                        <div class="form-text">
                            Deskrpsi Produk
                        </div>
                        <textarea class="form-control ckeditor" id="floatingTextarea2" style="height: 200px" placeholder="Deskripsi" name="deskripsi"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100" name="publish">publish</button>

                </form>

                <!-- start tampilkan ucapan & doa -->
                <!-- end tampilkan ucapan & doa -->

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