<?php

session_start();

if (!isset($_SESSION["login"])) {
    echo "<script>document.location.href = '../login'</script>";
    return false;
}




$harga_barang = $_POST["harga_barang"];


$asal_distrik = $_POST["asal_distrik"]; //Kabupaten asal ongkir akan dihitung dari kota/kabupaten ini ID 39 adalah kabupaten Bantul, Yogyakarta
$id_kabupaten = $_POST['kab_id'];
$kurir = $_POST['kurir'];
$berat = $_POST['berat']; //Berat barang menggunakan satuan gram
$curl = curl_init();
curl_setopt_array($curl, array(
    CURLOPT_URL => "http://api.rajaongkir.com/starter/cost",
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => "",
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 30,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
    CURLOPT_CUSTOMREQUEST => "POST",
    CURLOPT_POSTFIELDS => "origin=" . $asal_distrik . "&destination=" . $id_kabupaten . "&weight=" . $berat . "&courier=" . $kurir . "",
    CURLOPT_HTTPHEADER => array(
        "content-type: application/x-www-form-urlencoded",
        "key:81d4424e2b099f8b8ea33708087f4b8c"
    ),
));

$response = curl_exec($curl);
$err = curl_error($curl);
curl_close($curl);
if ($err) {
    echo "cURL Error #:" . $err;
} else {
    $data = json_decode($response, true);
}
?>
<?php
for ($k = 0; $k < count($data['rajaongkir']['results']); $k++) {
?>
    <div title="<?php echo strtoupper($data['rajaongkir']['results'][$k]['name']); ?>" style="padding-top: 10px;">
        <table border="1" class="table table-borderless" style="font-size:.8em">
            <tr>
                <th>No.</th>
                <th>Jenis Layanan</th>
                <th>ETD</th>
                <th>Tarif</th>
                <th>Pilih</th>
            </tr>
            <?php

            for ($l = 0; $l < count($data['rajaongkir']['results'][$k]['costs']); $l++) {
            ?>
                <tr>
                    <td><?php echo $l + 1; ?></td>
                    <td>
                        <div style="font:bold 14px Arial" id="servis"><?php echo $data['rajaongkir']['results'][$k]['costs'][$l]['service']; ?></div>
                        <div style="font:normal 10px Arial"><?php echo $data['rajaongkir']['results'][$k]['costs'][$l]['description']; ?></div>
                    </td>
                    <td align="center">&nbsp;<?php echo $data['rajaongkir']['results'][$k]['costs'][$l]['cost'][0]['etd']; ?> hari</td>
                    <td align="right"><?php echo number_format($data['rajaongkir']['results'][$k]['costs'][$l]['cost'][0]['value']); ?></td>
                    <td>

                        <div class="radio">
                            <label><input type="radio" tarif="<?php echo $data['rajaongkir']['results'][$k]['costs'][$l]['cost'][0]['value']; ?>" name="pilih_ongkir" class="pilih_ongkir"></label>
                        </div>
                    </td>
                </tr>
            <?php
            }
            ?>
        </table>
    </div>
<?php
}
?>

<!-- menampilkan checkout -->
<table class="table table-striped table-borderless">
    <thead>
        <tr>
            <td><small>Ongkir</small></td>
            <td class="text-end">Rp. </td>
            <td class="text-end" id="ongkir"></td>
        </tr>
    </thead>
    <tbody>
        <tr class="fw-bold">
            <td>Total</td>
            <td class="text-end">Rp. </td>
            <td class="text-end" id="total"></td>
        </tr>
    </tbody>
</table>
<br>
<!-- <span id="ongkir"> </span> -->

<script type="text/javascript">
    // jika tombol quantity ditekan
    $('#quantity').on("click keyup", function() {
        $('#total, #ongkir').text('');
        $('.pilih_ongkir').prop('checked', false);
    });

    $('.pilih_ongkir').on('click', function() {

        var tarif = parseInt($(this).attr("tarif"));
        var subtotal = parseInt($('#subtotal').text());
        var total = tarif + subtotal;


        $('#ongkir').text(format_rupiah(tarif))

        $('#total').text(format_rupiah(total));

    });

    function format_rupiah(nominal) {
        var reverse = nominal.toString().split('').reverse().join(''),
            ribuan = reverse.match(/\d{1,3}/g);
        return ribuan = ribuan.join('.').split('').reverse().join('');
    }
</script>