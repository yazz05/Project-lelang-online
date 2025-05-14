<?php
session_start();
$koneksi = mysqli_connect("localhost", "root", "", "lelang_online");

$id_lelang = $_GET['id'];

// Ambil info lelang & barang
$lelang = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT l.*, b.nama_barang, b.foto_barang, b.deskripsi_barang, b.harga_awal 
    FROM tb_lelang l 
    JOIN tb_barang b ON l.id_barang = b.id_barang 
    WHERE l.id_lelang = $id_lelang
"));

// Ambil semua bid
$penawaran = mysqli_query($koneksi, "
    SELECT h.*, m.nama_lengkap 
    FROM history_lelang h 
    JOIN tb_masyarakat m ON h.id_user = m.id_user 
    WHERE h.id_lelang = $id_lelang 
    ORDER BY penawaran_harga DESC
");

// Mengambil penawaran tertinggi
$penawaran_tertinggi = mysqli_fetch_assoc(mysqli_query($koneksi, "
    SELECT h.penawaran_harga, m.nama_lengkap 
    FROM history_lelang h 
    JOIN tb_masyarakat m ON h.id_user = m.id_user 
    WHERE h.id_lelang = $id_lelang 
    ORDER BY h.penawaran_harga DESC LIMIT 1
"));

// Proses penentuan pemenang
if (isset($_POST['tutup_lelang'])) {
    $id_pemenang = $_POST['id_user'];
    $harga_akhir = $_POST['harga_akhir'];

    mysqli_query($koneksi, "UPDATE tb_lelang SET status = 'ditutup', id_user = $id_pemenang, harga_akhir = $harga_akhir WHERE id_lelang = $id_lelang");

    echo "<script>alert('Lelang ditutup. Pemenang telah dipilih!'); window.location='lelang.php';</script>";
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Lelang - LELON</title>

    <!-- External CSS links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="styles.css">

    <!-- External JS links -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>

    <script>
        function logoutAlert() {
            if (confirm("Yakin ingin logout?")) {
                window.location.href = 'logoutPetugas.php';
            }
        }
    </script>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', sans-serif;
        }

        body {
            display: flex;
            min-height: 100vh;
            background-color: #e6e6e6;
            margin: 0;
            padding: 0;
            flex-direction: row;
            /* Menambahkan ini untuk memastikan elemen sidebar dan konten berjalan secara horizontal */
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

        .content-wrapper {
            flex: 1;
            margin-left: 250px;
            /* Pastikan konten tidak tertutup oleh sidebar */
            padding: 20px;
            overflow: auto;
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
            background-color: #e6e6e6;
        }

        .dashboard-title {
            font-size: 30px;
            font-weight: bold;
            color: #2a7fc1;
            margin-bottom: 30px;
        }

        /* Gambar Barang */
        .content img {
            width: 100%;
            height: auto;
            max-height: 400px;
            object-fit: cover;
            margin-bottom: 20px;
        }

        /* Styling untuk Deskripsi Barang */
        .content p {
            font-size: 16px;
            line-height: 1.6;
            margin-bottom: 15px;
        }

        .content p strong {
            font-weight: bold;
        }

        /* Tabel Riwayat Bid */
        .table {
            margin-top: 20px;
            border: 1px solid #ddd;
            border-collapse: collapse;
        }

        .table th,
        .table td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }

        .table th {
            background-color: #f4f4f4;
            font-weight: bold;
        }

        .table td {
            background-color: white;
        }

        .table tbody tr:hover {
            background-color: #f1f1f1;
        }

        /* Tombol Lelang */
        .btn-danger {
            background-color: #e74c3c;
            border: none;
            color: white;
            font-size: 16px;
            padding: 10px 20px;
            cursor: pointer;
        }

        .btn-danger:hover {
            background-color: #c0392b;
        }

        /* Badge Status Lelang */
        .badge {
            font-size: 14px;
            padding: 8px 12px;
            border-radius: 15px;
            color: white;
        }

        .bg-success {
            background-color: #2ecc71;
        }

        .bg-danger {
            background-color: #e74c3c;
        }
    </style>
</head>

<body>

    <!-- Include Side Bar -->
    <?php include('sidebar.php'); ?>

    <!-- Include Topbar -->
    <?php include('topbar.php'); ?>

    <div class="content-wrapper">
        <div class="container-fluid">
            <h3>Detail Lelang: <?= htmlspecialchars($lelang['nama_barang']); ?></h3>

            <!-- Gambar dan Deskripsi Barang -->
            <div>
                <img src="<?= htmlspecialchars($lelang['foto_barang']); ?>" alt="<?= htmlspecialchars($lelang['nama_barang']); ?>" class="img-fluid" style="max-height: 400px; object-fit: cover;">
                <p><strong>Deskripsi:</strong> <?= htmlspecialchars($lelang['deskripsi_barang']); ?></p>
                <p><strong>Harga Awal:</strong> Rp<?= number_format($lelang['harga_awal'], 0, ',', '.'); ?></p>
                <p><strong>Status Lelang:</strong> <span class="badge <?= $lelang['status'] === 'dibuka' ? 'bg-success' : 'bg-danger'; ?>"><?= ucfirst($lelang['status']); ?></span></p>
            </div>

            <?php if ($penawaran_tertinggi) : ?>
                <h4>Penawaran Tertinggi:</h4>
                <p><strong>Nama:</strong> <?= htmlspecialchars($penawaran_tertinggi['nama_lengkap']); ?></p>
                <p><strong>Penawaran:</strong> Rp<?= number_format($penawaran_tertinggi['penawaran_harga'], 0, ',', '.'); ?></p>
            <?php endif; ?>

            <!-- Tabel Riwayat Bid -->
            <h4>Riwayat Bid:</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>Nama Masyarakat</th>
                        <th>Penawaran</th>
                        <th>Tanggal Penawaran</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($penawaran)) { ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                            <td>Rp<?= number_format($row['penawaran_harga'], 0, ',', '.'); ?></td>
                            <td><?= date("d M Y H:i", strtotime($row['tanggal_penawaran'])); ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>

            <!-- Tombol untuk menutup lelang (hanya jika lelang belum ditutup) -->
            <?php if ($lelang['status'] !== 'ditutup') : ?>
                <form method="POST" class="mt-3">
                    <input type="hidden" name="id_user" value="<?= $penawaran_tertinggi['id_user']; ?>">
                    <input type="hidden" name="harga_akhir" value="<?= $penawaran_tertinggi['penawaran_harga']; ?>">
                    <button type="submit" name="tutup_lelang" class="btn btn-danger">Tutup Lelang dan Pilih Pemenang</button>
                </form>
            <?php endif; ?>

        </div>
    </div>

</body>

</html>