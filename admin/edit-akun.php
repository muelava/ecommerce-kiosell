<?php


session_start();

include "koneksi.php";

if (!isset($_SESSION["login"])) {
    header("Location: ../login");
    return false;
}


// ambil id user dari get
$id_user = $_GET["id_user"];

if (!$id_user) {
    header("Location:daftar-user");
    return false;
}

// ambil data provinsi api rajaongkir
$curl = curl_init();

curl_setopt_array($curl, array(
    CURLOPT_URL => "https://api.rajaongkir.com/starter/province",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "GET",
    CURLOPT_HTTPHEADER => array(
        "key:81d4424e2b099f8b8ea33708087f4b8c"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
// /ambil data provinsi api rajaongkir


// ambil id user dari session
$username = $_SESSION["login"];


// cari user
$user = mysqli_query($conn, "SELECT *FROM user WHERE id_user = '$id_user'");
$result = mysqli_fetch_assoc($user);

// cek apakah tidak menemukan id_user
if (mysqli_num_rows($user) === 0) {
    echo "<script>  alert('user tidak ditemukan'); window.location.href='daftar-user'</script>";
    return false;
}

// data api rajongkir
// cari kota dan provinsi user
$distrik_user = $result["distrik"];

$cur2 = curl_init();
curl_setopt_array($cur2, array(
    CURLOPT_URL => "http://api.rajaongkir.com/starter/cost",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "origin=" . $distrik_user . "&destination=" . $distrik_user . "&weight=500" . "&courier=jne",
    CURLOPT_HTTPHEADER => array(
        "content-type: application/x-www-form-urlencoded",
        "key: 81d4424e2b099f8b8ea33708087f4b8c"
    ),
));
$response2 = curl_exec($cur2);
$err = curl_error($cur2);
curl_close($cur2);
$data = json_decode($response2, true);
$distrikUser = $data['rajaongkir']['origin_details']['city_name'];
$provinsiUser = $data['rajaongkir']['origin_details']['province'];


// tombol update di tekan
if (isset($_POST["update"])) {
    $nama_user = strip_tags($_POST["nama_lengkap"]);
    $email = strip_tags($_POST["email"]);
    $nomor_hp = strip_tags($_POST["nomor_hp"]);
    $provinsi = $_POST["provinsi"];
    $distrik = $_POST["kabupaten"];
    $alamat = strip_tags($_POST["alamat"]);
    $kode_pos = strip_tags($_POST["kode_pos"]);

    mysqli_query($conn, "UPDATE user SET nama_user = '$nama_user', email = '$email', nomor_hp = '$nomor_hp', provinsi = '$provinsi', distrik = '$distrik', alamat = '$alamat', kode_pos = '$kode_pos' WHERE id_user = '$id_user';");

    echo "<script>alert('Berhasil Diubah!'); window.location.href='index'</script>";
    return mysqli_affected_rows($conn);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $result["nama_user"]; ?></title>

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
                                        <span class="ms-1" id="sebagai"><?= $_SESSION["status"] ?></span>
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-light text-small border-0 shadow-sm">
                                    <?php if ($_SESSION["status"] == "user") : ?>
                                        <li class="py-1">
                                            <a class="dropdown-item" href="akun?id_user=<?= $result["id_user"] ?>"><i class="fa fa-user-circle"></i> Akun</a>
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
                        <?php else : ?>
                            <li>
                                <a href="pesanan" class="nav-link px-0 align-middle" style="opacity: .5;">
                                    <i class="fs-4 bi-table"></i> <span class="ms-1" id="navigasi"><img src="assets/img/outline_shopping_cart_black_24dp.png" class="me-2" width="35" alt=""> Pesanan</span></a>
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

            <div class="col-md-5 py-3 mt-5" id="content">

                <h2 class="py-5 text-capitalize">Edit <b><?= $result["status"]; ?></b></h2>

                <form action="" method="POST">
                    <ul style="list-style: none;" class="p-0">
                        <div class="mb-3 form-floating">
                            <input class="form-control form-control-sm" type="text" name="nama_lengkap" value="<?= $result['nama_user']; ?>" required>
                            <label for="floatingInputInvalid">Nama Lengkap</label>
                        </div>
                        <div class="mb-3 form-floating">
                            <input class="form-control form-control-sm" type="text" name="username" value="<?= $result['username']; ?>" disabled required>
                            <label for="floatingInputInvalid">Username</label>
                        </div>
                        <div class="mb-3 form-floating">
                            <input class="form-control form-control-sm" type="email" name="email" value="<?= $result['email']; ?>" required>
                            <label for="floatingInputInvalid">Email</label>
                        </div>
                        <div class="mb-3 form-floating">
                            <input class="form-control form-control-sm" type="text" name="nomor_hp" value="<?= $result['nomor_hp']; ?>" required>
                            <label for="floatingInputInvalid">Nomor Hp/WhatsApp</label>
                        </div>
                        <div class="mb-3 form-floating">
                            <select class="form-select form-select-sm" aria-label="Default select example" name='provinsi' id='provinsi'>";
                                <?php
                                $get = json_decode($response, true);
                                for ($i = 0; $i < count($get['rajaongkir']['results']); $i++) :
                                ?>
                                    <option value="<?php echo $get['rajaongkir']['results'][$i]['province_id']; ?>"><?php echo $get['rajaongkir']['results'][$i]['province']; ?></option>
                                <?php endfor; ?>
                            </select>
                            <label for="floatingInputInvalid">Provinsi</label>
                        </div>
                        <div class="mb-3 form-floating">
                            <select class="form-select form-select-sm" id="kabupaten" name="kabupaten" required>
                                <!-- Data kabupaten akan diload menggunakan AJAX -->
                            </select>
                            <label for="floatingInputInvalid">Kota/Kabupaten</label>
                        </div>
                        <div class="mb-3 form-floating">
                            <textarea class="form-control form-control-sm" name="alamat" style="height: 150px;"> <?= $result['alamat']; ?></textarea>
                            <label for="floatingInputInvalid">Alamat Lengkap</label>
                        </div>
                        <div class="mb-3 form-floating w-50">
                            <input class="form-control form-control-sm" type="text" name="kode_pos" value="<?= $result['kode_pos']; ?>" required>
                            <label for="floatingInputInvalid">Kode Pos</label>
                        </div>
                    </ul>
                    <div class="row justify-content-around">
                        <a href="index" class="btn btn-outline-secondary col-md-2">Cancel</a>
                        <button type="submit" class="btn btn-primary col-md-3" name="update">Update</button>
                    </div>
                </form>

            </div>
        </div>

        <script>
            $(document).ready(function() {
                $("#btn-burger").click(function() {
                    $(".sidebar").toggle(250);
                });
            });

            // ambil data provinsi lama
            $('#provinsi').val(<?= $result["provinsi"] ?>).change();

            // ambil data kabupaten lama
            $(document).ready(function() {

                var prov = $('#provinsi').val();
                var kab_id = <?= $result["distrik"] ?>;
                $.ajax({
                    type: 'GET',
                    url: '../fitur-ongkir/ambil-kabupaten.php',
                    data: 'prov_id=' + prov + '&kab_id=' + kab_id,
                    success: function(data) {
                        //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                        $("#kabupaten").html(data);
                    }
                });
            });

            $('#provinsi').change(function() {

                //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
                var prov = $('#provinsi').val();
                $.ajax({
                    type: 'GET',
                    url: '../fitur-ongkir/ambil-kabupaten.php',
                    data: 'prov_id=' + prov,
                    success: function(data) {
                        //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                        $("#kabupaten").html(data);
                    }
                });
            });
        </script>
        <!-- bootstrap js -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</body>

</html>