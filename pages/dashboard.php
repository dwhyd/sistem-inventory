<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('../config.php');

session_start();
// melakukan pengecekan pada session apakah ada session bernama email
if (
    !isset($_SESSION['nama'])
    && !isset($_SESSION['nama']) && !isset($_SESSION['role'])
) {
    header('location: ../login.php');
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Dashboard</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="style/dashboard.css?v=<?php echo time() ?>" />

</head>

<body>
    <div class="container">
        <?php

        include('../component/navigation.php');
        ?>
        <div class="content" onclick=closeAkun()>
            <div class="menu">
                <a href="../pages/daftar_obat.php?keyword=&halaman=1" class="btn ex1">Data obat</a>
                <a href="../pages/daftar_pengguna.php" class="btn ex6">Data Pengguna</a>
                <a href="../pages/tambah_obat.php" class="btn ex2">Tambah Obat Baru</a>
                <a href="../pages/tambah_stok_obat.php" class="btn ex3">Input Faktur</a>
                <a href="../pages/riwayat_barang_masuk.php" class="btn ex4">Riwayat Barang Masuk</a>
                <a href="../pages/tambah_pengguna.php" class="btn ex5">Tambah Pengguna</a>
            </div>
        </div>
    </div>
</body>

</html>