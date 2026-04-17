<?php
include "koneksi.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $mata_kuliah = mysqli_real_escape_string($koneksi, $_POST['mata_kuliah']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $foto_name = '';
    if (!empty($_FILES['foto']['name'])) {
        $allowed = ['jpg','jpeg','png'];
        $ext = strtolower(pathinfo($_FILES['foto']['name'], PATHINFO_EXTENSION));
        if (!in_array($ext, $allowed)) {
            die('Format foto tidak diperbolehkan.');
        }
        if ($_FILES['foto']['size'] > 2 * 1024 * 1024) {
            die('Ukuran foto maksimal 2MB.');
        }
        if (!is_dir(__DIR__ . '/uploads')) mkdir(__DIR__ . '/uploads', 0755, true);
        $foto_name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . '/uploads/' . $foto_name);
    }

    $sql = "INSERT INTO yogi (nip, nama, mata_kuliah, alamat, foto) VALUES ('$nip','$nama','$mata_kuliah','$alamat','$foto_name')";
    if (mysqli_query($koneksi, $sql)) {
        header('Location: index.php');
        exit;
    } else {
        echo "Error: " . mysqli_error($koneksi);
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Tambah Data</title>
    <style>
        body {
            text-align: center;
            font-family: Arial;
            padding: 20px;
            background-color: #f4f4f4;
        }

        .container {
            max-width: 400px;
            background: #fff;
            padding: 20px;
            margin: auto;
            border-radius: 10px;
            box-shadow: 0 0 10px #ccc;
        }

        input[type=text],
        textarea {
            width: 90%;
            padding: 8px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        input[type=text]:focus,
        input[type=number]:focus,
        textarea:focus {
            outline: none;
            border-color: #007BFF;
        }

        button {
            padding: 10px;
            background-color: blue;
            color: #fff;
            border: none;
            border-radius: 5px;
            margin-top: 5px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h2>Tambah Data Dosen</h2>
        <form method="POST" enctype="multipart/form-data">
            <input type="text" name="nip" placeholder="NIP" required><br>
            <input type="text" name="nama" placeholder="Nama Dosen" required><br>
            <input type="text" name="mata_kuliah" placeholder="Mata Kuliah" required><br>
            <textarea name="alamat" placeholder="Alamat"></textarea><br>
            <br><input type="file" name="foto" accept="image/*"><br><br>
            <button type="submit" name="simpan">Simpan</button>
        </form>
    </div>

</body>

</html>