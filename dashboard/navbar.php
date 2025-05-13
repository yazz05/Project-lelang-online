<?php
// Pastikan session sudah aktif sebelum akses $_SESSION
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

// Cek apakah user sudah login
if (!isset($_SESSION['nama'])) {
  header("Location: login.php");
  exit();
}
?>

<!-- Navbar -->
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
<!-- End Navbar -->
