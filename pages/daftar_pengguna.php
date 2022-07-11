<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

include_once('../config.php');

session_start();
// melakukan pengecekan pada session apakah ada session bernama email
if (!isset($_SESSION['nama'])) {
    header('location: ../login.php');
}
if ($_SESSION['role'] > 2) {
    header('location: ../pages/daftar_obat.php');
}
$jumlahData;
$keyword = "";
$nomor = 1;
$query = "SELECT * FROM pengguna ORDER BY `pengguna`.`nip` asc";
$info_msg = '';
date_default_timezone_set('Asia/Jakarta');
// kode pencarian
if (isset($_POST['cari'])) {

    // mengambil data keyword
    $keyword = htmlentities(strip_tags($_POST['keyword']));

    // melakukan pengecekan keyword
    // jika keyword kosong maka akan menampilkan semua produk
    if (strlen($keyword) <= 0) {
        $info_msg = "Masukkan kata kunci";
    }
    // jika terdapat keyword maka melakukan pencarian produk dengan keyword tersebut
    else if ($keyword > 0) {
        // kondisi urutkan harga
        /*menambahakn % sebelum dan sesudah keyword untuk mencari obat yang mengandung kata keyword tersebut. 
        misal %paracetamol% , akan menampilkan nama obat yang mengandung  para
        */
        $keyword = mysqli_real_escape_string($connection, $keyword);
        $query = "SELECT * FROM pengguna where nama_pengguna LIKE '%$keyword%'";
        $info_msg = "Menampilkan hasil pencarian : " . $keyword;
    }
} else {
    $keyword = '';
}
$pengguna = mysqli_query($connection, $query);

$jumlahData = mysqli_num_rows($pengguna);
if ($jumlahData <= 0) {
    $info_msg = "Data <u><i>" . $keyword . "</i></u> tidak ada";
}
$keyword = mysqli_real_escape_string($connection, $keyword);
?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Data Pengguna</title>
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Roboto&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" />
    <link rel="stylesheet" href="style/daftar_obat.css?v=<?php echo time() ?>" />
</head>

<body>
    <div class="container">
        <?php
        include('../component/navigation.php')
        ?>

        <div class="content" onclick=closeAkun()>
            <h2>Data Pengguna</h2>
            <form action="daftar_pengguna.php" method="POST">
                <div class="cari">
                    <input type="text" class="keyword" name="keyword" placeholder="ketik nama" value="<?php echo $keyword ?>">
                    <button name="cari">
                        <i class="fa fa-search" aria-hidden="true"></i>
                    </button>

                    <?php
                    if (!empty($keyword)) {
                        echo '   <button name="reset" value="reset"><i class="fa fa-times" aria-hidden="true"></i></button>';
                    }
                    ?>
                </div>
            </form>
            <?php
            if ($info_msg != '') {
                echo " <div class='info'> <strong> $info_msg</strong></div>";
            }
            ?>

            <?php
            if ($jumlahData) {
                echo " <div class='jumlah'> <strong> Jumlah data : $jumlahData</strong></div>";
            }
            ?>
            <div class="data">
                <table border="1">
                    <thead>
                        <tr>
                            <th>NIP</th>
                            <th>Nama</th>
                            <th>Email </th>
                            <th>Role</th>
                            <?php
                            if ($_SESSION['role'] <= 2) {
                                echo " <th>Menu</th>";
                            }
                            ?>

                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        while ($data_pengguna = mysqli_fetch_array($pengguna)) {
                            echo "<tr>";
                            echo "<td>" . $data_pengguna['nip'] . "</td>";
                            echo "<td>" . $data_pengguna['nama_pengguna'] . "</td>";
                            echo "<td>" . $data_pengguna['email'] . "</td>";
                            echo "<td>" . $data_pengguna['role'] . "</td>";

                            if ($_SESSION['role'] <= 2) {
                                echo    "<td class='menu'> ";
                                echo "<a href='../pages/edit_pengguna.php?id=$data_pengguna[nip]' disabled><i class='fa-solid fa-pencil'></i></a>";
                                echo "</td>";
                            }

                            echo "</tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
            <!-- end -->
        </div>
    </div>
</body>

</html>