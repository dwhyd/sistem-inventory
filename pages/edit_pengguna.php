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
    $nip_baru = $_POST['nip_baru'];
    $nama_baru = htmlentities(strip_tags($_POST['nama_baru']));
    $role_baru = $_POST['role_baru'];
    // echo $nip_baru;


    // jika role bukan super admin maka hanya bisa mengganti nama;
    if ($_SESSION['role'] < 1) {
        $query = "UPDATE pengguna SET nama_pengguna='$nama_baru' where nip=$nip_baru";
    } else {
        $query = "UPDATE pengguna SET nama_pengguna='$nama_baru', role=$role_baru where nip=$nip_baru";
    }

    $update = mysqli_query($connection, $query);
    header("location: ../pages/daftar_pengguna.php ");
}

$id = $_GET['id'];

$getRole = mysqli_query($connection, "SELECT * FROM role");
$pengguna = mysqli_query($connection, "SELECT * from pengguna where nip=$id ");
while ($data_pengguna = mysqli_fetch_array($pengguna)) {
    $nip = $data_pengguna['nip'];
    $nama_pengguna = $data_pengguna['nama_pengguna'];
    $email = $data_pengguna['email'];
    $role = $data_pengguna['role'];
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
    <title>Edit Pengguna</title>
</head>

<body>
    <div class="container">
        <?php
        include('../component/navigation.php')
        ?>
        <div class="content" onclick=closeAkun()>
            <h2>Edit Pengguna</h2>
            <a href="../pages/daftar_pengguna.php">back</a>
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
            <form action="edit_pengguna.php" method="POST">
                <div class="fieldset">
                    <input type="hidden" name="nip_baru" value="<?php echo $_GET['id'] ?>">

                    <div class="field">
                        <h3>NIP :</h3>
                        <input disabled value="<?php echo $_GET['id'] ?>">
                    </div>
                    <div class="field">
                        <h3>Nama :</h3>
                        <input type=" text" name="nama_baru" value='<?php echo $nama_pengguna ?>'>
                    </div>
                    <div class="field">
                        <h3>Email :</h3>
                        <input type="email" disabled value='<?php echo $email ?>'>
                    </div>
                    <div class="field">
                        <h3>Role :</h3>
                        <select name="role_baru" id="role_baru">
                            <?php
                            while ($data_role = mysqli_fetch_array($getRole)) {

                                if ($data_role['id'] === $role) {
                                    echo " <option value=$data_role[id]  selected> $data_role[nama_role]</option> ";
                                } else {

                                    echo " <option value=$data_role[id]>$data_role[nama_role]</option>";
                                }
                            }
                            ?>
                        </select>
                    </div>

                    <div class="field">
                        <input type="submit" name="update" value="Update">
                    </div>
                </div>

            </form>
        </div>



</body>

</html>