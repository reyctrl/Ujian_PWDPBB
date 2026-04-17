<?php include "koneksi.php"; ?>

<!DOCTYPE html>
<html>

<head>
    <title>Data Dosen</title>
    <style>
        body {
            font-family: Arial;
            padding: 20px;
            text-align: center
        }

        table {
            border-collapse: collapse;
            width: 100%;
        }

        th,
        td {
            border: 1px solid #aaa;
            padding: 10px;
        }

        a {
            padding: 6px 10px;
            background: blue;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }

        form {
            margin-bottom: 15px;
        }

        input[type=text] {
            padding: 10px;
            width: 200px;
            border-radius: 5px;
            border: 1px solid #aaa;
        }

        input[type=text]:focus {
            border: 1px solid #27F5e7;
            outline: none;
        }
        
        .edit{
            background-color: orange;
        }

        .delete{
            background-color: red;
        }

        .edit,
        .delete {
            padding: 6px 10px;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
            margin-right: 10px;
        }

        button{
            padding: 6px 10px;
            background-color: blue;
            color: #fff;
            border: none;
            border-radius: 5px;
        }

        img.thumb{ width:80px; height:auto; border-radius:4px; }
    </style>
</head>

<body>

    <h2>Data Dosen</h2>

    <a href="create.php">Tambah Data</a>
    <br><br>

    <form method="GET">
        <input type="text" name="cari" placeholder="Cari nama dosen...">
        <button type="submit">Cari</button>
    </form>

    <table>
        <tr>
            <th>ID</th>
            <th>NIP</th>
            <th>Nama</th>
            <th>Mata Kuliah</th>
            <th>Alamat</th>
            <th>Foto</th>
            <th>Aksi</th>
        </tr>

        <?php
        $cari = isset($_GET['cari']) ? mysqli_real_escape_string($koneksi, $_GET['cari']) : '';

        if ($cari != '') {
            $data = mysqli_query($koneksi, "SELECT * FROM yogi WHERE nama LIKE '%$cari%'");
        } else {
            $data = mysqli_query($koneksi, "SELECT * FROM yogi");
        }

        while ($d = mysqli_fetch_assoc($data)) {
            ?>
            <tr>
                <td><?= htmlspecialchars($d['id']); ?></td>
                <td><?= htmlspecialchars($d['nip']); ?></td>
                <td><?= htmlspecialchars($d['nama']); ?></td>
                <td><?= htmlspecialchars($d['mata_kuliah']); ?></td>
                <td><?= htmlspecialchars($d['alamat']); ?></td>
                <td>
                    <?php if (!empty($d['foto']) && file_exists(__DIR__ . '/uploads/' . $d['foto'])): ?>
                        <img src="uploads/<?= htmlspecialchars($d['foto']); ?>" class="thumb" alt="Foto">
                    <?php else: ?>
                        -
                    <?php endif; ?>
                </td>
                <td>
                    <a href="edit.php?id=<?= $d['id']; ?>" class="edit">Edit</a>
                    <a href="delete.php?id=<?= $d['id']; ?>" class="delete" onclick="return confirm('Hapus data?')">Hapus</a>
                </td>
            </tr>
        <?php } ?>
    </table>

</body>

</html>