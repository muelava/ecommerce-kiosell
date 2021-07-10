<?php

session_start();

if (isset($_SESSION["login"])) {
    header("Location: admin");
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


$conn = mysqli_connect("localhost", "root", "", "kiosell");


if (isset($_POST["register"])) {

    $nama_user = $_POST["nama_depan"] . " " . $_POST["nama_belakang"];
    $username = strtolower(stripslashes($_POST["username"]));
    $email = $_POST["email"];
    $nomor_hp = $_POST["nomor_hp"];
    $provinsi = $_POST["provinsi"];
    $distrik = $_POST["kabupaten"];
    $kode_pos = strip_tags($_POST["kode_pos"]);
    $alamat = strip_tags($_POST["alamat"]);
    $password = mysqli_real_escape_string($conn, $_POST["password"]);
    $konfirm_password = $_POST["konfirm_password"];

    $result = mysqli_query($conn, "SELECT username FROM user WHERE username = '$username'");

    $result2 = mysqli_query($conn, "SELECT username FROM admin WHERE username = '$username'");

    if (mysqli_fetch_assoc($result)) {
        echo "<script>alert('Username telah digunakan'); document.location.href = 'register'</script>";
        return false;
    } elseif (mysqli_fetch_assoc($result2)) {
        echo "<script>alert('Username telah digunakan'); document.location.href = 'register'</script>";
        return false;
    }

    if ($password != $konfirm_password) {
        echo "<script>alert('Password tidak sesuai'); document.location.href = 'register'</script>";
        return false;
    }


    $password = password_hash($password, PASSWORD_DEFAULT);

    mysqli_query($conn, "INSERT INTO user VALUES('','$nama_user','$username','$email','$nomor_hp','$provinsi','$distrik','$kode_pos','$alamat','$password','user','$waktu')");

    echo "<script>alert('Berhasil Regitrasi. Silakan Login!'); document.location.href = 'login'</script>";
    return mysqli_affected_rows($conn);
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="admin/assets/css/main.css">


    <link rel="stylesheet" href="assets/css/main.css">

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-+0n0xVW2eSR5OomGNYDnhzAbDsOXxcvSN1TPprVMTNDbiYZCxYbOOl7+AMvyTG2x" crossorigin="anonymous">


</head>

<body>
    <?php if (isset($error)) : ?>
        <script>
            alert('username / password yang anda masukan salah!');
        </script>
    <?php endif; ?>


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
    <h4 class="text-center mt-5">Selamat Datang <strong>Register</strong></h4>

    <form action="" method="POST" class="form-login">
        <ul style="list-style: none;" class="p-0">
            <li><input type="text" class="form-control" placeholder="Nama Depan" name="nama_depan" autofocus required></li><br>
            <li><input type="text" class="form-control" placeholder="Nama Belakang" name="nama_belakang"></li><br>
            <li><input type="text" class="form-control" placeholder="Username" name="username"></li><br>
            <li><input type="email" class="form-control" placeholder="Email" name="email"></li><br>
            <li><input type="text" class="form-control" placeholder="Nomor Hp/WhatsApp" name="nomor_hp"></li><br>
            <li>
                <select class="form-control" name='provinsi' id='provinsi'>";
                    <option>Pilih Provinsi</option>
                    <?php
                    $get = json_decode($response, true);
                    for ($i = 0; $i < count($get['rajaongkir']['results']); $i++) :
                    ?>
                        <option value="<?php echo $get['rajaongkir']['results'][$i]['province_id']; ?>"><?php echo $get['rajaongkir']['results'][$i]['province']; ?></option>
                    <?php endfor; ?>
                </select>
            </li><br>
            <li>
                <label>Kabupaten</label><br>
                <select class="form-control form-control-sm" id="kabupaten" name="kabupaten" disabled="true" required>
                    <!-- Data kabupaten akan diload menggunakan AJAX -->
                </select> <br>
            </li><br>
            <li><input type="text" class="form-control" placeholder="Alamat Lengkap" name="alamat"></li><br>
            <li><input type="text" class="form-control w-50" placeholder="Kode Pos" name="kode_pos" required></li><br>
            <li><input type="password" class="form-control" placeholder="password" id="password" name="password" required></li><br>
            <li><input type="password" class="form-control" placeholder="Konfirmasi Password" id="konfirm_password" name="konfirm_password" required></li>
            <small id="message"></small><br>
            <button name="register" class="btn w-100 my-3" id="mycolor-bg" type="submit">Register</button>
            <a href="login">Sudah Punya Akun ?</a>
        </ul>
    </form>


    <script>
        $('#password, #konfirm_password').on('keyup', function() {
            if ($('#password').val() == $('#konfirm_password').val()) {
                $('#message').html('password sesuai').css('color', 'green');
            } else
                $('#message').html('password tidak sesuai').css('color', 'red');
        });



        // ongkir break point

        $('#provinsi').change(function() {
            $("#kabupaten").prop("disabled", false);
            //Mengambil value dari option select provinsi kemudian parameternya dikirim menggunakan ajax
            var prov = $('#provinsi').val();
            var nama_provinsi = $(this).attr("nama_provinsi");
            $.ajax({
                type: 'GET',
                url: 'fitur-ongkir/ambil-kabupaten.php',
                data: 'prov_id=' + prov,
                success: function(data) {
                    //jika data berhasil didapatkan, tampilkan ke dalam option select kabupaten
                    $("#kabupaten").html(data);
                }
            });
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/js/bootstrap.bundle.min.js" integrity="sha384-gtEjrD/SeCtmISkJkNUaaKMoLD0//ElJ19smozuHV6z3Iehds+3Ulb9Bn9Plx0x4" crossorigin="anonymous"></script>

</body>

</html>