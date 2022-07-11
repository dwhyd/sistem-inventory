<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
session_start();
if (!isset($_SESSION['nama'])) {
    header('location: ../login.php');
}
include_once('../config.php');
$nip = $_SESSION['nip'];
$dataUser = mysqli_query($connection, "SELECT * FROM pengguna where nip=$nip")
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Akun</title>
    <link rel="stylesheet" href="style/akun.css?v=<?php echo time() ?>">
</head>

<body>
    <div class="container">

        <!-- import komponen Navigation -->
        <?php
        include('../component/navigation.php')
        ?>

        <div class="content" onclick=closeAkun()>

            <h2>Akun</h2>
            <div class="fieldset">

                <?php
                while ($data_user = mysqli_fetch_array($dataUser)) {
                ?>
                    <div class="field
                ">
                        <h4>NIP :</h4>
                        <p><?php echo $data_user['nip'] ?> </p>
                    </div>
                    <div class="field">
                        <h4>Nama :</h4>
                        <p><?php echo $data_user['nama_pengguna'] ?> </p>
                    </div>
                    <div class="field">
                        <h4>Email: </h4>
                        <p><?php echo $data_user['email'] ?> </p>
                    </div>
                <?php
                } ?>
            </div>
            <div class="menu">
                <a href="../pages/ganti_nama_akun.php">
                    <i class='fa-solid fa-pencil'></i> Edit
                </a>
            </div>
        </div>
</body>

</html>