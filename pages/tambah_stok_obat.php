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
$getSupplier = mysqli_query($connection, "SELECT * FROM supplier");
$getObat = mysqli_query($connection, "SELECT * FROM daftar_obat");
$err_msg = '';
$success_msg = '';
if (isset($_POST['submit'])) {
    // invoice
    $inv = "INV";
    $tahun = htmlentities(strip_tags($_POST['tahun']));
    $bulan =  htmlentities(strip_tags($_POST['bulan']));
    $kode_faktur = htmlentities(strip_tags($_POST['kd_faktur']));
    $faktur = "$inv/$tahun/$bulan/$kode_faktur";

    $kode_obat = $_POST['kd_obat'];
    $stok = htmlentities(strip_tags($_POST['stok']));
    $tanggal = date('Y-m-d H:i:s');
    $supplier = $_POST['kd_supplier'];
    $penerima = $_SESSION['nama'];

    // Validasi
    if (empty($tahun)) {
        $err_msg = "kode faktur maih kosong";
    } else if (empty($bulan)) {
        $err_msg = "kode faktur maih kosong";
    } else if (empty($kode_faktur)) {
        $err_msg = "kode faktur  masih kosong";
    } else if ($kode_obat === "pilih obat") {
        $err_msg = "Kode obat belum dipilih";
    } else if (empty($stok)) {
        $err_msg = "Stok masih kosong";
        if ($stok <= 0) {
            $err_msg = 'Nilai stok kurang dari 1';
        }
    } else  if ($supplier === "pilih supplier") {
        $err_msg =  "Supplier belum dipilih";
    } else {
        // cek apakah barang sudah pernah di masukkan dengan kode faktur yang sama
        $cek = mysqli_query($connection, "SELECT * FROM barang_masuk WHERE kd_faktur='$faktur' AND kd_obat='$kode_obat'");
        if (mysqli_num_rows($cek) > 0) {
            $err_msg = "Stok obat sudah pernah dimasukkan dengan kode faktur yang sama, silahkan cek <a href='../pages/riwayat_barang_masuk.php'>riwayat</a> penambahan stok ! ";
        } else {
            mysqli_query($connection, "INSERT INTO barang_masuk set kd_faktur='$faktur', kd_obat ='$kode_obat', stok_baru ='$stok', tanggal = '$tanggal', kd_supplier ='$supplier', diterima ='$penerima' ");
            $success_msg = "Stok berhasil ditambahkan";
        }
    }
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Medicine</title>
    <link rel="stylesheet" href="style/tambah_stok_obat.css?v=<?php echo time() ?> ">
</head>

<body>
    <div class="container">
        <?php
        include('../component/navigation.php')
        ?>
        <div class="content" onclick=closeAkun()>
            <h2>Tambah Stok Obat</h2>
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

            <div class="history">
                <a href="../pages/riwayat_barang_masuk.php"><i class="fa-solid fa-clock-rotate-left"></i> Obat masuk</a>
                <a href="../pages/tambah_obat.php"><i class="fa-solid fa-plus"></i> Obat Baru</a>

            </div>
            <form action="tambah_stok_obat.php" method="post">
                <div class="field">
                    <h3 class="header">Kode Faktur : </h3>
                    <div class="fieldset">
                        <input value="INV" disabled class="inv" size="2">
                        /
                        <input type="number" class="tahun" name="tahun" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==4) return false;" size="4" maxlength="4">
                        /
                        <input type="number" class="bulan" name="bulan" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==2) return false;" size="2" maxlength="2">
                        /
                        <input type="number" name="kd_faktur" class="kd_faktur" pattern="/^-?\d+\.?\d*$/" onKeyPress="if(this.value.length==10) return false;" size="10" maxlength="10">
                    </div>
                </div>
                <div class="field">
                    <h3 class="header">Kode Obat : </h3>
                    <div class="fieldset"><select name="kd_obat" class="obat">
                            <option> pilih obat</option>
                            <?php
                            while ($data_obat = mysqli_fetch_array($getObat)) {
                                echo " <option value='$data_obat[kd_obat]'> $data_obat[kd_obat] - $data_obat[nama_obat] </option>";
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