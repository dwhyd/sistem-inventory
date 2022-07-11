<?php

$DB_HOST = 'localhost';
$DB_USERNAME = 'root';
$DB_PASSWORD = '';



$connection = mysqli_connect($DB_HOST, $DB_USERNAME, $DB_PASSWORD);

// if ($mysqli) {
//     echo "database connect";
// } else {
//     echo "database not connect";
// }
// membuat database
if (!$connection) {
    die("Koneksi dengan database gagal: " . mysqli_connect_errno() . " - " . mysqli_connect_error());
}
$query = "DROP DATABASE sistem_inventory";
mysqli_query($connection, $query);

$query = "CREATE DATABASE IF NOT EXISTS sistem_inventory";
$hasil_query = mysqli_query($connection, $query);

if (!$hasil_query) {
    die("Query Error: " . mysqli_errno($connection) . " - " . mysqli_error(($connection)));
} else {
    echo "Database Sistem Inventory dibuat <br>";
}

// memilih database

$result = mysqli_select_db($connection, "sistem_inventory");

if (!$result) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "Database sistem inventory berhasil dipilih<br>";
}
// hapus table role jika ada
$query = "DROP TABLE IF EXISTS role";
$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "Tabel <b> role </b> berhasil dihapus<br>";
}

// membuat tabel role baru
$query = "CREATE TABLE role (id int(11) not null primary key auto_increment, nama_role varchar(15) not null )";
$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "Tabel <b> role </b> berhasil dibuat<br>";
}


// menghapus tabel pengguna
$query = "DROP TABLE  IF EXISTS pengguna";
$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "Tabel <b> pengguna </b> berhasil dihapus<br>";
}
// membuat tabel pengguna baru
$query = "CREATE TABLE pengguna (
    nip int(10)  not null primary key auto_increment , nama_pengguna varchar(50), email varchar(50), password varchar(100), role int(11), FOREIGN KEY (role) REFERENCES role (id) ) ";
$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "Tabel <b> pengguna </b> berhasil dibuat<br>";
}


// Tabel satuan_obat
$query = "CREATE TABLE satuan_obat ( id int(15) not null auto_increment primary key, satuan varchar(20) not null) ";

$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "Tabel <b> satuan_obat </b> berhasil ditambahkan<br>";
}

// Tabel satuan_obat
$query = "CREATE TABLE sediaan_obat ( id int(15) not null auto_increment primary key, sediaan varchar(20) not null) ";

$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "Tabel <b> sediaan_obat </b> berhasil ditambahkan<br>";
}

// Tabel supplier
$query = "CREATE TABLE supplier ( kd_supplier int(11) not null auto_increment primary key, nama_supplier varchar(100) not null, alamat varchar(100) not null) ";

$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "Tabel <b> supplier </b> berhasil ditambahkan<br>";
}

// Tabel obat
$query = "CREATE TABLE obat (
    kd_obat int not null primary key auto_increment,
    nama_obat varchar(100),
    id_sediaan_obat int,
    kekuatan int,
    id_satuan_obat int,
    stok int,
    harga int,
    kd_supplier int,
    FOREIGN KEY (id_sediaan_obat) REFERENCES sediaan_obat (id),
    FOREIGN KEY (id_satuan_obat) REFERENCES satuan_obat (id),
    FOREIGN KEY (kd_supplier) REFERENCES supplier (kd_supplier)
    ) ";

$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "Tabel <b> obat </b> berhasil ditambahkan<br>";
}

// Tabel barang_masuk
$query = "CREATE TABLE barang_masuk (
    id int not null primary key AUTO_INCREMENT,
    kd_faktur varchar(100),
    kd_obat int,
    stok_baru int,
    tanggal datetime,
    kd_supplier int,
    diterima varchar(50),
    FOREIGN KEY (kd_obat) REFERENCES obat (kd_obat),
    FOREIGN KEY (kd_supplier) REFERENCES supplier (kd_supplier)
    )";

$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "Tabel <b> barang_masuk </b> berhasil ditambahkan<br>";
}
echo "membuat view.....<br>";
//membuat view
// View daftar_obat
$query = "CREATE VIEW daftar_obat AS SELECT obat.kd_obat, obat.nama_obat, sediaan_obat.sediaan, obat.kekuatan, satuan_obat.satuan, obat.stok, obat.harga 
from obat
JOIN sediaan_obat
ON sediaan_obat.id = obat.id_sediaan_obat
JOIN satuan_obat
ON satuan_obat.id = obat.id_satuan_obat";

$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "View <b> daftar obat </b> berhasil ditambahkan<br>";
}

// view detail_obat
$query = "CREATE VIEW  detail_obat AS SELECT obat.kd_obat, obat.nama_obat, sediaan_obat.sediaan, obat.kekuatan, satuan_obat.satuan, obat.stok, obat.harga, supplier.nama_supplier, supplier.alamat
from obat
JOIN sediaan_obat
ON sediaan_obat.id = obat.id_sediaan_obat
JOIN satuan_obat
ON satuan_obat.id = obat.id_satuan_obat
JOIN supplier
ON supplier.kd_supplier = obat.kd_supplier";

$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "View <b> detail obat </b> berhasil ditambahkan<br>";
}

// view riwayat_barang_masuk
$query = "CREATE VIEW riwayat_barang_masuk AS SELECT barang_masuk.tanggal,  barang_masuk.kd_faktur, obat.kd_obat, obat.nama_obat, barang_masuk.stok_baru, supplier.nama_supplier, pengguna.nama_pengguna
from barang_masuk
JOIN obat
ON obat.kd_obat = barang_masuk.kd_obat
JOIN supplier
ON supplier.kd_supplier = barang_masuk.kd_supplier
JOIN pengguna
ON pengguna.nip =barang_masuk.diterima";

$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "View <b> riwayat_barang_masuk </b> berhasil ditambahkan<br>";
}
echo "input data.....<br>";

//input data role 
$query = "INSERT INTO role VALUES 
( 1,'super admin' ), 
(2, 'admin'),
(3, 'guest')";
$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "Data <b> role </b> berhasil diinput<br>";
}

// menginput ke tabel pengguna sebagai akun utama
$password = "admin";
$hash = password_hash($password, PASSWORD_DEFAULT);

$query = "INSERT INTO pengguna VALUES ( null, 'John', 'john@email.com', '$hash', 1 )";

$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "Akun <b> John </b> berhasil ditambahkan<br>";
}

//input data satuan_obat 
$query = "INSERT INTO `satuan_obat` (`id`, `satuan`) VALUES
(1, 'mcg/jam'),
(2, 'mg/ml'),
(3, 'ml'),
(4, 'mg'),
(5, '%'),
(6, 'mgg/puff'),
(7, 'IU/ml')";
$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "Data <b> satuan_obat </b> berhasil diinput<br>";
}


//input data sediaan_obat 
$query = "INSERT INTO `sediaan_obat` (`id`, `sediaan`) VALUES
(1, 'tab'),
(2, 'injeksi'),
(3, 'kapsul'),
(4, 'spray'),
(5, 'patch'),
(6, 'larutan/oral'),
(7, 'ig/gas dalam tabung'),
(8, 'aerosol'),
(9, 'tablet salut selaput'),
(10, 'susp'),
(11, 'tablet lepas lambat')";
$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "Data <b> sediaan_obat </b> berhasil diinput<br>";
}

//input data supplier
$query = "INSERT INTO `supplier` (`kd_supplier`, `nama_supplier`, `alamat`) VALUES
(10, 'PT Abbot Indonesia', 'Jakarta'),
(11, 'PT Aditama Raya Farmindo', 'Jawa Timur'),
(12, 'PT Afiat', 'Jawa Barat'),
(13, 'PT Afifarma', 'Jawa Timur'),
(14, 'PT ASTA Medica, Transfarma Medica Indah', 'Jakarta'),
(15, 'PT Bima Mitra Farma', 'Jakarta'),
(16, 'PT Bio Farma', 'Bandung'),
(17, 'PT Bristol Myers Squibb Indonesia Tbk', 'Jakarta'),
(18, 'PT Bromo Pharmaceutical Industries', 'Jakarta'),
(19, 'PT Bufa Aneka', 'Semarang'),
(20, 'PT Aventis', 'Jakarta'),
(26, 'PT. Ciubros Farma', 'Jawa Tengah'),
(27, 'PT. Combined Imperial Pharmaceuticals, Inc (Combiphar)', 'Jawa Barat'),
(28, 'PT. Daewoong Infion', 'Jawa Timur'),
(29, 'PT. Dankos Farma', 'Jakarta'),
(30, 'PT. Darya-Varia Laboratoria Tbk', 'Jawa Barat')";
$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "Data <b> supplier </b> berhasil diinput<br>";
}


//input data obat 
$query = "INSERT INTO `obat` (`kd_obat`, `nama_obat`, `id_sediaan_obat`, `kekuatan`, `id_satuan_obat`, `stok`, `harga`, `kd_supplier`) VALUES
(11, 'Hydromorphone', 11, 8, 2, 50, 25000, 26),
(12, 'Morphine', 1, 10, 4, 20, 50000, 19),
(13, 'Oxycodone', 11, 10, 4, 25, 100000, 17),
(14, 'Pethidine', 2, 50, 2, 10, 70000, 10),
(15, 'Ketolorac', 2, 30, 2, 20, 50000, 28),
(16, 'Pregablin', 3, 75, 4, 50, 50000, 27),
(17, 'paracetamol', 1, 500, 4, 14, 90000, 17),
(18, 'Fentanyl', 5, 25, 1, 10, 100000, 20),
(19, 'Ibuprofen cair', 6, 50, 3, 20, 90000, 16),
(20, 'ibuprofen', 1, 500, 2, 100, 14000, 30)";

$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "Data <b> obat </b> berhasil diinput<br>";
}


// Trigger
// membuat trigger update stok obat dari tabel barang masuk
$query = "CREATE TRIGGER update_stok_obat AFTER INSERT ON barang_masuk FOR EACH ROW 
update obat SET stok = stok + new.stok_baru where kd_obat = new.kd_obat";
$hasil_query = mysqli_query($connection, $query);
if (!$hasil_query) {
    die("Query error : " . mysqli_errno($connection)) . " - " . mysqli_error($connection);
} else {
    echo "Membuat  <b> Trigger barang masuk </b> berhasil <br>";
}

echo "menuju halaman login....";
header('location: index.php');
