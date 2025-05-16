<?php
session_start();
$koneksi = new mysqli("localhost", "root", "", "lelang_online");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$id_lelang = isset($_GET['id']) ? (int)$_GET['id'] : 0;

if ($id_lelang <= 0) {
    die("ID lelang tidak valid.");
}

// Ambil info lelang & barang
$stmt = $koneksi->prepare("
    SELECT l.*, b.nama_barang, b.foto_barang, b.deskripsi_barang, b.harga_awal 
    FROM tb_lelang l 
    JOIN tb_barang b ON l.id_barang = b.id_barang 
    WHERE l.id_lelang = ?
");
$stmt->bind_param("i", $id_lelang);
$stmt->execute();
$result = $stmt->get_result();
$lelang = $result->fetch_assoc();
$stmt->close();

if (!$lelang) {
    die("Data lelang tidak ditemukan.");
}

// Ambil semua bid
$stmt = $koneksi->prepare("
    SELECT h.*, m.nama_lengkap 
    FROM history_lelang h 
    JOIN tb_masyarakat m ON h.id_user = m.id_user 
    WHERE h.id_lelang = ? 
    ORDER BY penawaran_harga DESC
");
$stmt->bind_param("i", $id_lelang);
$stmt->execute();
$penawaran = $stmt->get_result();
$stmt->close();

// Mengambil penawaran tertinggi
$stmt = $koneksi->prepare("
    SELECT h.penawaran_harga, h.id_user, m.nama_lengkap 
    FROM history_lelang h 
    JOIN tb_masyarakat m ON h.id_user = m.id_user 
    WHERE h.id_lelang = ? 
    ORDER BY h.penawaran_harga DESC LIMIT 1
");
$stmt->bind_param("i", $id_lelang);
$stmt->execute();
$result = $stmt->get_result();
$penawaran_tertinggi = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Detail Lelang - LELON</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />


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

        .detail-container {
            background: #fff;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }

        .detail-title {
            font-size: 28px;
            font-weight: 700;
            color: #333;
            margin-bottom: 25px;
            border-bottom: 2px solid #2a7fc1;
            padding-bottom: 10px;
        }

        .item-image {
            width: 100%;
            height: auto;
            max-height: 350px;
            object-fit: cover;
            border-radius: 10px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }

        .item-info p {
            font-size: 16px;
            margin: 8px 0;
        }

        .item-info strong {
            color: #555;
            width: 130px;
            display: inline-block;
        }

        .penawaran-tertinggi {
            background-color: #f9f9f9;
            padding: 15px 20px;
            border-left: 5px solid #2ecc71;
            border-radius: 8px;
            margin-top: 25px;
        }

        .penawaran-tertinggi p {
            margin: 5px 0;
        }

        .table-container {
            margin-top: 30px;
            background: #fff;
            padding: 20px;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
    </style>
</head>

<body>
    <?php include('sidebar.php'); ?>
    <?php include('topbar.php'); ?>

    <div class="content-wrapper">
        <div class="container-fluid detail-container">
            <h3 class="detail-title">Detail Lelang: <?= htmlspecialchars($lelang['nama_barang']); ?></h3>

            <img src="<?= htmlspecialchars($lelang['foto_barang']); ?>" alt="<?= htmlspecialchars($lelang['nama_barang']); ?>" class="item-image" />

            <div class="item-info">
                <p><strong>Deskripsi:</strong> <?= htmlspecialchars($lelang['deskripsi_barang']); ?></p>
                <p><strong>Harga Awal:</strong> Rp<?= number_format($lelang['harga_awal'], 0, ',', '.'); ?></p>
                <p><strong>Status Lelang:</strong>
                    <span class="badge <?= (isset($lelang['status']) && trim(strtolower($lelang['status'])) === 'dibuka') ? 'bg-success' : 'bg-danger'; ?>">
                        <?= ucfirst(htmlspecialchars($lelang['status'])); ?>
                    </span>
                </p>
            </div>

            <?php if ($penawaran_tertinggi) : ?>
                <div class="penawaran-tertinggi">
                    <h5><strong>Penawaran Tertinggi:</strong></h5>
                    <p><strong>Nama:</strong> <?= htmlspecialchars($penawaran_tertinggi['nama_lengkap']); ?></p>
                    <p><strong>Penawaran:</strong> Rp<?= number_format($penawaran_tertinggi['penawaran_harga'], 0, ',', '.'); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <div class="container-fluid table-container">
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
                    <?php while ($row = $penawaran->fetch_assoc()) : ?>
                        <tr>
                            <td><?= htmlspecialchars($row['nama_lengkap']); ?></td>
                            <td>Rp<?= number_format($row['penawaran_harga'], 0, ',', '.'); ?></td>
                            <td><?= date("d M Y H:i", strtotime($row['created_at'])); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>

            <!-- Tombol Akhir Lelang -->
            <?php if (isset($lelang['status']) && strtolower($lelang['status']) != 'ditutup') : ?>
                <a href="pilihPemenang.php?id=<?= $id_lelang ?>" class="btn btn-danger mt-3">
                    Akhiri Lelang dan Pilih Pemenang
                </a>
            <?php else : ?>
                <div class="alert alert-info mt-3">Lelang sudah ditutup dan pemenang telah dipilih.</div>
            <?php endif; ?>
        </div>
    </div>

</body>

</html>