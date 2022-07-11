<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('../config.php');
session_start();
if (!isset($_SESSION['nama'])) {
    header('location: ../login.php');
}
// melakukan pengecekan role , jika role tidak sesuai akan diarahkan ke halaman dashboard.php
if ($_SESSION['role'] != 1) {
    header('location: ../pages/daftar_obat.php');
}
// mengambil data role
$getRole = mysqli_query($connection, "SELECT * FROM role order by id desc");

$err_msg = '';
$success_msg = '';
$nama = '';
$email = '';
$password = '';
if (isset($_POST['submit'])) {
    // ucfirst berguna untuk mengkapitalkan otomatis nama pengguna, jika pengguna mengisi dengan huruf kecil maka akan otomatis mejadi kapital pada huruf depan namanya
    $nama = ucfirst(htmlentities(strip_tags($_POST['nama'])));
    $email = htmlentities(strip_tags($_POST['email']));
    $password = htmlentities(strip_tags($_POST['password']));
    $role = $_POST['role'];

    // validasi
    if (empty($nama)) {
        $err_msg = 'Nama belum diisi';
    } else if (empty($email)) {
        $err_msg = 'Email belum diisi';
    } else if (empty($password)) {
        $err_msg = 'Password belum diisi';
    } else if (strlen($password) < 6) {
        $err_msg = 'Password kurang dari 6 huruf';
    } else     if (!is_numeric($role)) {
        $err_msg = 'Role belum dipilih';
    } else {
        // melakukan pengecekan email, apakah sudah ada didatabase?
        $checkUser = mysqli_query($connection, "SELECT * FROM pengguna where pengguna.email= '$email' ");
        // jika sudah ada munculkan email sudah terdaftar
        if (mysqli_num_rows($checkUser) == 1) {
            $err_msg =  "Email sudah terdaftar!";
        }
        // jika email belum terdaftar masukkan data kedalam databases
        else {
            // melakukan hashing pada password
            $passwordHash = password_hash($password, PASSWORD_DEFAULT);

            // insert to database
            mysqli_query($connection, "INSERT INTO pengguna set nama_pengguna ='$nama', email ='$email', password ='$passwordHash', role = '$role'");

            $success_msg = "Buat Akun berhasi!";
            $nama = '';
            $email = '';
            $password = '';
            $role = '';
        }
    }
} else {
    $err_msg = '';
    $nama = '';
    $email = '';
    $password = '';
    $role;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Account</title>
    <link rel="stylesheet" href="style/tambah_pengguna.css?v=<?php echo time() ?>">
</head>

<body>
    <div class="container">

        <?php
        include('../component/navigation.php')
        ?>

        <div class="content" onclick=closeAkun()>
            <h2> Tambah Akun</h2>
            <div class="history">
                <a href="../pages/daftar_pengguna.php"><i class="fas fa-book-user"></i> Daftar Pengguna</a>
            </div>
            <div id="message">
                <?php
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

            <form action="tambah_pengguna.php" method="POST">

                <div class="field">
                    <h4 class="header">Nama :</h4>
                    <input type="text" name="nama">
                </div>
                <div class="field">
                    <h4 class="header">Email :</h4>
                    <input type="email" name="email" value=<?php echo $email ?>>
                </div>
                <div class="field">
                    <h4 class="header">Password :</h4>
                    <input type="password" name="password" value=<?php echo $password ?>>
                </div>
                <div class="field">
                    <h4 class="header">Role :</h4>

                    <select name="role" id="pilih">
                        <option onclick="" id="default"> Pilih role </option>
                        <?php
                        while ($dataRole = mysqli_fetch_array($getRole)) {
                            echo "<option value=$dataRole[id] >$dataRole[nama_role] </option> ";
                        }

                        ?>
                    </select>
                </div>
                <div class="field">
                    <div class="button"><input type="submit" name="submit" value="Tambah Pengguna">
                    </div>
                </div>
            </form>

        </div>
    </div>
</body>


</html>