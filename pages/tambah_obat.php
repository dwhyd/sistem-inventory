<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
if (
    !isset($_SESSION['nama'])
    && !isset($_SESSION['nama']) && !isset($_SESSION['role'])
) {
    header('location: ../login.php');
}

if ($_SESSION['role'] > 2) {
    header('location: ../pages/daftar_obat.php');
}
include_once('../config.php');
date_default_timezone_set('Asia/Jakarta');
date('m/d/y H:i:s');
$getSediaanObat = mysqli_query($connection, "SELECT * FROM sediaan_obat");
$getSatuanObat = mysqli_query($connection, "SELECT * FROM satuan_obat");
$getSupplier = mysqli_query($connection, "SELECT * FROM supplier");
$getObat = mysqli_query($connection, "SELECT * FROM daftar_obat");
$err_msg = '';
$success_msg = '';
if (isset($_POST['submit'])) {
    $nama = htmlentities(strip_tags($_POST['nama']));
    $sediaan = $_POST['sediaan'];
    $kekuatan = htmlentities(strip_tags($_POST['kekuatan']));
    $satuan = $_POST['satuan'];
    $stok = htmlentities(strip_tags($_POST['stok']));
    $harga = htmlentities(strip_tags($_POST['harga']));
    $supplier = $_POST['kd_supplier'];

    // Validasi
    if (empty($nama)) {
        $err_msg = "Nama masih kosong";
    } else if ($sediaan === "pilih sediaan obat") {
        $err_msg =  "Sediaan belum dipilih";
    } else if (empty($kekuatan)) {
        $err_msg = "Kekuatan masih kosong";
    } else  if ($satuan === "pilih satuan obat") {
        $err_msg =  "Satuan belum dipilih";
    } else if (empty($stok)) {
        $err_msg = "Stok masih kosong";
        // if ($stok <= 0) {
        //     $err_msg = 'Nilai stok kurang dari 1';
        // }
    } else if (empty($harga)) {
        $err_msg = "Harga masih kosong";
    } else  if ($supplier === "pilih supplier") {
        $err_msg =  "Supplier belum dipilih";
    } else {
        // cek apakah nama obat sudah ada
        $cek = mysqli_query($connection, "SELECT * FROM obat WHERE nama_obat='$nama' AND id_sediaan_obat=$sediaan AND kekuatan=$kekuatan ");
        if (mysqli_num_rows($cek) > 0) {
            $err_msg = "Nama obat dengan jenis sediaan & kekuatan yang sama sudah ada, silahkan gunakan nama lain !";
        } else {
            mysqli_query($connection, "INSERT INTO obat set nama_obat='$nama', id_sediaan_obat=$sediaan, kekuatan=$kekuatan, id_satuan_obat=$satuan, stok=$stok, harga=$harga, kd_supplier=$supplier   ");
            $success_msg = "Stok berhasil ditambahkan";
        }
    }
    $nama;
    $sediaan;
    $kekuatan;
    $satuan;
    $stok;
    $harga;
    $supplier;
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Obat</title>
    <link rel="stylesheet" href="style/tambah_obat.css?v=<?php echo time() ?> ">
</head>

<body>
    <div class="container">
        <?php
        include('../component/navigation.php')
        ?>
        <div class="content" onclick=closeAkun()>
            <h2>Tambah Obat Baru</h2>
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

            <div class="menu">
                <a href="../pages/riwayat_barang_masuk.php"><i class="fa-solid fa-clock-rotate-left"></i> Tambah Stok Obat</a>
            </div>
            <form action="tambah_obat.php" method="post">
                <div class="field">
                    <h3 class="header">Nama Obat: </h3>
                    <div class="fieldset">
                        <input type="text" name="nama">
                    </div>
                </div>
                <div class="field">
                    <h3 class="header">Sediaan Obat: </h3>
                    <div class="fieldset"><select name="sediaan" class="obat">
                            <option> pilih sediaan obat</option>
                            <?php
                            while ($data_sediaan = mysqli_fetch_array($getSediaanObat)) {

                                echo " <option value='$data_sediaan[id]'> $data_sediaan[id] - $data_sediaan[sediaan] </option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="field">
                    <h3 class="header">Kekuatan : </h3>
                    <div class="fieldset">
                        <input placeholder="1-9999" type="number" name="kekuatan" class="kekuatan" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==4) return false;" size="4" maxlength="4" size="4">
                    </div>
                </div>
                <div class="field">
                    <h3 class="header">Satuan Obat: </h3>
                    <div class="fieldset"><select name="satuan" class="obat">
                            <option> pilih satuan obat</option>
                            <?php
                            while ($data_satuan = mysqli_fetch_array($getSatuanObat)) {
                                echo " <option value='$data_satuan[id]'> $data_satuan[id] - $data_satuan[satuan] </option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="field">
                    <h3 class="header">Stok : </h3>
                    <div class="fieldset">
                        <input placeholder="1-9999" type="number" name="stok" class="stok" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==4) return false;" size="4" maxlength="4" size="4">
                    </div>
                </div>
                <div class="field">
                    <h3 class="header">Harga : </h3>
                    <div class="fieldset">
                        <input type="number" name="harga" class="harga" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==4) return false;" size="4" maxlength="4" size="4">
                    </div>
                </div>
                <div class="field">
                    <h3 class="header">Supplier : </h3>
                    <div class="fieldset"><select name="kd_supplier">
                            <option>pilih supplier</option>
                            <?php
                            while ($data_supplier = mysqli_fetch_array($getSupplier)) {
                                echo " <option value='$data_supplier[kd_supplier]'>$data_supplier[nama_supplier] </option>";
                            }

                            ?>
                        </select>
                    </div>
                </div>
                <div class="field">
                    <div class="button">
                        <input type="submit" name="submit" value="Tambah Stok">
                    </div>
                </div>
            </form>

        </div>
    </div>
</body>

</html>