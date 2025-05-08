<?php
session_start();

// Cek apakah user sudah login
if (!isset($_SESSION['nama'])) {
  header("Location: login.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Daftar Lelang - LeLon!</title>
  <style>
    body {
      background-color: #e0e0e0 !important;
    }
  </style>

  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" crossorigin="anonymous" />
  <!-- CSS -->
  <link rel="stylesheet" href="style.css" />
  <!-- Bootstrap JS Bundle -->
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
  <!-- Icons -->
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
              <li><a class="dropdown-item" href="kategori.php">Elektronik</a></li>
              <li><a class="dropdown-item" href="kategori.php">Furnitur</a></li>
              <li><a class="dropdown-item" href="kategori.php">Pakaian</a></li>
              <li><a class="dropdown-item" href="kategori.php">Alat</a></li>
              <li><a class="dropdown-item" href="kategori.php">Kendaraan</a></li>
              <li><a class="dropdown-item" href="kategori.php">Barang lainnya</a></li>
            </ul>
          </li>
          <li class="nav-item"><a class="nav-link active" href="lelang.php">Lelang</a></li>
        </ul>

        <!-- User -->
        <ul class="navbar-nav ms-auto">
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle text-light" href="#" role="button" data-bs-toggle="dropdown">
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

  <!-- Konten Daftar Lelang -->
  <div class="container" style="margin-top: 100px; min-height: 100vh;">
    <h2 class="mb-4 text-center">Barang yang Sedang Dilelang</h2>
    <div class="row">
      <?php
      include '../login/koneksi.php'; // pastikan file koneksi ada

      $query = "SELECT * FROM tb_lelang INNER JOIN tb_barang ON tb_lelang.id_barang = tb_barang.id_barang WHERE tb_lelang.status='dibuka'";
      $result = mysqli_query($conn, $query);

      if (mysqli_num_rows($result) > 0) {
        while ($data = mysqli_fetch_assoc($result)) {
      ?>
          <div class="col-md-4 mb-4">
            <div class="card shadow-sm h-100">
              <img src="img/barang/<?php echo $data['foto']; ?>" class="card-img-top" alt="<?php echo $data['nama_barang']; ?>" style="height: 200px; object-fit: cover;">
              <div class="card-body">
                <h5 class="card-title"><?php echo $data['nama_barang']; ?></h5>
                <p class="card-text">Harga Awal: Rp<?php echo number_format($data['harga_awal'], 0, ',', '.'); ?></p>
                <p class="card-text"><small class="text-muted">Tanggal: <?php echo $data['tgl_lelang']; ?></small></p>
                <a href="detail_lelang.php?id=<?php echo $data['id_lelang']; ?>" class="btn btn-primary w-100">Ikuti Lelang</a>
              </div>
            </div>
          </div>
      <?php
        }
      } else {
        echo '<p class="text-center">Tidak ada lelang yang sedang dibuka.</p>';
      }
      ?>
    </div>
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
