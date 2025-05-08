<?php
session_start();
if (!isset($_SESSION['nama'])) {
  header("Location: login.php");
  exit();
}

$koneksi = mysqli_connect("localhost", "root", "", "lelang_online");
if (!$koneksi) {
  die("Koneksi gagal: " . mysqli_connect_error());
}

$resultPetugas = mysqli_query($koneksi, "SELECT COUNT(*) AS total_petugas FROM tb_petugas");
$petugas = mysqli_fetch_assoc($resultPetugas)['total_petugas'];
$resultUser = mysqli_query($koneksi, "SELECT COUNT(*) AS total_user FROM tb_masyarakat");
$user = mysqli_fetch_assoc($resultUser)['total_user'];
$resultBarang = mysqli_query($koneksi, "SELECT COUNT(*) AS total_barang FROM tb_barang");
$barang = mysqli_fetch_assoc($resultBarang)['total_barang'];

date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Dashboard Admin - LELON</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* CSS untuk sidebar, topbar, dan layout */
* {
  margin: 0;
  padding: 0;
  box-sizing: border-box;
  font-family: 'Segoe UI', sans-serif;
}

body {
  display: flex;
  min-height: 100vh;
  background-color: #e6e6e6;
  margin: 0;
  padding: 0;
}

.sidebar {
  width: 250px;
  background-color: #f4f4f4;
  border-right: 1px solid #ccc;
  padding: 20px 0;
  position: fixed;
  top: 0;
  left: 0;
  height: 100vh;
  overflow-y: auto;
}

.sidebar h2 {
  text-align: center;
  color: white;
  background-color: #2a7fc1;
  padding: 15px 0;
}

.sidebar a {
  display: block;
  padding: 10px 20px;
  color: #333;
  text-decoration: none;
  font-weight: bold;
}

.sidebar a:hover,
.sidebar-link:hover {
  background-color: #d0e8f8;
}

.sidebar .section {
  padding: 10px 20px;
  font-weight: bold;
  color: #666;
}

.sidebar-link {
  display: block;
  padding: 8px 10px;
  color: #333;
  text-decoration: none;
  font-weight: normal;
}

.main {
  flex: 1;
  margin-left: 250px;
  padding: 20px;
}

.topbar {
  background-color: #2a7fc1;
  padding: 15px;
  display: flex;
  justify-content: space-between;
  color: white;
}

.content {
  flex: 1;
  padding: 40px;
  background-color: #e6e6e6;
}

.dashboard-title {
  font-size: 30px;
  font-weight: bold;
  color: #2a7fc1;
  margin-bottom: 30px;
}

.cards {
  display: flex;
  gap: 20px;
  margin-bottom: 30px;
  flex-wrap: wrap;
  justify-content: center;
}

.card {
  flex: 1;
  min-width: 250px;
  background-color: white;
  border-radius: 20px;
  padding: 30px;
  display: flex;
  flex-direction: column;
  align-items: center;
  text-align: center;
  justify-content: center;
  box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
}

.card .icon {
  font-size: 40px;
  color: #2a7fc1;
  margin-bottom: 10px;
}

.card .number {
  font-size: 28px;
  font-weight: bold;
  color: #2a7fc1;
}

.card .info {
  font-size: 16px;
  color: #555;
}

@media (max-width: 768px) {
  .sidebar {
    width: 100%;
    position: relative;
    height: auto;
  }

  .main {
    margin-left: 0;
  }

  .cards {
    flex-direction: column;
    align-items: center;
  }
}

  </style>
</head>
<body>
  <?php include 'sidebar.php'; ?>

  <div class="main">
    <?php include 'topbar.php'; ?>

    <div class="content">
      <div class="cards">
        <div class="card text-center flex-column">
          <div class="icon"><i class="fa fa-user-shield"></i></div>
          <div class="number"><?php echo $petugas; ?></div>
          <div class="info">Total Petugas</div>
        </div>

        <div class="card text-center flex-column">
          <div class="icon"><i class="fa fa-user"></i></div>
          <div class="number"><?php echo $user; ?></div>
          <div class="info">Total Masyarakat</div>
        </div>

        <div class="card text-center flex-column">
          <div class="icon"><i class="fa fa-box"></i></div>
          <div class="number"><?php echo $barang; ?></div>
          <div class="info">Total Barang</div>
        </div>
      </div>
    </div>
  </div>

  <script>
    function logoutAlert() {
      if (confirm("Yakin ingin logout?")) {
        window.location.href = 'logoutAdmin.php';
      }
    }
  </script>
</body>
</html>
