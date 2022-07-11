<?php

include_once('../config.php');
$nip = $_SESSION['nip'];

$user = mysqli_query($connection, "SELECT *FROM pengguna where nip=$nip");
while ($data_user = mysqli_fetch_array($user)) {
  $nama = $data_user['nama_pengguna'];
  // $fullname = explode(' ', $nama);
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Document</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <style>
    #info-container {
      display: none;
      background-color: var(--white);
      position: absolute;
      border: 1px solid var(--green);
      right: 0px;
      margin-top: 2rem;
      max-width: 50vw;
    }

    #info {
      display: flex;
      flex-direction: column;
      padding: 1rem;
      font-size: 2vh;
      overflow: hidden;
    }

    .info-user,
    .logout {

      margin: 0.2rem;
      text-decoration: none;
      border-radius: 10px;

    }

    .info-user {
      background-color: var(--green);
      color: var(--softWhite);
      padding: 15px;

    }

    .logout {
      background-color: red;
      color: black;
      padding: 15px;

    }

    /* desktop */
    #info-desktop {
      text-align: left;
      margin-top: 1rem;
      padding: 0 10%;
    }

    #info-desktop a {
      text-decoration: none;
    }


    .user {
      display: flex;
      flex-direction: row;
      justify-content: space-between;
    }

    .user a:hover {
      background-color: var(--green);
      color: var(--white);
    }

    .name-user {
      line-height: 1rem;
      font-size: 1rem;
      color: var(--fontColor);
      line-height: 1.5rem;
    }

    /* drop menu */
    #showDesktopMenu,
    #closeDesktopMenu {
      padding: 0 10px;
    }

    #showDesktopMenu {
      display: block;
    }

    #closeDesktopMenu {
      display: none;
    }

    #menu-logout {
      display: none;
    }

    .logout-button {
      padding-left: 1vh;
      margin-top: 1rem;

    }

    .logout-button a {
      color: black;
      font-size: 1rem;
      background-color: red;
      border: 1px solid red;
      padding: 10px;

    }
  </style>
</head>

<body>
  <div class="info-mobile">
    <div class="judul">
      <h5>SISTEM INVENTORY OBAT</h5>
    </div>
    <div class="user">
      <a id="button-akun" onclick=showAkun()><i class="fa fa-user" title="akun"></i></a>
      <div id="info-container">
        <div id="info">
          <a href="../pages/akun.php" class="info-user"> <?php echo substr($nama, 0, 14)  ?></a>
          <a href="../logout.php" class="logout"><i class="fas fa-sign-out"></i> Logout</a>
        </div>

      </div>
    </div>
  </div>
  <div class="navigation">
    <nav class="desktop">
      <div class="info-desktop">
        <div class="judul">
          <h5>SISTEM INVENTORY OBAT</h5>
        </div>
        <div id="info-desktop">
          <div></div>
          <div class="user">
            <a href="../pages/akun.php" class="name-user">
              <i class="fa fa-user" title="akun"></i> <?php echo substr($nama, 0, 14)  ?>
            </a>
            <a id="showDesktopMenu" onclick=showLogoutMenuDesktop()> <i class="fas fa-caret-down"></i> </a>
            <a id="closeDesktopMenu" onclick=closeLogoutMenuDesktop()><i class="fas fa-caret-up"></i></a>
          </div>
          <div id="menu-logout">
            <div class="logout-button">
              <a href="../logout.php">
                <i class="fas fa-sign-out"></i> Logout

              </a>

            </div>
          </div>
        </div>
        <ul>
          <li>
            <a href="../pages/dashboard.php?keyword=&halaman=1">
              <i class="fa-solid fa-table-columns"></i> Dashboard</a>
          </li>
          <li>
            <a href="../pages/daftar_obat.php?keyword=&halaman=1">
              <i class="fa-solid fa-book-medical"></i> Data Obat</a>
          </li>
          <li>
            <a href="../pages/tambah_obat.php">
              <i class="fa-solid fa-book-medical"></i> Tambah Obat</a>
          </li>
          <li>
            <a href="../pages/daftar_pengguna.php">
              <i class="fa-solid fa-address-book"></i> Data Pengguna</a>
          </li>
          <?php if ($_SESSION['role'] == 1) echo '
               <li>
            <a href="../pages/tambah_pengguna.php"><i class="fas fa-user-plus"></i> Tambah Pengguna</a>
            </li>            
            ';
          ?>

        </ul>
    </nav>
    <!-- pada tampilan mobile hanya terdapat menu dashboard -->
    <nav class="mobile">
      <ul>
        <li>
          <a href="../pages/dashboard.php">
            <i class="fa-solid fa-table-columns"></i></a>
        </li>
        <li>
          <a href="../pages/daftar_obat.php?keyword=&halaman=1"><i class="fa-solid fa-book-medical" title="daftar obat"></i></a>
        </li>
        <li>
          <a href="../pages/daftar_pengguna.php">
            <i class="fa-solid fa-address-book"></i></a>
        </li>
      </ul>
    </nav>
  </div>
  <script>
    let buttonAkun = document.getElementById('button-akun')
    let info = document.getElementById('info-container')

    function showAkun() {
      info.style.display = 'block';
    }

    function closeAkun() {
      info.style.display = 'none'
    }
    let containerMenuUser = document.getElementById('menu-logout')
    let buttonShowDesktopMenu = document.getElementById('showDesktopMenu')
    let buttonCloseDesktopMenu = document.getElementById('closeDesktopMenu')

    function showLogoutMenuDesktop() {
      containerMenuUser.style.display = "inline"
      buttonCloseDesktopMenu.style.display = "inline";
      buttonShowDesktopMenu.style.display = 'none'
    }

    function closeLogoutMenuDesktop() {
      containerMenuUser.style.display = "none"
      buttonCloseDesktopMenu.style.display = "none";
      buttonShowDesktopMenu.style.display = 'block'
    }
  </script>
</body>

</html>