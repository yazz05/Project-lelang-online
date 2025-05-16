<?php
include '../login/koneksi.php'; // koneksi DB
session_start();

$id_barang = $_GET['id'] ?? 0;

// Ambil detail barang dan lelang
$queryBarang = $koneksi->query("
    SELECT b.*, l.id_lelang, l.tgl_lelang, l.status
    FROM tb_barang b
    JOIN tb_lelang l ON b.id_barang = l.id_barang
    WHERE b.id_barang = $id_barang
");

if (!$queryBarang) {
    die("Query Error: " . $koneksi->error);
}
$data = $queryBarang->fetch_assoc();

// Ambil penawaran tertinggi
$queryTertinggi = $koneksi->query("
    SELECT MAX(penawaran_harga) as tertinggi FROM history_lelang WHERE id_barang = $id_barang
");
$tertinggi = $queryTertinggi->fetch_assoc()['tertinggi'] ?? 0;

// Ambil riwayat penawaran
$queryRiwayat = $koneksi->query("
    SELECT h.*, m.nama_lengkap FROM history_lelang h
    JOIN tb_masyarakat m ON h.id_user = m.id_user
    WHERE h.id_barang = $id_barang
    ORDER BY h.penawaran_harga DESC
");
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Sesi Lelang</title>
   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
</head>

<body>
   <!-- Navbar -->
  <?php include 'navbar.php'; ?>

    <!-- Sesi Lelang -->
   <div class="container mt-5" style="padding-top: 80px;">
        <div class="row">
            <div class="col-md-5">
                <img src="<?= htmlspecialchars($data['foto_barang']) ?>" class="img-fluid rounded">
            </div>
            <div class="col-md-7">
                <h2><?= htmlspecialchars($data['nama_barang']) ?></h2>
                <p><strong>Harga Awal:</strong> Rp<?= number_format($data['harga_awal'], 0, ',', '.') ?></p>
                <p><strong>Deskripsi:</strong><br><?= nl2br(htmlspecialchars($data['deskripsi_barang'])) ?></p>
                <p><strong>Tanggal Lelang:</strong> <?= date('d M Y', strtotime($data['tgl_lelang'])) ?></p>
                <p><strong>Status:</strong> <?= $data['status'] === 'dibuka' ? '🔓 Dibuka' : '🔒 Ditutup' ?></p>
                <p><strong>Penawaran Tertinggi:</strong> Rp<?= number_format($tertinggi, 0, ',', '.') ?></p>

                <?php if ($data['status'] === 'dibuka'): ?>
                    <form action="prosesBid.php" method="POST">
                        <input type="hidden" name="id_barang" value="<?= $id_barang ?>">
                        <input type="hidden" name="id_lelang" value="<?= $data['id_lelang'] ?>">
                        <div class="mb-3">
                            <label for="penawaran" class="form-label">Masukkan Penawaran Anda</label>
                            <input type="number" name="penawaran_harga" class="form-control" required min="<?= $tertinggi + 1 ?>">
                        </div>
                        <button type="submit" class="btn btn-success">Tawar Sekarang</button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning">Lelang telah ditutup. Tidak bisa menawar lagi.</div>
                <?php endif; ?>
            </div>
        </div>

        <hr class="my-5">

        <h4>Riwayat Penawaran</h4>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nama</th>
                    <th>Penawaran</th>
                    <th>Waktu</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($r = $queryRiwayat->fetch_assoc()): ?>
                    <tr>
                        <td><?= htmlspecialchars($r['nama_lengkap']) ?></td>
                        <td>Rp<?= number_format($r['penawaran_harga'], 0, ',', '.') ?></td>
                        <td><?= date('d M Y H:i', strtotime($r['created_at'])) ?></td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
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
