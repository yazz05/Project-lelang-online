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
      text-align: center;
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
      $query = $koneksi->query("SELECT * FROM tb_barang WHERE kategori = '$kategori' ORDER BY tgl DESC");

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
          echo '      <p class="card-text text-muted"><small>' . date("d M Y", strtotime($row['tgl'])) . ' | Rp' . number_format($row['harga_awal'], 0, ',', '.') . '</small></p>';
          echo '      <a href="#" class="btn btn-primary">Mulai Lelang!</a>';
          echo '    </div>';
          echo '  </div>';
          echo '</div>';
        }

        echo '</div></div>';
      }
    }
    ?>
  </div>

  <!-- Footer -->
  <footer>
    <div class="container">
      &copy; <?= date('Y'); ?> LeLon! | Hak cipta dilindungi
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