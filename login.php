<?php
// untuk menampilkan error yang lebih spesifik
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// memanggil konfigurasi koneksi database ke php
include_once('config.php');
$email = '';
$password = '';
// mengambil data form
if (isset($_POST['submit'])) {
  // htmlentites & strip_tags berguna untuk menanggkal XSS dan HTML injection agar semua elemen html berubah menjadi string
  $email = htmlentities(strip_tags($_POST['email']));
  $password = htmlentities(strip_tags($_POST['password']));

  $err_msg = '';

  // validasi jika email kosong
  if (empty($email)) {
    $err_msg = 'Email belum diisi';
  } else if (empty($password)) {
    $err_msg = 'Password belum diisi';
  } else if (empty($email)  && empty($password)) {
    $err_msg = 'Email & Password belum diisi';
  } else {
    // cek ke database;

    $login = mysqli_query($connection, "SELECT * from pengguna where email= '{$email}'");

    // menampilkan jumlah data yang ada
    // jika email tidak ada pada database maka tampilkan email & password salah
    if (mysqli_num_rows($login) === 0) {
      $err_msg = "Email & Password salah";
      $email = '';
    } else {

      while ($dataUser = mysqli_fetch_array($login)) {
        //   lakukan verifikasi terhadap password jika sama jalankan session dan setting session
        if (password_verify($password, $dataUser['password'])) {
          // $err_msg = 'password is valid';
          session_start();
          $_SESSION['nip'] = $dataUser['nip'];
          $_SESSION['nama'] = $dataUser['nama_pengguna'];
          $_SESSION['email'] = $email;
          $_SESSION['role'] = $dataUser['role'];
          // alihkan menuju dashboard
          // header('location: ../sio/pages/dashboard/dashboard.php');
          header('location: pages/dashboard.php');
        } else {
          $err_msg = "Email & Password salah";
          $email = '';
          $password = '';
        }
      }
    }
  }
} else {
  // jika tidak ada data maka kosongkan form 
  $err_msg = '';
  $email = '';
  $password = '';
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link rel="stylesheet" href="style/login.css?v=<?php echo time() ?>" />
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
  <title>Login</title>
</head>

<body>
  <noscript>
    <h5>
      JavaScript harus diaktifkan agar Anda dapat menggunakan Gmail dalam
      tampilan standar. Namun, tampaknya JavaScript dinonaktifkan atau tidak
      didukung oleh browser Anda. Untuk menggunakan tampilan standar, aktifkan
      JavaScript dengan mengubah opsi browser, kemudian
      <a href="index.php"> coba lagi</a>.
    </h5>
    <style>
      div {
        display: none;
      }
    </style>
  </noscript>
  <div class="container">
    <div class="form">
      <h2>SISTEM INVENTORY OBAT</h2>
      <div class="error">
        <?php
        if ($err_msg != '') {
          echo " <p> $err_msg</p>";
        }
        ?>
      </div>

      <form action="login.php" method="POST">
        <div class="field">
          <label for="email">Email : </label>
          <input type="email" name="email" id="email" value="<?php echo $email ?>" />
        </div>

        <div class="field">
          <label for="password">Password : </label>
          <input type="password" name="password" id="password" />
        </div>

        <br />
        <div class="submit">
          <input type="submit" name="submit" value="Log in" />
        </div>
      </form>
    </div>
    <div class="image">
      <img src="assets/image_side.svg" alt="image side login" />
    </div>
  </div>
</body>

</html>