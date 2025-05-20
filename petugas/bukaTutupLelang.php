<?php
session_start();
if (!isset($_SESSION['nama'])) {
    header("Location: ../login/login.php");
    exit();
}

$koneksi = mysqli_connect("localhost", "root", "", "lelang_online");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

if (isset($_GET['aksi']) && $_GET['aksi'] == 'hapus') {
    $id = $_GET['id_lelang'];
    mysqli_query($koneksi, "DELETE FROM history_lelang WHERE id_lelang = $id");
    mysqli_query($koneksi, "DELETE FROM tb_lelang WHERE id_lelang = $id");
    echo "<script>alert('Data lelang berhasil dihapus'); window.location='bukaTutupLelang.php';</script>";
}

// Buka atau tutup lelang
if (isset($_GET['aksi']) && isset($_GET['id_lelang'])) {
    $id = $_GET['id_lelang'];
    $status = $_GET['aksi'] == 'buka' ? 'dibuka' : 'ditutup';
    mysqli_query($koneksi, "UPDATE tb_lelang SET status = '$status' WHERE id_lelang = $id");
}

// Tambah barang ke lelang
if (isset($_POST['submit'])) {
    if (isset($_POST['id_barang']) && is_array($_POST['id_barang'])) {
        $id_petugas = $_SESSION['id_petugas'];
        $tgl = date('Y-m-d');
        $harga_akhir = 0;
        $status = 'dibuka';
        $id_user = NULL;

        foreach ($_POST['id_barang'] as $id_barang) {
            $cek = mysqli_query($koneksi, "SELECT * FROM tb_lelang WHERE id_barang = $id_barang");
            if (mysqli_num_rows($cek) == 0) {
                $stmt = mysqli_prepare($koneksi, "INSERT INTO tb_lelang (id_barang, tgl_lelang, harga_akhir, id_petugas, status, id_user) VALUES (?, ?, ?, ?, ?, ?)");
                mysqli_stmt_bind_param($stmt, "isiisi", $id_barang, $tgl, $harga_akhir, $id_petugas, $status, $id_user);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
            }
        }

        echo "<script>alert('Barang berhasil dimasukkan ke lelang!'); window.location='bukaTutupLelang.php';</script>";
    } else {
        echo "<script>alert('Tidak ada barang yang dipilih!');</script>";
    }
}



// Ambil barang yang belum dilelang
$barang = mysqli_query($koneksi, "SELECT * FROM tb_barang WHERE id_barang NOT IN (SELECT id_barang FROM tb_lelang)");

// Ambil semua data lelang
$lelang = mysqli_query($koneksi, "
    SELECT tb_lelang.*, tb_barang.nama_barang,
           (SELECT COUNT(*) FROM history_lelang WHERE history_lelang.id_lelang = tb_lelang.id_lelang) AS jumlah_penawar
    FROM tb_lelang 
    JOIN tb_barang ON tb_lelang.id_barang = tb_barang.id_barang
    ORDER BY jumlah_penawar DESC, tb_lelang.id_lelang DESC
");

?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kelola Lelang - Petugas</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body {
            display: flex;
            min-height: 100vh;
            background-color: #e6e6e6;
            margin: 0;
        }

        .sidebar {
            width: 250px;
            background-color: #f4f4f4;
            border-right: 1px solid #ccc;
            padding: 20px 0;
            position: fixed;
            top: 0;
            left: 0;
            height: 100vh;
        }

        .sidebar h2 {
            text-align: center;
            color: white;
            background-color: #2a7fc1;
            padding: 15px 0;
        }

        .sidebar a {
            display: block;
            padding: 10px 20px;
            color: #333;
            text-decoration: none;
            font-weight: bold;
        }

        .sidebar a:hover {
            background-color: #d0e8f8;
        }

        .main {
            flex: 1;
            margin-left: 250px;
            padding: 20px;
        }

        .topbar {
            background-color: #2a7fc1;
            padding: 15px;
            display: flex;
            justify-content: space-between;
            color: white;
        }

        .content {
            padding: 40px;
        }

        .dashboard-title {
            font-size: 30px;
            font-weight: bold;
            color: #2a7fc1;
            margin-bottom: 30px;
        }

        .dropdown-checkbox {
        position: relative;
        display: inline-block;
        width: 300px;
    }

    .dropdown-btn {
        width: 100%;
        padding: 10px;
        border: 1px solid #ccc;
        background-color: white;
        text-align: left;
        cursor: pointer;
    }

    .dropdown-list {
        display: none;
        position: absolute;
        z-index: 1;
        background-color: #fff;
        border: 1px solid #ccc;
        max-height: 200px;
        overflow-y: auto;
        width: 100%;
    }

    .dropdown-list label {
        display: block;
        padding: 8px 10px;
        cursor: pointer;
    }

    .dropdown-list label:hover {
        background-color: #f0f0f0;
    }

    .dropdown-checkbox.open .dropdown-list {
        display: block;
    }

    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>
    <div class="main">
        <?php include 'topbar.php'; ?>
        <div class="content">
            <h2 class="dashboard-title">Kelola Lelang</h2>

            <!-- Form Tambah Barang ke Lelang -->
           <form method="POST" class="mb-4">
    <div class="dropdown-checkbox" id="dropdownCheckbox">
        <div class="dropdown-btn" onclick="toggleDropdown()">-- Pilih Barang --</div>
        <div class="dropdown-list">
            <label>
                <input type="checkbox" id="checkAll"> Pilih Semua
            </label>
            <?php mysqli_data_seek($barang, 0); while ($b = mysqli_fetch_assoc($barang)) { ?>
                <label>
                    <input type="checkbox" name="id_barang[]" class="item-checkbox" value="<?= $b['id_barang']; ?>">
                    <?= $b['nama_barang']; ?>
                </label>
            <?php } ?>
        </div>
    </div>
    <button class="btn btn-primary mt-2" type="submit" name="submit">Tambah ke Lelang</button>
</form>



            <!-- Tabel Lelang -->
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>ID Lelang</th>
                        <th>Nama Barang</th>
                        <th>Tanggal</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($l = mysqli_fetch_assoc($lelang)) { ?>
                        <tr>
                            <td><?= $l['id_lelang']; ?></td>
                            <td><?= $l['nama_barang']; ?></td>
                            <td><?= $l['tgl_lelang']; ?></td>
                            <td><?= ucfirst($l['status']); ?></td>
                            <td>
                                <?php
                                $bolehBukaTutup = $l['status'] == 'dibuka' || ($l['status'] == 'ditutup' && is_null($l['id_user']));
                                if ($bolehBukaTutup) {
                                    if ($l['status'] == 'dibuka') {
                                        echo '<a href="?aksi=tutup&id_lelang=' . $l['id_lelang'] . '" class="btn btn-danger btn-sm">Tutup</a> ';
                                    } else {
                                        echo '<a href="?aksi=buka&id_lelang=' . $l['id_lelang'] . '" class="btn btn-success btn-sm">Buka</a> ';
                                    }
                                }
                                ?>
                                <a href="?aksi=hapus&id_lelang=<?= $l['id_lelang']; ?>" class="btn btn-warning btn-sm" onclick="return confirm('Yakin ingin menghapus lelang ini?')">Hapus</a>
                                <a href="detailLelang.php?id=<?= $l['id_lelang']; ?>" class="btn btn-info btn-sm">Detail</a>
                            </td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function logoutAlert() {
            if (confirm("Yakin ingin logout?")) {
                window.location.href = 'logoutPetugas.php';
            }
        }
    </script>

    <script>
    const pilihSemua = document.getElementById("pilihSemua");
    const checkboxBarang = document.querySelectorAll(".checkbox-barang");

    pilihSemua.addEventListener("change", function() {
        checkboxBarang.forEach(cb => cb.checked = this.checked);
    });
</script>

<script>
    function toggleDropdown() {
        document.getElementById("dropdownCheckbox").classList.toggle("open");
    }

    // Pilih semua checkbox
    document.getElementById("checkAll").addEventListener("change", function () {
        const checkboxes = document.querySelectorAll(".item-checkbox");
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Tutup dropdown jika klik di luar
    window.addEventListener('click', function(e) {
        const box = document.getElementById("dropdownCheckbox");
        if (!box.contains(e.target)) {
            box.classList.remove("open");
        }
    });
</script>


</body>
</html>
