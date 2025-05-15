<?php
include '../login/koneksi.php';
session_start();

// Matikan display_errors supaya warning tidak ikut ke output query
error_reporting(E_ALL);
ini_set('display_errors', 0);

// Cek id lelang di URL
if (!isset($_GET['id'])) {
    die("❌ ID lelang tidak dikirim di URL");
}

$id_lelang = intval($_GET['id']);

$query = "SELECT l.*, b.nama_barang, b.deskripsi_barang, b.foto_barang, b.harga_awal, b.id_barang 
          FROM tb_lelang l 
          JOIN tb_barang b ON l.id_barang = b.id_barang 
          WHERE l.id_lelang = $id_lelang";

$result = mysqli_query($conn, $query);

if (!$result) {
    die("❌ Query error: " . mysqli_error($conn));
}

$lelang = mysqli_fetch_assoc($result);

if (!$lelang) {
    die("❌ Data lelang tidak ditemukan di database.");
}

// Proses bidding user
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bid'])) {
    $id_user = $_SESSION['id_user'] ?? 0;
    $penawaran_harga = intval($_POST['penawaran_harga']);
    $tgl = date("Y-m-d H:i:s");
    $id_barang = intval($lelang['id_barang']);
    $harga_akhir = intval($lelang['harga_akhir'] ?? 0);

    if ($penawaran_harga > $harga_akhir) {
        $insert = mysqli_query($conn, "INSERT INTO history_lelang 
            (id_lelang, id_user, id_barang, penawaran_harga, created_at) 
            VALUES ($id_lelang, $id_user, $id_barang, $penawaran_harga, '$tgl')");

        $update = mysqli_query($conn, "UPDATE tb_lelang 
            SET harga_akhir = $penawaran_harga, id_user = $id_user 
            WHERE id_lelang = $id_lelang");

        if ($insert && $update) {
            header("Location: detailLelang.php?id=$id_lelang");
            exit();
        } else {
            $error = "Gagal menyimpan penawaran. Coba lagi.";
        }
    } else {
        $error = "Penawaran harus lebih tinggi dari harga saat ini.";
    }
}

// Proses tutup lelang oleh petugas dan tentukan pemenang
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['tutup_lelang'])) {
    // Cari penawaran tertinggi
    $getPemenang = mysqli_query($conn, "SELECT * FROM history_lelang 
                                        WHERE id_lelang = $id_lelang 
                                        ORDER BY penawaran_harga DESC 
                                        LIMIT 1");

    if ($getPemenang && mysqli_num_rows($getPemenang) > 0) {
        $pemenang = mysqli_fetch_assoc($getPemenang);
        $id_user_pemenang = $pemenang['id_user'] ?? null;
        $harga_akhir = $pemenang['penawaran_harga'] ?? null;

        if (!$id_user_pemenang || !$harga_akhir) {
            $error = "❌ Data pemenang tidak valid.";
        } else {
            $sql = "UPDATE tb_lelang 
                    SET status = 'ditutup', id_user = $id_user_pemenang, harga_akhir = $harga_akhir 
                    WHERE id_lelang = $id_lelang";

            if (mysqli_query($conn, $sql)) {
                header("Location: detailLelang.php?id=$id_lelang");
                exit();
            } else {
                $error = "❌ Gagal menutup lelang: " . mysqli_error($conn);
            }
        }
    } else {
        $error = "Tidak ada penawaran untuk lelang ini.";
    }
}

?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Detail Lelang - LeLon!</title>
  <style>
    body {
      background-color: #e0e0e0 !important;
    }
  </style>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="style.css" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
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
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown">Kategori</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#">Elektronik</a></li>
              <li><a class="dropdown-item" href="#">Furnitur</a></li>
              <li><a class="dropdown-item" href="#">Pakaian</a></li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link" href="lelang.php">Lelang</a></li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-light" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle"></i> <?php echo $_SESSION['nama']; ?>
            </a>
            <ul class="dropdown-menu dropdown-menu-end">
              <li><a class="dropdown-item text-danger" href="#" onclick="logoutAlert()"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Detail Barang -->
  <div class="container" style="margin-top: 100px;">
    <div class="row">
      <div class="col-md-6">
        <img src="img/barang/<?php echo htmlspecialchars($lelang['foto_barang']); ?>" class="img-fluid rounded shadow" alt="<?php echo htmlspecialchars($lelang['nama_barang']); ?>">
      </div>
      <div class="col-md-6">
        <h2><?php echo htmlspecialchars($lelang['nama_barang']); ?></h2>
        <p><?php echo nl2br(htmlspecialchars($lelang['deskripsi_barang'])); ?></p>
        <p>Harga Awal: <strong>Rp<?php echo number_format($lelang['harga_awal'], 0, ',', '.'); ?></strong></p>
        <p>Harga Saat Ini: <strong class="text-success">Rp<?php echo number_format($lelang['harga_akhir'], 0, ',', '.'); ?></strong></p>

        <?php if (isset($error)) : ?>
          <div class="alert alert-danger"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if ($lelang['status'] === 'dibuka') : ?>
          <?php if ($_SESSION['role'] === 'petugas') : ?>
            <!-- Form Tutup Lelang untuk Petugas -->
            <form method="post" class="mt-3">
              <button type="submit" name="tutup_lelang" class="btn btn-danger" onclick="return confirm('Yakin ingin menutup lelang ini dan menentukan pemenang?')">Tutup Lelang dan Tentukan Pemenang</button>
            </form>
          <?php else : ?>
            <!-- Form Bidding untuk User biasa -->
            <form method="post" class="mt-3">
              <div class="mb-3">
                <label for="penawaran_harga" class="form-label">Masukkan Penawaran Anda</label>
                <input type="number" class="form-control" name="penawaran_harga" required min="<?php echo $lelang['harga_akhir'] + 1; ?>">
              </div>
              <button type="submit" name="bid" class="btn btn-primary">Tawar Sekarang</button>
            </form>
          <?php endif; ?>
        <?php else : ?>
          <div class="alert alert-secondary">Lelang ini telah <strong>ditutup</strong>.</div>
          <p>Pemenang: 
            <?php
            // Tampilkan nama pemenang jika ada
            if ($lelang['id_user']) {
                $pemenangData = mysqli_query($conn, "SELECT nama_lengkap FROM tb_masyarakat WHERE id_user = " . intval($lelang['id_user']));
                if ($pemenangData && mysqli_num_rows($pemenangData) > 0) {
                    $pemenang = mysqli_fetch_assoc($pemenangData);
                    echo "<strong>" . htmlspecialchars($pemenang['nama_lengkap']) . "</strong>";
                } else {
                    echo "<em>Tidak diketahui</em>";
                }
            } else {
                echo "<em>Belum ada pemenang</em>";
            }
            ?>
          </p>
        <?php endif; ?>
      </div>
    </div>

    <!-- Riwayat Penawaran -->
    <hr class="my-4" />
    <h4>Riwayat Penawaran</h4>
    <table class="table table-bordered table-hover">
      <thead>
        <tr>
          <th>No</th>
          <th>Nama Penawar</th>
          <th>Harga Ditawar</th>
          <th>Tanggal</th>
        </tr>
      </thead>
      <tbody>
        <?php
        $history = mysqli_query($conn, "SELECT h.*, m.nama_lengkap FROM history_lelang h JOIN tb_masyarakat m ON h.id_user = m.id_user WHERE h.id_lelang = $id_lelang ORDER BY h.penawaran_harga DESC");
        $no = 1;
        while ($row = mysqli_fetch_assoc($history)) {
          echo "<tr>
                  <td>$no</td>
                  <td>" . htmlspecialchars($row['nama_lengkap']) . "</td>
                  <td>Rp" . number_format($row['penawaran_harga'], 0, ',', '.') . "</td>
                  <td>" . htmlspecialchars($row['created_at']) . "</td>
                </tr>";
          $no++;
        }
        ?>
      </tbody>
    </table>
  </div>

  <!-- Footer -->
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
    <path fill="#273036" fill-opacity="1" d="M0,160L48,186.7C96,213,192,267,288,277.3C384,288,480,256,576,224C672,192,768,160,864,165.3C960,171,1056,213,1152,224C1248,235,1344,213,1392,202.7L1440,192L1440,320L0,320Z"></path>
  </svg>
  <footer style="background-color: #273036; color: #fff; padding: 40px 0;">
    <div class="container text-center">
      <p>&copy; <?php echo date('Y'); ?> LeLon. All Rights Reserved.</p>
    </div>
  </footer>

  <script>
    function logoutAlert() {
      if (confirm("Yakin mau logout?")) {
        window.location.href = "logout.php";
      }
    }
  </script>
</body>

</html>
