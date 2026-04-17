<?php
$user = "root";
$pass = "150620";
$host = "localhost";
$db = "uas_xi_pplg_1_yogi";

$koneksi = mysqli_connect($host, $user, $pass, $db);
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}
?>