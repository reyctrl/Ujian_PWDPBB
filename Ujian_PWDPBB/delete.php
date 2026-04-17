<?php
include "koneksi.php";
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$res = mysqli_query($koneksi, "SELECT foto FROM yogi WHERE id = $id");
if ($row = mysqli_fetch_assoc($res)) {
    if (!empty($row['foto']) && file_exists(__DIR__ . '/uploads/' . $row['foto'])) {
        @unlink(__DIR__ . '/uploads/' . $row['foto']);
    }
    mysqli_query($koneksi, "DELETE FROM yogi WHERE id = $id");
}
header('Location: index.php');
exit;
?>