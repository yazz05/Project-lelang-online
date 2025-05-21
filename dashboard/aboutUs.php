<?php
session_start();

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
  <title>About Us - LeLon!</title>

  <!-- Import Google Fonts Poppins -->
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet" />

  <!-- Bootstrap CSS & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />

  <style>
    body {
      font-family: 'Poppins', sans-serif;
      background-color: #f8f9fa;
    }

    .about-me-container {
      max-width: 1400px; /* atau lebih besar lagi */
      margin: 100px auto 150px auto;
      padding: 50px 60px;
      background: #fff;
      border-radius: 16px;
      box-shadow: 0 6px 30px rgba(0, 0, 0, 0.08);
    }

    .about-me-content {
      display: flex;
      align-items: flex-start;
      justify-content: flex-start;
      gap: 40px;
      flex-wrap: nowrap; /* <--- PENTING: agar tidak turun ke bawah */
    }

    .about-me-photo {
      width: 250px;
      height: auto;
      object-fit: contain;
      border-radius: 0;
      box-shadow: none;
      margin-top: -10px; /* naikkan sedikit */
    }

    .about-me-text h1 {
      font-weight: 800;
      font-size: 2.8rem;
      color: #222;
      margin-bottom: 30px;
    }

    .about-me-text {
      flex: 1;
      display: flex;
      flex-direction: column;
      justify-content: center;
    }

    .about-me-text p {
      font-weight: 400;
      font-size: 1.1rem;
      color: #555;
      line-height: 1.7;
      margin-bottom: 1.3rem;
    }

    @media (max-width: 768px) {
      .about-me-container {
        padding: 30px 20px;
      }

      .about-me-content {
        flex-direction: column;
        align-items: center;
        text-align: center;
      }

      .about-me-photo {
        width: 280px;
        height: auto;
        object-fit: contain;
        flex-shrink: 0; /* <--- Biar gambar gak ikut menyusut */
      }

      .about-me-text {
        text-align: left;
      }
    }
  </style>
</head>

<body>

  <!-- Navbar -->
  <?php include 'navbar.php'; ?>

  <section class="about-me-container">
    <div class="about-me-header">
    </div>
    <div class="about-me-content">
      <img src="../maskot/maskotHi.png" alt="Profile Photo" class="about-me-photo" />

      <div class="about-me-text">
        <h1>Tentang Kami</h1>
        <p>Selamat datang di LeLon – Platform Lelang Online Terpercaya! Kami adalah marketplace lelang digital yang menghubungkan penjual dan pembeli dalam transaksi yang aman, cepat, dan transparan.</p>
        <p>Di LeLon, siapa pun dapat mengikuti lelang berbagai produk menarik, mulai dari barang elektronik, fashion, hingga koleksi unik – semua hanya dengan beberapa klik.</p>
        <p>Kami percaya bahwa pengalaman lelang harus menyenangkan dan adil. Dengan sistem real-time, tampilan yang user-friendly, dan dukungan tim profesional, kami memastikan setiap pengguna merasa nyaman dan aman dalam setiap proses.</p>
        <p>Bergabunglah dengan komunitas LeLon hari ini dan jadilah bagian dari revolusi belanja online melalui sistem lelang yang modern dan menyenangkan.</p>
      </div>
    </div>

  </section>

  <!-- Footer -->
  <?php include 'footer.php'; ?>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function logoutAlert() {
      if (confirm("Yakin mau logout?")) {
        window.location.href = "logout.php";
      }
    }
  </script>
</body>

</html>
