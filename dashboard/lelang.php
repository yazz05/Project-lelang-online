<?php
session_start();
$koneksi = new mysqli("localhost", "root", "", "lelang_online");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Silakan login terlebih dahulu.'); window.location='login.php';</script>";
    exit;
}

$id_user = $_SESSION['id_user'];

// Lelang dibuka
$queryLelang = $koneksi->query("
    SELECT l.*, b.*
    FROM tb_lelang l
    JOIN tb_barang b ON l.id_barang = b.id_barang
    WHERE l.status = 'dibuka'
    ORDER BY l.tgl_lelang DESC
");

// Riwayat user
$stmtRiwayat = $koneksi->prepare("
    SELECT l.id_lelang, b.nama_barang, b.foto_barang, l.status, h.penawaran_harga, 
           p.nama_lengkap AS pemenang, l.harga_akhir, l.id_user AS id_pemenang
    FROM history_lelang h
    JOIN tb_lelang l ON h.id_lelang = l.id_lelang
    JOIN tb_barang b ON l.id_barang = b.id_barang
    LEFT JOIN tb_masyarakat p ON l.id_user = p.id_user
    WHERE h.id_user = ?
    GROUP BY l.id_lelang
    ORDER BY l.id_lelang DESC
");
$stmtRiwayat->bind_param("i", $id_user);
$stmtRiwayat->execute();
$resultRiwayat = $stmtRiwayat->get_result();

// Notifikasi
$notifikasi = [];
$jumlah_notif = 0;

$query = "SELECT id_notif, pesan, created_at FROM tb_notif 
          WHERE id_user = ? AND status_baca = 'belum terbaca' 
          ORDER BY created_at DESC";
$stmt = $koneksi->prepare($query);
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$notifikasi = $result->fetch_all(MYSQLI_ASSOC);
$jumlah_notif = count($notifikasi);
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Lelang - LeLon!</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <style>
        body {
            background-color: #f8f9fa;
        }

        .card {
            margin-bottom: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .badge-status {
            font-size: 0.9em;
        }

        .empty-state {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 0;
        }

        .empty-state img {
            width: 200px;
            height: auto;
            margin-bottom: 20px;
            opacity: 0.7;
        }

        .empty-state p {
            color: #6c757d;
            font-size: 1.1rem;
            max-width: 500px;
            margin: 0 auto;
        }

        .lelang-section {
            padding: 1rem 0;
        }

        .lelang-title {
            padding: 1rem;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .scroll-container {
            overflow-x: auto;
            white-space: nowrap;
            padding-bottom: 1rem;
        }

        .scroll-container .card {
            display: inline-block;
            width: 300px;
            margin-right: 1rem;
            white-space: normal;
            vertical-align: top;
        }

        .scroll-container .card img {
            height: 200px;
            object-fit: cover;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm fixed-top">
        <div class="container-fluid">
            <a class="navbar-brand" href="index.php">
                <img src="img/logoLelon.png" alt="LeLon Logo" width="40" height="30">
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNavDropdown">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link" href="index.php">Home</a></li>
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Kategori</a>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="kategori.php?kategori=elektronik">Elektronik</a></li>
                            <li><a class="dropdown-item" href="kategori.php?kategori=furnitur">Furnitur</a></li>
                            <li><a class="dropdown-item" href="kategori.php?kategori=pakaian">Pakaian</a></li>
                            <li><a class="dropdown-item" href="kategori.php?kategori=alat">Alat</a></li>
                            <li><a class="dropdown-item" href="kategori.php?kategori=kendaraan">Kendaraan</a></li>
                            <li><a class="dropdown-item" href="kategori.php?kategori=lainnya">Barang lainnya</a></li>
                        </ul>
                    </li>
                    <li class="nav-item"><a class="nav-link active" href="lelang.php">Lelang</a></li>
                </ul>
                <ul class="navbar-nav ms-auto">
                    <!-- Notifikasi -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle position-relative text-light" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-bell-fill"></i>
                            <?php if ($jumlah_notif > 0): ?>
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    <?= $jumlah_notif ?>
                                </span>
                            <?php endif; ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <?php if (!empty($notifikasi)): ?>
                                <?php foreach ($notifikasi as $notif): ?>
                                    <li>
                                        <a class="dropdown-item small text-wrap" href="pesan.php?id_notif=<?= $notif['id_notif'] ?>">
                                            <?= htmlspecialchars(substr($notif['pesan'], 0, 50)) ?>...
                                            <br><small class="text-muted"><?= date('d-m-Y H:i', strtotime($notif['created_at'])) ?></small>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                                <li>
                                    <hr class="dropdown-divider">
                                </li>
                                <li><a class="dropdown-item text-center" href="pesan.php">Lihat Semua</a></li>
                            <?php else: ?>
                                <li><span class="dropdown-item text-muted">Tidak ada notifikasi baru</span></li>
                            <?php endif; ?>
                        </ul>
                    </li>
                    <!-- User -->
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle text-light" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['nama']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item text-danger" href="#" onclick="logoutAlert()"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- ... (bagian atas tetap sama, sampai bagian tab) -->

    <!-- Konten -->
    <div class="container mt-5" style="padding-top: 80px; min-height: 60vh;">
        <h3 class="mb-4">Riwayat Lelang Saya</h3>
        <?php if ($resultRiwayat->num_rows > 0): ?>
            <?php while ($row = $resultRiwayat->fetch_assoc()): ?>
                <div class="card p-3 mb-3">
                    <div class="row">
                        <div class="col-md-3">
                            <img src="../uploads/<?= htmlspecialchars($row['foto_barang']) ?>" class="img-fluid rounded" alt="Barang">
                        </div>
                        <div class="col-md-9">
                            <h5><?= htmlspecialchars($row['nama_barang']) ?></h5>
                            <p>Status:
                                <?php if ($row['status'] == 'dibuka'): ?>
                                    <span class="badge bg-success">Dibuka</span>
                                <?php else: ?>
                                    <span class="badge bg-secondary">Ditutup</span>
                                <?php endif; ?>
                            </p>
                            <p>Penawaran Anda: <strong>Rp<?= number_format($row['penawaran_harga'], 0, ',', '.') ?></strong></p>
                            <?php if ($row['status'] == 'ditutup'): ?>
                                <p>Pemenang: <strong><?= htmlspecialchars($row['pemenang']) ?: 'Belum diumumkan' ?></strong></p>
                                <p>Harga Akhir: <span class="badge bg-info text-dark">Rp<?= number_format($row['harga_akhir'], 0, ',', '.') ?></span></p>
                                <?php if ($row['id_pemenang'] == $id_user): ?>
                                    <div class="alert alert-success mt-2">Selamat! Anda adalah pemenang lelang ini.</div>
                                <?php endif; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            <?php endwhile; ?>
        <?php else: ?>
            <div class="empty-state">
                <img src="../maskot/maskotExplain.png" alt="Belum ada lelang" class="img-fluid">
                <h4 class="text-muted mb-3">Ara~ Anda belum mengikuti lelang.</h4>
                <p>Mulailah menjelajahi lelang yang tersedia dan ikuti lelang yang menarik minat Anda!</p>
                <a href="kategori.php" class="btn btn-dark mt-3">Jelajahi Lelang</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <?php include 'footer.php'; ?>


    <script>
        function logoutAlert() {
            if (confirm("Yakin mau logout?")) {
                window.location.href = "logout.php";
            }
        }
    </script>
</body>

</html>