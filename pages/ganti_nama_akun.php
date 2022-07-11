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
if (isset($_POST['cancel'])) {
    header("location: ../pages/akun.php");
}
if (isset($_POST['update'])) {
    $nip_baru = $_SESSION['nip'];
    $nama_baru = htmlentities(strip_tags($_POST['nama_baru']));
    // jika role bukan super admin maka hanya bisa mengganti nama;
    $query = "UPDATE pengguna SET nama_pengguna='$nama_baru' where nip=$nip_baru";
    $update = mysqli_query($connection, $query);
    // header("location: ../pages/daftar_pengguna.php ");
}

$id = $_SESSION['nip'];

$getRole = mysqli_query($connection, "SELECT * FROM role");
$pengguna = mysqli_query($connection, "SELECT * from pengguna where nip=$id ");
while ($data_pengguna = mysqli_fetch_array($pengguna)) {
    $nip = $data_pengguna['nip'];
    $nama_pengguna = $data_pengguna['nama_pengguna'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="style/ganti_nama_akun.css?v=<?php echo time() ?>">
    <title>Edit Pengguna</title>
</head>

<body>
    <div class="container">
        <?php
        include('../component/navigation.php')
        ?>
        <div class="content" onclick=closeAkun()>
            <h2>Edit Pengguna</h2>
            <a href="../pages/akun.php">back</a>
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
            <form action="ganti_nama_akun.php" method="POST">
                <div class="fieldset">

                    <div class="field">
                        <h3>NIP :</h3>
                        <input disabled value="<?php echo $id ?>">
                    </div>
                    <div class="field">
                        <h3>Nama :</h3>
                        <input type=" text" name="nama_baru" value='<?php echo $nama_pengguna ?>'>
                    </div>

                    <div class="field">
                        <div class="menu">
                            <input type="submit" class="update" name="update" value="Update">
                            <br>
                            <input type="submit" class="cancel" name="cancel" value="Cancel">
                        </div>
                    </div>
                </div>

            </form>
        </div>



</body>

</html>