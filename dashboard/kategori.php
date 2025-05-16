<?php
session_start();
if (!isset($_SESSION['nama'])) {
  header("Location: login.php");
  exit();
}

$koneksi = new mysqli("localhost", "root", "", "lelang_online");
if ($koneksi->connect_error) {
  die("Koneksi gagal: " . $koneksi->connect_error);
}

date_default_timezone_set('Asia/Jakarta');
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
      /* kalau perlu padding dan lainnya tetap bisa pakai */
      padding: 1rem 0;
      background-color: transparent;
    }


    .kategori-title {
      padding: 1rem;
      font-size: 1.5rem;
      font-weight: bold;
    }

    .card img {
      height: 180px;
      object-fit: cover;
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
  </style>
</head>

<body id="home">

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm fixed-top">
    <div class="container-fluid">
      <a class="navbar-brand" href="#home">
        <img src="img/logoLelon.png" alt="LeLon Logo" width="40" height="30">
      </a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown">
        <span class="navbar-toggler-icon"></span>
      </button>

      <div class="collapse navbar-collapse" id="navbarNavDropdown">
        <ul class="navbar-nav me-auto">
          <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
          </li>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle active" href="#" role="button" data-bs-toggle="dropdown">
              Kategori
            </a>
            <ul class="dropdown-menu">
              <li><a class="dropdown-item" href="#elektronik">Elektronik</a></li>
              <li><a class="dropdown-item" href="#furnitur">Furnitur</a></li>
              <li><a class="dropdown-item" href="#pakaian">Pakaian</a></li>
              <li><a class="dropdown-item" href="#alat">Alat</a></li>
              <li><a class="dropdown-item" href="#kendaraan">Kendaraan</a></li>
              <li><a class="dropdown-item" href="#baranglainnya">Barang lainnya</a></li>
            </ul>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="lelang.php">Lelang</a>
          </li>
        </ul>
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-light" href="#" role="button" data-bs-toggle="dropdown">
              <i class="bi bi-person-circle"></i> <?= htmlspecialchars($_SESSION['nama']); ?>
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

    // Ambil data dari tb_lelang JOIN tb_barang berdasarkan kategori
    $query = $koneksi->query("
      SELECT l.*, b.*
      FROM tb_lelang l
      JOIN tb_barang b ON l.id_barang = b.id_barang
      WHERE b.kategori = '$kategori' AND l.status = 'dibuka'
      ORDER BY l.tgl_lelang DESC
    ");

    if ($query->num_rows > 0) {
      echo '<div class="kategori-section py-4" id="' . $idKategori . '">';
      echo '<div class="kategori-title">' . htmlspecialchars($kategori) . '</div>';
      echo '<div class="row g-4">';

      while ($row = $query->fetch_assoc()) {
        echo '<div class="col-6 col-md-3">';
        echo '  <div class="card h-100">';
        echo '    <img src="' . htmlspecialchars($row['foto_barang']) . '" class="card-img-top" alt="' . htmlspecialchars($row['nama_barang']) . '">';
        echo '    <div class="card-body">';
        echo '      <h5 class="card-title">' . htmlspecialchars($row['nama_barang']) . '</h5>';
        echo '      <p class="card-text">' . htmlspecialchars($row['deskripsi_barang']) . '</p>';
        echo '      <p class="card-text text-muted"><small>' . date("d M Y", strtotime($row['tgl_lelang'])) . ' | Rp' . number_format($row['harga_awal'], 0, ',', '.') . '</small></p>';

        echo '      <a href="sesiLelang.php?id=' . $row['id_barang'] . '" class="btn btn-primary">Mulai Lelang!</a>';

        echo '    </div>';
        echo '  </div>';
        echo '</div>';
      }

      echo '</div></div>';
    }
  }
  ?>
</div>




  <!-- Footer Start -->
  <footer style="background-color: #273036; color: #fff; padding: 0; margin-top: 2rem;">

    <!-- Wave Putih di Atas Footer -->
    <div style="margin-bottom: -5px;">
      <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
        <path fill="#e0e0e0" fill-opacity="1"
          d="M0,64L48,69.3C96,75,192,85,288,122.7C384,160,480,224,576,229.3C672,235,768,181,864,144C960,107,1056,85,1152,101.3C1248,117,1344,171,1392,197.3L1440,224L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z">
        </path>
      </svg>
    </div>

    <!-- Isi Footer -->
    <div class="container py-5">
      <div class="row">
        <!-- Kolom 1: Info Perusahaan -->
        <div class="col-md-4 mb-4 mb-md-0">
          <h5 style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.4rem; margin-bottom: 20px;">Tentang LeLon</h5>
          <p style="font-family: 'Poppins', sans-serif; font-size: 1rem; color: #dcdcdc;">
            LeLon adalah platform lelang online yang menawarkan berbagai barang keren dengan harga yang bisa Anda tawar sendiri. Temukan barang impian Anda melalui sistem lelang yang transparan dan aman!
          </p>
        </div>

        <!-- Kolom 2: Navigasi -->
        <div class="col-md-4 mb-4 mb-md-0">
          <h5 style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.4rem; margin-bottom: 20px;">Navigasi</h5>
          <ul class="list-unstyled" style="font-family: 'Poppins', sans-serif; font-size: 1rem; color: #dcdcdc;">
            <li><a href="index.php" style="color: #dcdcdc; text-decoration: none;">Home</a></li>
            <li><a href="lelang.php" style="color: #dcdcdc; text-decoration: none;">Lelang</a></li>
            <li><a href="#" style="color: #dcdcdc; text-decoration: none;">Kategori</a></li>
            <li><a href="about.php" style="color: #dcdcdc; text-decoration: none;">Tentang Kami</a></li>
          </ul>
        </div>

        <!-- Kolom 3: Social Media -->
        <div class="col-md-4 mb-4 mb-md-0 text-center text-md-end">
          <h5 style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.4rem; margin-bottom: 20px;">Ikuti Kami</h5>
          <a href="#" style="margin-right: 15px; color: #dcdcdc; font-size: 1.5rem; text-decoration: none;">
            <i class="bi bi-facebook"></i>
          </a>
          <a href="#" style="margin-right: 15px; color: #dcdcdc; font-size: 1.5rem; text-decoration: none;">
            <i class="bi bi-twitter"></i>
          </a>
          <a href="#" style="margin-right: 15px; color: #dcdcdc; font-size: 1.5rem; text-decoration: none;">
            <i class="bi bi-instagram"></i>
          </a>
          <a href="#" style="margin-right: 15px; color: #dcdcdc; font-size: 1.5rem; text-decoration: none;">
            <i class="bi bi-linkedin"></i>
          </a>
        </div>
      </div>


      <!-- Garis pemisah -->
      <hr class="mt-4" style="border-color: rgba(255,255,255,0.1);">

      <!-- Copyright -->
      <div class="row mt-4">
        <div class="col text-center">
          <p style="font-family: 'Poppins', sans-serif; font-size: 1rem; color: #dcdcdc; margin-bottom: 0;">
            &copy; <?php echo date('Y'); ?> LeLon. All Rights Reserved.
          </p>
        </div>
      </div>
    </div>
  </footer>
  <!-- Footer End -->


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