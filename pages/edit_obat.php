<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
include_once("../config.php");
session_start();
if (!isset($_SESSION['nama'])) {
    header('location: ../login.php');
}
$err_msg = '';
$success_msg = '';

if (isset($_POST['update'])) {
    $kode_obat = $_POST['kode_obat'];
    $nama_baru = htmlentities(strip_tags($_POST['nama_baru']));
    $sediaan_baru = $_POST['sediaan_baru'];
    $kekuatan_baru =  htmlentities(strip_tags($_POST['kekuatan_baru']));
    $satuan_baru = $_POST['satuan_baru'];
    $stok_baru =   htmlentities(strip_tags($_POST['stok_baru']));
    $harga_baru =   htmlentities(strip_tags($_POST['harga_baru']));

    $query = "UPDATE obat SET nama_obat='$nama_baru',id_sediaan_obat= $sediaan_baru, kekuatan=$kekuatan_baru,id_satuan_obat= $satuan_baru, stok =$stok_baru, harga= $harga_baru where kd_obat=$kode_obat";

    $update = mysqli_query($connection, $query);

    header("location: ../pages/daftar_obat.php ");
}

$id = $_GET['id'];

$getSediaan = mysqli_query($connection, "SELECT * FROM sediaan_obat");
$getSatuan = mysqli_query($connection, "SELECT * FROM satuan_obat");
$obat = mysqli_query($connection, "SELECT * from detail_obat where kd_obat=$id ");
while ($data_obat = mysqli_fetch_array($obat)) {
    $kode_obat = $data_obat['kd_obat'];
    $nama_obat = $data_obat['nama_obat'];
    $sediaan = $data_obat['sediaan'];
    $kekuatan = $data_obat['kekuatan'];
    $satuan = $data_obat['satuan'];
    $stok = $data_obat['stok'];
    $harga = $data_obat['harga'];
    $supplier = $data_obat['nama_supplier'];
    $alamat_supplier = $data_obat['alamat'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style/edit_obat.css?v=<?php echo time() ?>">
    <title><?php echo $nama_obat ?></title>
</head>

<body>
    <div class="container">
        <?php
        include('../component/navigation.php')
        ?>
        <div class="content" onclick=closeAkun()>
            <h2>Edit Obat</h2>
            <div id="message"> <?php
                                if ($err_msg != '') {
                                    echo '            <div id="error">';
                                    $info = $err_msg;
                                    include("../component/info.php");
                                    echo '      </div>';
                                }

                                if ($success_msg != '') {
                                    echo '            <div id="success">';
                                    $info = $success_msg;
                                    include("../component/info.php");
                                    echo '      </div>';
                                }
                                ?>
            </div>
            <form action="edit_obat.php" method="POST">
                <div class="fieldset">
                    <input type="hidden" name="kode_obat" value="<?php echo $_GET['id'] ?>">
                    <div class="field">
                        <h3>Nama :</h3>
                        <input type=" text" name="nama_baru" value='<?php echo $nama_obat ?>'>
                    </div>
                    <div class="field">
                        <h3>Sediaan :</h3>
                        <select name="sediaan_baru" id="sediaan_obat">
                            <?php
                            while ($data_sediaan = mysqli_fetch_array($getSediaan)) {

                                if ($data_sediaan['sediaan'] === $sediaan) {
                                    echo " <option value=$data_sediaan[id]  selected> $data_sediaan[sediaan]</option> ";
                                } else {

                                    echo " <option value=$data_sediaan[id]>$data_sediaan[sediaan]</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="field">
                        <h3>Kekuatan :</h3>
                        <input type="number" name="kekuatan_baru" value='<?php echo $kekuatan ?>'>
                    </div>
                    <div class="field">
                        <h3>Satuan :</h3>
                        <select name="satuan_baru" id="satuan_baru">

                            <?php
                            while ($data_satuan = mysqli_fetch_array($getSatuan)) {

                                if ($data_satuan['satuan'] === $satuan) {
                                    echo " <option value=$data_satuan[id]  selected>$data_satuan[satuan]</option> ";
                                } else {

                                    echo " <option value=$data_satuan[id]>$data_satuan[satuan]</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>
                    <div class="field">
                        <h3>Stok :</h3>
                        <input type="number" min=1 name="stok_baru" value=<?php echo $stok ?>>
                    </div>
                    <div class="field">
                        <h3>Harga :</h3>
                        <input type="number" name="harga_baru" value=<?php echo $harga ?>>
                    </div>
                    <div class="field">
                        <input type="submit" name="update" value="Update">
                    </div>
                </div>

            </form>
        </div>



</body>

</html>