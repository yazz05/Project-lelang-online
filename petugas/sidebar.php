<!-- sidebar.php -->
<div class="sidebar">
  <h2>Halaman Petugas</h2>
  <div></div>
  <a href="index.php" class="px-3 mt-3 d-block fw-bold text-dark text-decoration-none">Dashboard</a>

  <div class="px-3">
    <a class="btn btn-light w-100 text-start fw-bold" data-bs-toggle="collapse" href="#collapseAdmin" role="button" aria-expanded="false" aria-controls="collapseAdmin">
      Administrator
    </a>
    <div class="collapse mt-1" id="collapseAdmin">
      <ul class="list-unstyled ps-3">
        <li><a class="sidebar-link" href="petugas.php">Petugas</a></li>
        <li><a class="sidebar-link" href="admin.php">Admin</a></li>
        <li><a class="sidebar-link" href="masyarakat.php">Masyarakat</a></li>
      </ul>
    </div>
  </div>

  <a href="barang.php" class="px-3 mt-3 d-block fw-bold text-dark text-decoration-none">Data Barang</a>

  <a href="bukaTutupLelang.php" class="px-3 mt-3 d-block fw-bold text-dark text-decoration-none">Lelang</a>

  <a href="laporanLelang.php" class="px-3 mt-3 d-block fw-bold text-dark text-decoration-none">Generate Laporan</a>

  <div class="mt-auto px-3 pt-4">
    <div class="fw-bold text-secondary mb-2">
      <i class="fa fa-user-circle"></i> <?php echo htmlspecialchars($_SESSION['nama']); ?>
    </div>
    <a href="../petugas/logoutPetugas.php" class="btn btn-danger w-100 text-start fw-bold" onclick="logoutAlert()">
      <i class="fa fa-sign-out-alt"></i> Logout
    </a>
  </div>
</div>
