<?php
session_start();
if (!isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit();
}

$koneksi = mysqli_connect("localhost", "root", "", "lelang_online");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$tanggalHariIni = date('Y-m-d');

if (isset($_GET['tanggal']) && !empty($_GET['tanggal'])) {
    $tanggal = $_GET['tanggal'];
    $barangResult = mysqli_query($koneksi, "SELECT * FROM tb_barang WHERE tgl = '$tanggal'");
} else {
    $barangResult = mysqli_query($koneksi, "SELECT * FROM tb_barang");
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Barang - LELON</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="style.css">
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
            overflow-y: auto;
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

        .sidebar a:hover,
        .sidebar-link:hover {
            background-color: #d0e8f8;
        }

        .sidebar .section {
            padding: 10px 20px;
            font-weight: bold;
            color: #666;
        }

        .sidebar-link {
            display: block;
            padding: 8px 10px;
            color: #333;
            text-decoration: none;
            font-weight: normal;
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

        .dashboard-title {
            font-size: 30px;
            font-weight: bold;
            color: #fff;
        }

        .content {
            margin-top: 20px;
        }

        table {
            background: white;
            border-radius: 10px;
            overflow: hidden;
        }

        th {
            background-color: #2a7fc1;
            color: white;
        }

        @media (max-width: 768px) {
            .sidebar {
                width: 100%;
                position: relative;
                height: auto;
            }

            .main {
                margin-left: 0;
            }
        }
    </style>
</head>

<body>
    <?php include('sidebar.php'); ?>

    <div class="main">
        <?php include('topbar.php'); ?>

        <div class="content">
            <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['success']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?= htmlspecialchars($_SESSION['error']); ?>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

            <div class="d-flex justify-content-between mb-3 flex-wrap gap-2">
                <form method="GET" class="d-flex align-items-center flex-wrap gap-2">
                    <label for="tanggal" class="mb-0">Filter Tanggal:</label>
                    <input type="date" name="tanggal" id="tanggal" class="form-control"
                           value="<?= isset($_GET['tanggal']) ? $_GET['tanggal'] : $tanggalHariIni ?>">
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                    <a href="barang.php" class="btn btn-secondary">Reset</a>
                    <a href="tambahBarang.php" class="btn btn-success">+ Tambah Barang</a>
                </form>

                
            </div>

            <table class="table table-bordered table-hover">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Barang</th>
                        <th>Tanggal</th>
                        <th>Harga Awal</th>
                        <th>Deskripsi</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($barangResult)) : ?>
                        <tr>
                            <td><?= $no++; ?></td>
                            <td><?= htmlspecialchars($row['nama_barang']); ?></td>
                            <td><?= htmlspecialchars($row['tgl']); ?></td>
                            <td>Rp<?= number_format($row['harga_awal'], 0, ',', '.'); ?></td>
                            <td><?= htmlspecialchars($row['deskripsi_barang']); ?></td>
                            <td>
                                <a href="editBarang.php?id=<?= $row['id_barang']; ?>" class="btn btn-warning btn-sm">Edit</a>
                                <a href="hapusBarang.php?id=<?= $row['id_barang']; ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus barang ini?')">Hapus</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>

    <script>
        function logoutAlert() {
            if (confirm("Yakin ingin logout?")) {
                window.location.href = 'logoutAdmin.php';
            }
        }
    </script>

</body>

</html>
