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

date_default_timezone_set('Asia/Jakarta');

// Proses hapus
if (isset($_GET['hapus'])) {
    $id = $_GET['hapus'];
    mysqli_query($koneksi, "DELETE FROM tb_petugas WHERE id_petugas='$id'");

    header("Location: admin.php");
    exit();
}

$data = mysqli_query($koneksi, "
  SELECT p.* 
  FROM tb_petugas p
  JOIN tb_level l ON p.id_level = l.id_level
  WHERE l.level = 'administrator'
");

?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Data Admin - LELON</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
    <style>
        body {
            background-color: #e6e6e6;
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

        .sidebar a:hover {
            background-color: #d0e8f8;
        }

        .main {
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
            flex: 1;
            padding: 40px;
            background-color: #e6e6e6;
        }

        .dashboard-title {
            font-size: 30px;
            font-weight: bold;
            color: #2a7fc1;
            margin-bottom: 30px;
        }

        .table-custom thead {
            background-color: #2a7fc1;
            color: white;
        }

        .table-custom th,
        .table-custom td {
            border: 1px solid rgb(199, 195, 195);
            vertical-align: middle;
        }

        .table-custom tbody tr:nth-child(even) {
            background-color: #e1f0fb;
        }

        .table-custom tbody tr:nth-child(odd) {
            background-color: #ffffff;
        }
    </style>
</head>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main">
        <?php include 'topbar.php'; ?>

        <div class="content">
            <h3 class="dashboard-title">Data Admin</h3>
            <table class="table table-striped table-custom">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $no = 1;
                    while ($row = mysqli_fetch_assoc($data)) : ?>
                        <tr>
                            <td><?= $no++ ?></td>
                            <td><?= htmlspecialchars($row['nama_petugas']) ?></td>
                            <td><?= htmlspecialchars($row['username']) ?></td>
                            <td><?= htmlspecialchars($row['password']) ?></td>
                            <td>
                                <span class="text-muted">Tidak tersedia</span>
                                <!--  Kalau mau sembunyikan total: -->
                                <!-- <td class="d-none"></td> -->
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>

            </table>
        </div>
    </div>
    <script>
        function logoutAlert() {
            if (confirm('Yakin ingin logout?')) {
                window.location.href = 'logoutPetugas.php';
            }
        }
    </script>

</body>

</html>