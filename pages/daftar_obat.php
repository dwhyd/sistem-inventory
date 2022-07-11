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
// keyword

$keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
// bypass sqli manual
// if (preg_match('/or|and| /i', $keyword)) header('location: ../pages/daftar_obat.php?keyword=&halaman=1&cari=');


$keyword = mysqli_real_escape_string($connection, $keyword);

// pagination
$batas = 10;
$halaman = isset($_GET['halaman']) ? $_GET['halaman'] : 0;
$halaman_awal = ($halaman > 1) ? ($halaman * $batas) - $batas : 0;
$previous = $halaman - 1;
$next = $halaman + 1;
$allDataObat = mysqli_query($connection, "SELECT * FROM obat ");
$jumlahData = mysqli_num_rows($allDataObat);
$total_halaman = ceil($jumlahData / $batas);
$nomor = 1;

if (strlen(isset($_GET['keyword'])) > 0) {
  $allDataObat = mysqli_query($connection, "SELECT * FROM daftar_obat  where nama_obat LIKE '%$keyword%' order by daftar_obat.kd_obat asc ");
  $jumlahData = mysqli_num_rows($allDataObat);
  $query = "SELECT * FROM daftar_obat  where nama_obat LIKE '%$keyword%' order by daftar_obat.kd_obat asc LIMIT $halaman_awal,$batas ";
} else {
  $query = "SELECT * FROM daftar_obat order by daftar_obat.kd_obat asc LIMIT $halaman_awal,$batas ";
}

if (isset($_GET['reset'])) {
  header("location: daftar_obat.php?keyword=&halaman=1&cari=");
}
$info_msg = '';
// kode pencarian
if (isset($_GET['cari'])) {

  // mengambil data keyword
  $keyword = htmlentities(strip_tags($_GET['keyword']));
  $halaman  = 1;

  // melakukan pengecekan keyword
  // jika keyword kosong maka akan menampilkan semua produk
  if (strlen($keyword) <= 0) {
    $info_msg = "Masukkan kata kunci";
    // $get = "location: ../pages/daftar_obat.php?keyword=&halaman=1";
    // header($get);
  }
  // jika terdapat keyword maka melakukan pencarian produk dengan keyword tersebut
  else if ($keyword > 0) {
    // cek total data dengan kata kunci
    $totalData = mysqli_query($connection, "SELECT * FROM obat where nama_obat  LIKE '%$keyword%' ");
    // tampilkan data dengan kata kunci dengan limit
    $query = "SELECT * FROM daftar_obat where nama_obat LIKE '%$keyword%' order by daftar_obat.kd_obat asc LIMIT $halaman_awal,$batas";
    $obat = mysqli_query($connection, $query);

    $jumlahDataObat = mysqli_num_rows($totalData);
    $total_halaman = ceil($jumlahData / $batas);
    $info_msg = "Hasil pencarian : <u><i> " . $keyword . "</u></i>";
    $get = "location: ../pages/daftar_obat.php?keyword=$keyword&halaman=1";
    header($get);
  }
} else {
  $keyword = isset($_GET['keyword']) ? $_GET['keyword'] : '';
}
if ($keyword > 1) {
  $info_msg = "Hasil pencarian : <u><i> " . $keyword . "</u></i>";
}
$obat = mysqli_query($connection, $query);
if ($jumlahData == 0) {
  $info_msg = "Data <u><i>" . $keyword . "</i></u> tidak ada";
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Data Obat</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
  <link rel="stylesheet" href="style/daftar_obat.css?v=<?php echo time() ?>" />

</head>

<body>
  <div class="container">
    <?php

    include('../component/navigation.php');
    ?>
    <div class="content" onclick=closeAkun()>
      <h2>Data Obat</h2>
      <form action="daftar_obat.php?halaman=1" method="get">
        <div class="cari">
          <input type="text" class="keyword" name="keyword" placeholder="ketik nama obat" value="<?php echo $keyword ?>">
          <button name="cari" title="cari">
            <i class="fa fa-search" aria-hidden="true"></i>
          </button>

          <?php
          if (!empty($keyword)) {
            echo '   <button name="reset" value="reset">
                <i class="fa fa-times" aria-hidden="true"></i>
            </button>';
          }
          ?>
        </div>
      </form>
      <?php
      // if ($info_msg != '') {
      echo " <div class='info'> <strong> $info_msg</strong></div>";
      // }
      ?>

      <?php
      // if ($jumlahData) {
      echo " <div class='jumlah'> <strong> Jumlah data : $jumlahData</strong></div>";
      // }
      ?>
      <div class="data">
        <table border="1">
          <thead>
            <tr>
              <th>Nomor</th>
              <th>Kode Obat</th>
              <th>Nama Obat </th>
              <th>Sediaan</th>
              <th>Kekuatan</th>
              <th>Satuan</th>
              <th>Stok</th>
              <th>Harga</th>
              <th>Menu</th>

            </tr>
          </thead>
          <tbody>
            <?php

            while ($data_obat = mysqli_fetch_array($obat)) {

              echo "<tr>";
              echo "<td>" . $nomor++ . "</td>";
              echo "<td>" . $data_obat['kd_obat'] . "</td>";
              echo "<td>" . $data_obat['nama_obat'] . "</td>";
              echo "<td>" . $data_obat['sediaan'] . "</td>";
              echo "<td>" . $data_obat['kekuatan'] . "</td>";
              echo "<td>" . $data_obat['satuan'] . "</td>";
              echo "<td>" . $data_obat['stok'] . "</td>";
              echo "<td> Rp. " . number_format($data_obat['harga'], 0) . "</td>";
              echo    "<td class='menu'> ";
              echo "<a href='../pages/detail_obat.php?id=$data_obat[kd_obat]'><i class='fa fa-info-circle' aria-hidden='true' title='detail obat'></i></a>";
              echo "<a href='../pages/edit_obat.php?id=$data_obat[kd_obat]'><i class='fa-solid fa-pencil'></i></a>";
              echo "</td>";
              echo "</tr>";
            }
            ?>
          </tbody>
        </table>
      </div>
      <div class="pagination">
        <a <?php if ($halaman > 1) {
              echo "class='previous'";
              echo "href='?keyword=$keyword&halaman=$previous '";
            } else {
              echo 'class="previous hidden"';
            } ?>>
          < </a>
            <?php
            if ($jumlahData > 0) {
              for ($x = 1; $x <= $total_halaman; $x++) {

                if ($x == $halaman) {
                  echo "<li class='current-page'>";
                  echo " <a   href='?keyword=$keyword&halaman=$x'> $x </a>";
                  echo ' </li>';
                } else {
                  echo "<li>";
                  echo " <a  href='?keyword=$keyword&halaman=$x'> $x </a>";
                }
                echo ' </li>';
              }
            }
            if ($halaman < 1) {
              $halaman = 1;
              $next + 1;
            }
            if (empty($halaman)) {
              $next = $next + 1;
            }
            if ($halaman == $next + 1) {
              echo "s";
            }
            ?>

            <a <?php
                if ($halaman < $total_halaman) {

                  echo 'class="next"';
                  echo "href='../pages/daftar_obat.php?keyword=$keyword&halaman=$next'";
                } else {
                  echo 'class="next hidden"';
                }

                ?>>></a>
      </div>
    </div>
  </div>
</body>

</html>