<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['nama'])) {
  header("Location: login.php"); // redirect kalau belum login
  exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <style>
    body {
      background-color: #e0e0e0 !important;
    }
  </style>

  <title>LeLon!</title>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />

  <!-- CSS -->
  <link rel="stylesheet" href="style.css" />

  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>

  <!-- Icons -->
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
</head>

<body id="home">
  <!-- Nav bar -->
  <section id="home">
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm fixed-top">
      <div class="container-fluid">
        <a class="navbar-brand" href="#home">
          <img src="img/logoLelon.png" alt="LeLon Logo" width="40" height="30">
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
          <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNavDropdown">
          <ul class="navbar-nav me-auto">
            <li class="nav-item">
              <a class="nav-link active" aria-current="page" href="#home">Home</a>
            </li>
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                Kategori
              </a>
              <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="kategori.php">Elektronik</a></li>
                <li><a class="dropdown-item" href="kategori.php">Furnitur</a></li>
                <li><a class="dropdown-item" href="kategori.php">Pakaian</a></li>
                <li><a class="dropdown-item" href="kategori.php">Alat</a></li>
                <li><a class="dropdown-item" href="kategori.php">Kendaraan</a></li>
                <li><a class="dropdown-item" href="kategori.php">Barang lainnya</a></li>
              </ul>
            </li>
            <li class="nav-item">
              <a class="nav-link" href="lelang.php">Lelang</a>
            </li>
          </ul>

          <!-- User Dropdown -->
          <ul class="navbar-nav ms-auto">
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle text-light" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($_SESSION['nama']); ?>
              </a>
              <ul class="dropdown-menu dropdown-menu-end">
                <li><a class="dropdown-item text-danger" href="#" onclick="logoutAlert()"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </nav>

    <!-- End Nav bar -->

    <!-- Carousel -->
    <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="img/bannerLelon1.jpg" class="d-block w-100" style="height: 345px; object-fit: cover; margin-top: 55px;" alt="gambar 1">
        </div>
        <div class="carousel-item">
          <img src="img/bannerLelon2.jpeg" class="d-block w-100" style="height: 345px; object-fit: cover; margin-top: 55px;" alt="gambar 2">
        </div>
        <div class="carousel-item">
          <img src="img/bannerLelon3.png" class="d-block w-100" style="height: 345px; object-fit: cover; margin-top: 55px;" alt="gambar 3">
        </div>
      </div>
    </div>

    <h5 class="text-center" style="margin-top: 100px;">Temukan Harga Terbaik, Raih Barang Impianmu.</h5>
    <br>
    <br>
    <br>
    <br>
    <br>
  </section>

  <!-- Welcome Section -->
  <section style="background-color: #ffffff; padding: 60px 0; min-height: 100vh; display: flex; justify-content: center; align-items: center;" id="pengenalan">
    <div class="container">
      <div class="row align-items-center justify-content-center" style="height: 100%;">
        <!-- Kiri: Teks -->
        <div class="col-md-6 mb-4 mb-md-0 text-center text-md-start">
          <p style="font-family: 'Poppins', sans-serif; font-size: 1.2rem; line-height: 1.8; font-weight: 500; letter-spacing: 0.5px; color: #333;">
            Yoo, selamat datang di tempat nongkrongnya para pemburu barang kece – lelang online versi kekinian! Di sini lo bisa dapetin barang-barang keren, mulai dari yang rare banget sampe yang lagi hype, semua lewat sistem lelang yang aman, transparan, dan real-time. Tinggal daftar, pilih barang incaran, terus gas tawar-tawaran sampe menang. Nggak perlu ribet, bisa langsung dari layar komputer lo. Jadi, siap-siap dapetin deal gokil sambil rebahan!
          </p>
        </div>

        <!-- Kanan: Gambar + slogan -->
        <div class="col-md-6 text-center">
          <img src="img/bannerLelon3.png" alt="Lelang Keren" class="img-fluid rounded shadow-sm mb-3" style="max-height: 300px; object-fit: cover;">
          <p style="font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 600; color: #555; font-style: italic; letter-spacing: 0.5px; text-shadow: 1px 1px 3px rgba(0, 0, 0, 0.1);">
            “Lelang seru, barang baru, gaya lo makin jago.”
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Cara Mengikuti Lelang Section -->
  <section style="background-color: #e0e0e0; padding: 60px 0; min-height: 100vh; margin-top: 80px;" id="home3">
    <div class="container">
      <div class="row align-items-center justify-content-center">
        <!-- Teks Penjelasan -->
        <div class="col-md-8">
          <div style="background-color: #fff; padding: 40px; border-radius: 10px; box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);">
            <h2 style="font-family: 'Poppins', sans-serif; font-size: 2.5rem; font-weight: 600; color: #333; text-align: left; text-shadow: 2px 2px 5px rgba(0, 0, 0, 0.1); margin-bottom: 20px;">
              <i class="bi bi-clipboard-check" style="font-size: 2rem; color: #4caf50; margin-right: 10px;"></i>Cara Mengikuti Lelang
            </h2>
            <p style="font-family: 'Poppins', sans-serif; font-size: 1.2rem; line-height: 1.8; font-weight: 500; letter-spacing: 0.5px; color: #333; text-align: left;">
              Ikuti langkah-langkah berikut untuk bergabung dalam lelang online yang seru ini!
            </p>

            <ol style="text-align: left; margin-top: 20px; font-size: 1.1rem; color: #555; list-style-type: decimal; margin-left: 30px;">
              <li style="margin-bottom: 10px;">Daftar akun di platform kami jika belum memiliki akun.</li>
              <li style="margin-bottom: 10px;">Cari barang yang ingin Anda bid dan pastikan Anda sudah paham kondisi barangnya.</li>
              <li style="margin-bottom: 10px;">Masukkan harga tawaran yang Anda inginkan dan ikut lelang secara real-time.</li>
              <li style="margin-bottom: 10px;">Jika tawaran Anda yang tertinggi saat lelang selesai, Anda menang dan akan diberikan instruksi pembayaran.</li>
            </ol>

            <p style="font-family: 'Poppins', sans-serif; font-size: 1.1rem; font-weight: 600; color: #333; font-style: italic; text-align: left; margin-top: 20px;">
              Nikmati pengalaman lelang yang seru dan mudah!
            </p>

            <!-- Tombol Ayo Mulai Sekarang -->
            <div class="text-center" style="margin-top: 30px;">
              <a href="kategori.php" class="btn btn-success" style="font-family: 'Poppins', sans-serif; font-size: 1.2rem; font-weight: 600; padding: 10px 30px; border-radius: 5px; text-decoration: none; color: #fff;">
                Mulai Lelang Pertamamu!
              </a>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Footer with Wave -->
  <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320">
    <path fill="#273036" fill-opacity="1" d="M0,160L48,186.7C96,213,192,267,288,277.3C384,288,480,256,576,224C672,192,768,160,864,165.3C960,171,1056,213,1152,224C1248,235,1344,213,1392,202.7L1440,192L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
  </svg>

  <!-- Footer Content -->
  <footer style="background-color: #273036; color: #fff; padding: 40px 0;">
    <div class="container">
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


  <!-- Logout Script -->
  <script>
    function logoutAlert() {
      if (confirm("Yakin mau logout?")) {
        window.location.href = "logout.php";
      }
    }
  </script>
</body>

</html>