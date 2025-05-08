<?php
session_start();
include '../login/koneksi.php';

if (!isset($_SESSION['nama'])) {
  header("Location: login.php");
  exit();
}

if (!isset($_GET['id'])) {
  echo "ID Lelang tidak ditemukan!";
  exit();
}

$id_lelang = intval($_GET['id']);

// Ambil detail lelang dan barang
$query = "SELECT l.*, b.nama_barang, b.deskripsi, b.foto, b.harga_awal 
          FROM tb_lelang l 
          JOIN tb_barang b ON l.id_barang = b.id_barang 
          WHERE l.id_lelang = $id_lelang";
$result = mysqli_query($conn, $query);
$lelang = mysqli_fetch_assoc($result);

if (!$lelang) {
  echo "Data lelang tidak ditemukan.";
  exit();
}

// Menangani proses bid
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['bid'])) {
  $id_user = $_SESSION['id_user'];
  $penawaran_harga = intval($_POST['penawaran_harga']);
  $tgl = date('Y-m-d');

  if ($penawaran_harga > $lelang['harga_akhir']) {
    mysqli_query($conn, "INSERT INTO history_lelang (id_lelang, id_user, penawaran_harga, tgl_penawaran) 
                         VALUES ($id_lelang, $id_user, $penawaran_harga, '$tgl')");

    // Update harga akhir
    mysqli_query($conn, "UPDATE tb_lelang SET harga_akhir = $penawaran_harga, id_user = $id_user WHERE id_lelang = $id_lelang");

    header("Location: detail_lelang.php?id=$id_lelang");
    exit();
  } else {
    $error = "Penawaran harus lebih tinggi dari harga saat ini.";
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
        <img src="img/barang/<?php echo $lelang['foto']; ?>" class="img-fluid rounded shadow" alt="<?php echo $lelang['nama_barang']; ?>">
      </div>
      <div class="col-md-6">
        <h2><?php echo $lelang['nama_barang']; ?></h2>
        <p><?php echo nl2br($lelang['deskripsi']); ?></p>
        <p>Harga Awal: <strong>Rp<?php echo number_format($lelang['harga_awal'], 0, ',', '.'); ?></strong></p>
        <p>Harga Saat Ini: <strong class="text-success">Rp<?php echo number_format($lelang['harga_akhir'], 0, ',', '.'); ?></strong></p>

        <?php if ($lelang['status'] === 'dibuka') : ?>
          <form method="post" class="mt-3">
            <div class="mb-3">
              <label for="penawaran_harga" class="form-label">Masukkan Penawaran Anda</label>
              <input type="number" class="form-control" name="penawaran_harga" required min="<?php echo $lelang['harga_akhir'] + 1; ?>">
            </div>
            <?php if (isset($error)) echo '<div class="alert alert-danger">' . $error . '</div>'; ?>
            <button type="submit" name="bid" class="btn btn-primary">Tawar Sekarang</button>
          </form>
        <?php else : ?>
          <div class="alert alert-secondary">Lelang ini telah <strong>ditutup</strong>.</div>
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
        $history = mysqli_query($conn, "SELECT h.*, m.nama FROM history_lelang h JOIN tb_masyarakat m ON h.id_user = m.id_user WHERE h.id_lelang = $id_lelang ORDER BY h.penawaran_harga DESC");
        $no = 1;
        while ($row = mysqli_fetch_assoc($history)) {
          echo "<tr>
                <td>$no</td>
                <td>{$row['nama']}</td>
                <td>Rp" . number_format($row['penawaran_harga'], 0, ',', '.') . "</td>
                <td>{$row['tgl_penawaran']}</td>
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
