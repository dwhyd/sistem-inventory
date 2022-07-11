<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id = $_GET['id'];

include_once("../config.php");
session_start();
if (
    !isset($_SESSION['nama'])
    && !isset($_SESSION['nama']) && !isset($_SESSION['role'])
) {
    header('location: ../login.php');
}
$obat = mysqli_query($connection, "SELECT * from detail_obat where kd_obat=$id ");
// jika data sudah tidak ada maka akan menuju daftar obat

while ($data_obat = mysqli_fetch_array($obat)) {
    $kd_obat = $data_obat['kd_obat'];
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
    <link rel="stylesheet" href="style/detail_obat.css?v=<?php echo time() ?>">
    <title><?php echo $nama_obat ?></title>
</head>

<body>
    <div class="container">
        <?php
        include('../component/navigation.php')
        ?>
        <div class="content" onclick=closeAkun()>
            <h2>Detail Obat</h2>
            <div class="fieldset">
                <div class="field
                ">
                    <h4>Kode Obat</h4>
                    <p>: <?php echo $kd_obat ?></p>
                </div>
                <div class="field">
                    <h4>Nama</h4>
                    <p>: <?php echo $nama_obat ?></p>
                </div>
                <div class="field">
                    <h4>Sediaan </h4>
                    <p>: <?php echo $sediaan ?></p>
                </div>
                <div class="field">
                    <h4>Kekuatan </h4>
                    <p>: <?php echo $kekuatan ?></p>
                </div>
                <div class="field">
                    <h4>Satuan </h4>
                    <p>: <?php echo $satuan ?></p>
                </div>
                <div class="field">
                    <h4>Stok </h4>
                    <p>: <?php echo $stok ?></p>
                </div>
                <div class="field">
                    <h4>Harga </h4>
                    <p>: <?php echo "Rp. " . number_format($harga, 0) ?></p>
                </div>
                <div class="field">
                    <h4>Supplier </h4>
                    <p>: <?php echo $supplier ?></p>
                </div>
                <div class="field">
                    <h4>Alamat Supplier </h4>
                    <p>: <?php echo $alamat_supplier ?></p>
                </div>
            </div>
            <button id="delete" onclick="openMenu()">Hapus Obat</button>
            <div id="menu">
                <i class="fa-solid fa-xmark" id="close" onclick="closeMenu()"></i>

                <div class="verify">
                    <h3>Apakah yakin ingin menghapus <span><i><u><?php echo $nama_obat ?></u> </i></span> dari inventory ?</h3>
                    <a href="../pages/hapus_obat.php?id=<?php echo $kd_obat  ?>" class="yes">Iya</a>
                    <a onclick="closeMenu()" class="no">Tidak</a>
                </div>
            </div>


            <script>
                let menu = document.getElementById('menu')

                function openMenu() {
                    menu.style.display = 'inline'
                }

                function closeMenu() {
                    menu.style.display = 'none'
                }
            </script>
</body>

</html>