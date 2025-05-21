<?php
session_start();

// Cek login
if (!isset($_SESSION['nama'])) {
  header("Location: login.php");
  exit();
}

$koneksi = new mysqli("localhost", "root", "", "lelang_online");
if ($koneksi->connect_error) {
  die("Koneksi gagal: " . $koneksi->connect_error);
}

$id_user = $_SESSION['id_user'] ?? 0;
$notifikasi = [];
$jumlah_notif = 0;

if ($id_user) {
  $stmt = $koneksi->prepare("SELECT id_notif, pesan, created_at FROM tb_notif WHERE id_user = ? ORDER BY created_at DESC LIMIT 5");
  $stmt->bind_param("i", $id_user);
  $stmt->execute();
  $result = $stmt->get_result();
  $notifikasi = $result->fetch_all(MYSQLI_ASSOC);
  $jumlah_notif = count($notifikasi);
  $stmt->close();
}


?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>LeLon!</title>

  <!-- CSS & JS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="style.css" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
  <style>
    body {
      background-color: white !important;
    }
  </style>
</head>

<body id="home">
  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <!-- Carousel -->
  <div id="carouselExampleSlidesOnly" class="carousel slide" data-bs-ride="carousel">
    <div class="carousel-inner">
      <div class="carousel-item active">
        <img src="img/bannerLelon1.jpg" class="d-block w-100" style="height: 345px; object-fit: cover; margin-top: 55px;" alt="gambar 1">
      </div>
      <div class="carousel-item">
        <img src="img/bannerLelon2.png" class="d-block w-100" style="height: 345px; object-fit: cover; margin-top: 55px;" alt="gambar 2">
      </div>
      <div class="carousel-item">
        <img src="img/bannerLelon3.png" class="d-block w-100" style="height: 345px; object-fit: cover; margin-top: 55px;" alt="gambar 3">
      </div>
    </div>
  </div>

  <p class="h4" style="margin-top: 100px; margin-bottom: 100px; text-align: center;">Temukan Harga Terbaik, <small class="text-body-secondary h4">Raih Barang Impianmu.</small></p>
  </section>

  <!-- SECTION 1: Kategori Populer -->
  <section class="py-5">
    <div class="container">
      <h3 class="text-center mb-4" style="font-family: 'Poppins', sans-serif; font-weight: 600;">Kategori Populer</h4>
        <div class="d-flex flex-wrap justify-content-center gap-4">
          <div class="text-center">
            <img src="img/2.jpg" alt="Elektronik" style="width: 140px; height: 140px; object-fit: contain; border-radius: 50%; background-color: #f5f5f5; padding: 10px;">
            <p class="mt-2 h6" style="font-family: 'Poppins', sans-serif;">Elektronik</p>
          </div>
          <div class="text-center">
            <img src="img/3.jpg" alt="Furnitur" style="width: 140px; height: 140px; object-fit: contain; border-radius: 50%; background-color: #f5f5f5; padding: 10px;">
            <p class="mt-2 h6" style="font-family: 'Poppins', sans-serif;">Furnitur</p>
          </div>
          <div class="text-center">
            <img src="img/4.jpg" alt="Pakaian" style="width: 140px; height: 140px; object-fit: contain; border-radius: 50%; background-color: #f5f5f5; padding: 10px;">
            <p class="mt-2 h6" style="font-family: 'Poppins', sans-serif;">Pakaian</p>
          </div>
          <div class="text-center">
            <img src="img/5.jpg" alt="Alat" style="width: 140px; height: 140px; object-fit: contain; border-radius: 50%; background-color: #f5f5f5; padding: 10px;">
            <p class="mt-2 h6" style="font-family: 'Poppins', sans-serif;">Alat</p>
          </div>
          <div class="text-center">
            <img src="img/6.jpg" alt="Kendaraan" style="width: 140px; height: 140px; object-fit: contain; border-radius: 50%; background-color: #f5f5f5; padding: 10px;">
            <p class="mt-2 h6" style="font-family: 'Poppins', sans-serif;">Kendaraan</p>
          </div>
          <div class="text-center">
            <img src="img/7.jpg" alt="Barang Lainnya" style="width: 140px; height: 140px; object-fit: contain; border-radius: 50%; background-color: #f5f5f5; padding: 10px;">
            <p class="mt-2 h6" style="font-family: 'Poppins', sans-serif;">Barang Lainnya</p>
          </div>
        </div>
    </div>
  </section>



  <!-- SECTION 2: Banner Shopping Info -->
  <section class="py-4">
    <div class="container">
      <div class="bg-light d-flex flex-column flex-md-row justify-content-between align-items-center p-4 rounded-4">
        <div>
          <h5 style="font-family: 'Poppins', sans-serif; font-weight: 600; margin-bottom: 5px;">Belanja jadi mudah</h5>
          <p style="font-family: 'Poppins', sans-serif; margin: 0; font-size: 0.95rem; color: #555;">
            Nikmati keandalan, pengiriman aman, dan pengembalian tanpa repot.
          </p>
        </div>
        <a href="kategori.php" class="btn btn-dark mt-3 mt-md-0 rounded-pill px-4" style="font-weight: 600; font-size: 0.95rem;">
          Mulai sekarang
        </a>
      </div>
    </div>
  </section>


  <!-- Full-width Banner Section with Overlay -->
  <section class="position-relative" style="height: 400px; overflow: hidden; margin-top: 100px;">
    <!-- Gambar Banner -->
    <img src="img/bannerLelon1.jpg" alt="Banner" class="w-100 h-100 object-fit-cover">

    <!-- Overlay gelap -->
    <div class="position-absolute top-0 start-0 w-100 h-100" style="background-color: rgba(0,0,0,0.4);"></div>


    <!-- Overlay teks di kiri -->
    <div class="position-absolute top-50 start-0 translate-middle-y ps-5 text-white" style="max-width: 600px;">
      <h2 class="fw-bold mb-3" style="font-size: 2.5rem;">
        Get your order or your money back
      </h2>
      <p class="mb-4" style="font-size: 1.1rem;">
        Shop confidently with eBay Money Back Guarantee.
      </p>
      <a href="#" class="btn btn-light btn-lg rounded-pill px-4 fw-semibold">
        Learn more
      </a>
    </div>
  </section>


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