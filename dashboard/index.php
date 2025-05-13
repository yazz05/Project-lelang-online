<?php
session_start();

// Cek login
if (!isset($_SESSION['nama'])) {
  header("Location: login.php");
  exit();
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
      background-color: #e0e0e0 !important;
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