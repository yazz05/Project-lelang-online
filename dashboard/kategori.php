<?php
session_start();
if (!isset($_SESSION['nama'])) {
  header("Location: login.php");
  exit();
}

require '../login/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$id_user = $_SESSION['id_user'];

// Ambil notifikasi belum terbaca
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
$stmt->close();
?>
<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Kategori Lelang - LeLon!</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">
  <style>
    body {
      background-color: #e0e0e0;
    }

    .kategori-title {
      font-size: 1.5rem;
      font-weight: bold;
      margin: 2rem 0 1rem;
    }

   .card img {
  aspect-ratio: 16 / 9;
  object-fit: cover;
}


    .scroll-container {
      overflow-x: auto;
      display: flex;
      gap: 1rem;
      padding-bottom: 1rem;
    }

    .scroll-container::-webkit-scrollbar {
      height: 8px;
    }

    .scroll-container::-webkit-scrollbar-thumb {
      background: #888;
      border-radius: 10px;
    }

    .scroll-container::-webkit-scrollbar-thumb:hover {
      background: #555;
    }

  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">
        <img src="img/logoLelon.png" width="40" height="30" alt="LeLon Logo">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav me-auto">
          <li class="nav-item"><a class="nav-link active" href="index.php">Home</a></li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Kategori</a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#elektronik">Elektronik</a></li>
              <li><a class="dropdown-item" href="#furnitur">Furnitur</a></li>
              <li><a class="dropdown-item" href="#pakaian">Pakaian</a></li>
              <li><a class="dropdown-item" href="#alat">Alat</a></li>
              <li><a class="dropdown-item" href="#kendaraan">Kendaraan</a></li>
              <li><a class="dropdown-item" href="#baranglainnya">Barang Lainnya</a></li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link" href="lelang.php">Lelang</a></li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle position-relative" href="#" data-bs-toggle="dropdown">
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
                    <a class="dropdown-item small" href="pesan.php?id_notif=<?= $notif['id_notif'] ?>">
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
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
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

  <!-- Kategori Section -->
  <div class="container mt-5 pt-5">
    <?php
    $kategoriList = ["Elektronik", "Furnitur", "Pakaian", "Alat", "Kendaraan", "Barang Lainnya"];

    foreach ($kategoriList as $kategori) {
      $idKategori = strtolower(str_replace(' ', '', $kategori));
      $query = "SELECT l.*, b.* FROM tb_lelang l 
          JOIN tb_barang b ON l.id_barang = b.id_barang 
          WHERE b.kategori = ? AND l.status = 'dibuka' 
          ORDER BY l.tgl_lelang DESC";

      $stmt = $koneksi->prepare($query);
      $stmt->bind_param("s", $kategori);
      $stmt->execute();
      $result = $stmt->get_result();

      if ($result->num_rows > 0) {
        echo '<div class="kategori-section" id="' . $idKategori . '">';
        echo '<div class="kategori-title">' . htmlspecialchars($kategori) . '</div>';
        echo '<div class="scroll-container">';
        while ($row = $result->fetch_assoc()) {
          echo '<div class="card" style="min-width: 250px;">';
       echo '<img src="' . htmlspecialchars($row['foto_barang']) . '" class="card-img-top" alt="' . htmlspecialchars($row['nama_barang']) . '">';


          echo '<div class="card-body">';
          echo '<h5 class="card-title">' . htmlspecialchars($row['nama_barang']) . '</h5>';
          echo '<p class="card-text">' . htmlspecialchars($row['deskripsi_barang']) . '</p>';
          echo '<p class="text-muted"><small>' . date("d M Y", strtotime($row['tgl_lelang'])) . ' | Rp' . number_format($row['harga_awal'], 0, ',', '.') . '</small></p>';
          echo '<a href="sesiLelang.php?id=' . $row['id_barang'] . '" class="btn btn-success">Mulai Lelang!</a>';
          echo '</div></div>';
        }
        echo '</div></div>';
      }
      $stmt->close();
    }
    ?>
  </div>

  <!-- Footer -->
    <?php include 'footer.php'; ?>


  <!-- Script -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function logoutAlert() {
      if (confirm('Yakin mau logout?')) {
        window.location.href = 'logout.php';
      }
    }

  </script>
</body>

</html>