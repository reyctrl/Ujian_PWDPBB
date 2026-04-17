<?php
include "koneksi.php";
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$res = mysqli_query($koneksi, "SELECT * FROM yogi WHERE id = $id");
if (mysqli_num_rows($res) == 0) { header('Location: index.php'); exit; }
$data = mysqli_fetch_assoc($res);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nip = mysqli_real_escape_string($koneksi, $_POST['nip']);
    $nama = mysqli_real_escape_string($koneksi, $_POST['nama']);
    $mata_kuliah = mysqli_real_escape_string($koneksi, $_POST['mata_kuliah']);
    $alamat = mysqli_real_escape_string($koneksi, $_POST['alamat']);

    $foto_name = $data['foto'];
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
        $new_name = time() . '_' . bin2hex(random_bytes(4)) . '.' . $ext;
        move_uploaded_file($_FILES['foto']['tmp_name'], __DIR__ . '/uploads/' . $new_name);

        // hapus foto lama jika ada
        if (!empty($foto_name) && file_exists(__DIR__ . '/uploads/' . $foto_name)) {
            @unlink(__DIR__ . '/uploads/' . $foto_name);
        }
        $foto_name = $new_name;
    }

    $sql = "UPDATE yogi SET nip='$nip', nama='$nama', mata_kuliah='$mata_kuliah', alamat='$alamat', foto='$foto_name' WHERE id = $id";
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
    <title>Edit Data</title>
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
    <h2>Edit Data Dosen</h2>

    <form method="post" enctype="multipart/form-data">
        NIP:<br><input type="text" name="nip" value="<?= htmlspecialchars($data['nip']); ?>" required><br>
        Nama:<br><input type="text" name="nama" value="<?= htmlspecialchars($data['nama']); ?>" required><br>
        Mata Kuliah:<br><input type="text" name="mata_kuliah" value="<?= htmlspecialchars($data['mata_kuliah']); ?>" required><br>
        Alamat:<br><input type="text" name="alamat" value="<?= htmlspecialchars($data['alamat']); ?>"><br>
        Foto saat ini:<br>
        <?php if (!empty($data['foto']) && file_exists(__DIR__ . '/uploads/' . $data['foto'])): ?>
            <img src="uploads/<?= htmlspecialchars($data['foto']); ?>" style="width:120px"><br>
        <?php else: ?>
            -
        <?php endif; ?>
        <br><br><input type="file" name="foto" accept="image/*"><br><br>
        <button type="submit">Update</button>
    </form>
</div>
</body>
</html>