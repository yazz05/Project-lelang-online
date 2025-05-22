<?php
session_start();
if (!isset($_SESSION['nama'])) {
  header("Location: login.php");
  exit();
}

// Use only one database connection
require '../login/koneksi.php';
date_default_timezone_set('Asia/Jakarta');

$id_user = $_SESSION['id_user'];

// Get unread notifications
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
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Kategori Lelang - LeLon!</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <style>
    body {
      background-color: #e0e0e0 !important;
      margin: 0;
      padding: 0;
    }

    .kategori-section {
      padding: 1rem 0;
      background-color: transparent;
    }

    .kategori-section .d-flex>div {
      margin-right: 90px;
    }

    .kategori-title {
      padding: 1rem;
      font-size: 1.5rem;
      font-weight: bold;
    }

    .card {
      width: 320px;
      border-radius: 8px;
      overflow: hidden;
      box-shadow: 0 4px 6px rgb(0 0 0 / 0.1);
      display: flex;
      flex-direction: column;
    }

    .card img {
      width: 320px;
      height: auto;
      aspect-ratio: 16 / 9;
      object-fit: cover;
      display: block;
    }

    html {
      scroll-behavior: smooth;
    }

    .navbar {
      z-index: 9999;
    }

    footer {
      background-color: #212529;
      color: white;
      padding: 1rem 0;
      margin-top: 2rem;
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

<body id="home">
  <!-- Navbar - Fixed duplicate -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="index.php">
        <img src="img/logoLelon.png" alt="LeLon Logo" width="40" height="30">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="index.php">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              Kategori
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="kategori.php?kategori=elektronik">Elektronik</a></li>
              <li><a class="dropdown-item" href="kategori.php?kategori=furnitur">Furnitur</a></li>
              <li><a class="dropdown-item" href="kategori.php?kategori=pakaian">Pakaian</a></li>
              <li><a class="dropdown-item" href="kategori.php?kategori=alat">Alat</a></li>
              <li><a class="dropdown-item" href="kategori.php?kategori=kendaraan">Kendaraan</a></li>
              <li><a class="dropdown-item" href="kategori.php?kategori=lainnya">Barang lainnya</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="lelang.php">Lelang</a>
          </li>
        </ul>

        <!-- Notification Dropdown -->
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-light position-relative" href="#" role="button" data-bs-toggle="dropdown">
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
                    <a class="dropdown-item small text-wrap"
                      href="pesan.php?id_notif=<?= $notif['id_notif'] ?>">
                      <?= htmlspecialchars(substr($notif['pesan'], 0, 50)) ?>...
                      <br>
                      <small class="text-muted">
                        <?= date('d-m-Y H:i', strtotime($notif['created_at'])) ?>
                      </small>
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
          <!-- User Info -->
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-light" href="#" role="button" data-bs-toggle="dropdown">
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
  <div class="container mt-5 pt-5 pb-3">
    <?php
    $kategoriList = ["Elektronik", "Furnitur", "Pakaian", "Alat", "Kendaraan", "Barang Lainnya"];

    foreach ($kategoriList as $kategori) {
      $idKategori = strtolower(str_replace(' ', '', $kategori));

      // Use prepared statement to prevent SQL injection
      $query = "SELECT l.*, b.*
                      FROM tb_lelang l
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
        echo '<div class="d-flex flex-row overflow-auto gap-3 px-2 scroll-container" style="scroll-snap-type: x mandatory;">';

        while ($row = $result->fetch_assoc()) {
          echo '<div style="min-width: 250px; scroll-snap-align: start;">';
          echo '  <div class="card h-100">';
          echo '    <img src="' . htmlspecialchars($row['foto_barang']) . '" class="card-img-top" alt="' . htmlspecialchars($row['nama_barang']) . '">';
          echo '    <div class="card-body">';
          echo '      <h5 class="card-title">' . htmlspecialchars($row['nama_barang']) . '</h5>';
          echo '      <p class="card-text">' . htmlspecialchars($row['deskripsi_barang']) . '</p>';
          echo '      <p class="card-text text-muted"><small>' . date("d M Y", strtotime($row['tgl_lelang'])) . ' | Rp' . number_format($row['harga_awal'], 0, ',', '.') . '</small></p>';
          echo '      <a href="sesiLelang.php?id=' . $row['id_barang'] . '" class="btn btn-success">Mulai Lelang!</a>';
          echo '    </div>';
          echo '  </div>';
          echo '</div>';
        }

        echo '</div></div>';
      }
      $stmt->close();
    }
    ?>
  </div>

  <!-- Footer -->
  <footer style="background-color: #273036; color: #fff; padding: 0; margin-top: 2rem;">
    <div style="margin-bottom: -5px;">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#e0e0e0" fill-opacity="1"
          d="M0,64L48,69.3C96,75,192,85,288,122.7C384,160,480,224,576,229.3C672,235,768,181,864,144C960,107,1056,85,1152,101.3C1248,117,1344,171,1392,197.3L1440,224L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z">
        </path>
      </svg>
    </div>

    <div class="container py-5">
      <div class="row">
        <div class="col-md-4 mb-4 mb-md-0">
          <h5 style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.4rem; margin-bottom: 20px;">Tentang LeLon</h5>
          <p style="font-family: 'Poppins', sans-serif; font-size: 1rem; color: #dcdcdc;">
            LeLon adalah platform lelang online yang menawarkan berbagai barang keren dengan harga yang bisa Anda tawar sendiri. Temukan barang impian Anda melalui sistem lelang yang transparan dan aman!
          </p>
        </div>

        <div class="col-md-4 mb-4 mb-md-0">
          <h5 style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.4rem; margin-bottom: 20px;">Navigasi</h5>
          <ul class="list-unstyled" style="font-family: 'Poppins', sans-serif; font-size: 1rem; color: #dcdcdc;">
            <li><a href="index.php" style="color: #dcdcdc; text-decoration: none;">Home</a></li>
            <li><a href="lelang.php" style="color: #dcdcdc; text-decoration: none;">Lelang</a></li>
            <li><a href="#" style="color: #dcdcdc; text-decoration: none;">Kategori</a></li>
            <li><a href="aboutUs.php" style="color: #dcdcdc; text-decoration: none;">Tentang Kami</a></li>
          </ul>
        </div>

        <div class="col-md-4 mb-4 mb-md-0 text-center text-md-end">
          <h5 style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.4rem; margin-bottom: 20px;">Ikuti Kami</h5>
          <a href="#" style="margin-right: 15px; color: #dcdcdc; font-size: 1.5rem;"><i class="bi bi-facebook"></i></a>
          <a href="#" style="margin-right: 15px; color: #dcdcdc; font-size: 1.5rem;"><i class="bi bi-twitter"></i></a>
          <a href="#" style="margin-right: 15px; color: #dcdcdc; font-size: 1.5rem;"><i class="bi bi-instagram"></i></a>
          <a href="#" style="margin-right: 15px; color: #dcdcdc; font-size: 1.5rem;"><i class="bi bi-linkedin"></i></a>
        </div>
      </div>

      <hr class="mt-4" style="border-color: rgba(255,255,255,0.1);">
      <div class="row mt-4">
        <div class="col text-center">
          <p style="font-family: 'Poppins', sans-serif; font-size: 1rem; color: #dcdcdc; margin-bottom: 0;">
            &copy; <?php echo date('Y'); ?> LeLon. All Rights Reserved.
          </p>
        </div>
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function logoutAlert() {
      if (confirm('Yakin mau logout?')) {
        window.location.href = 'logout.php';
      }
    }

    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
      anchor.addEventListener('click', function(e) {
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
          e.preventDefault();
          const offset = 80;
          const targetPosition = target.getBoundingClientRect().top + window.pageYOffset - offset;
          window.scrollTo({
            top: targetPosition,
            behavior: 'smooth'
          });
        }
      });
    });
  </script>
</body>

</html>