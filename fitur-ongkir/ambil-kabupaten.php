<?php

$provinsi_id = $_GET['prov_id'];


session_start();

if (!isset($_SESSION["login"]) && !$provinsi_id) {
    echo "<script>document.location.href = '../login'</script>";
    return false;
}


$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "http://api.rajaongkir.com/starter/city?province=$provinsi_id",
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

curl_close($curl);

if ($err) {
    echo "cURL Error #:" . $err;
} else {
    //echo $response;
}

$data = json_decode($response, true);
for ($i = 0; $i < count($data['rajaongkir']['results']); $i++) {
    echo "<option value='" . $data['rajaongkir']['results'][$i]['city_id'] . "'>" . $data['rajaongkir']['results'][$i]['city_name'] . "</option>";
}
?>


<!-- kondisi jika melakukan edit akun user -->
<?php
if (isset($_GET['kab_id'])) : $kab_id = $_GET['kab_id']; ?>
    <script>
        $('#kabupaten').val(<?= $kab_id ?>).change();
    </script>
<?php endif; ?>
<!-- end kondisi -->