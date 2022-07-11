<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$id = isset($_GET['id']) ? $_GET['id'] : header('location: location: ../pages/daftar_obat.php ');
echo $id;
include_once('../config.php');

session_start();
if ($_SESSION['role'] > 2) {
    header('location: ../pages/daftar_obat.php');
}
$hasil_query = mysqli_query($connection, "DELETE FROM obat WHERE kd_obat= $id");

if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    header('location: ../pages/daftar_obat.php?keyword=&halaman=1');
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hapus Obat</title>
</head>

<body>
    <p>berhasil</p>

</body>

</html>