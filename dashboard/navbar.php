<?php
if (session_status() === PHP_SESSION_NONE) {
  session_start();
}

if (!isset($_SESSION['nama'])) {
  header("Location: login.php");
  exit();
}

require '../login/koneksi.php';
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

<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm fixed-top">
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

        <!-- Simplified Notification Dropdown -->
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
  </ul>
</nav>