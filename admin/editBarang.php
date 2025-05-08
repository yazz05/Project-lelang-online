<?php
session_start();
if (!isset($_SESSION['nama'])) {
  header("Location: login.php");
  exit();
}

$koneksi = new mysqli("localhost", "root", "", "lelang_online");
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;

$query = "SELECT * FROM tb_barang WHERE id_barang = $id";
$result = $koneksi->query($query);

if (!$result || $result->num_rows === 0) {
    echo "Data tidak ditemukan.";
    exit();
}

$data = $result->fetch_assoc();

if (isset($_POST['submit'])) {
  $nama = $_POST['nama_barang'];
  $tgl = $_POST['tgl'];
  $harga = $_POST['harga_awal'];
  $deskripsi = $_POST['deskripsi_barang'];
  $kategori = $_POST['kategori'];

  $koneksi->query("UPDATE tb_barang SET 
    nama_barang='$nama',
    tgl='$tgl',
    harga_awal='$harga',
    deskripsi_barang='$deskripsi',
    kategori='$kategori' 
    WHERE id_barang=$id");

  header("Location: barang.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Edit Barang - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="styles.css">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <style>
    /* styles.css */
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

.sidebar a:hover {
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

@media (max-width: 768px) {
  .sidebar {
    width: 100%;
    position: relative;
    height: auto;
  }

  .main {
    margin-left: 0;
  }
}

  </style>
</head>

<body>
  <?php include 'sidebar.php'; ?>

  <div class="main">
    <?php include 'topbar.php'; ?>

    <div class="content">
      <form method="POST" class="p-4 bg-white rounded shadow-sm" style="max-width:600px; margin:auto;">
        <div class="mb-3">
          <label class="form-label">Nama Barang</label>
          <input type="text" name="nama_barang" class="form-control" value="<?= htmlspecialchars($data['nama_barang']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Tanggal</label>
          <input type="date" name="tgl" class="form-control" value="<?= htmlspecialchars($data['tgl']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Harga Awal</label>
          <input type="number" name="harga_awal" class="form-control" value="<?= htmlspecialchars($data['harga_awal']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Deskripsi Barang</label>
          <textarea name="deskripsi_barang" class="form-control" rows="3" required><?= htmlspecialchars($data['deskripsi_barang']) ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Kategori</label>
          <input type="text" name="kategori" class="form-control" value="<?= htmlspecialchars($data['kategori']) ?>" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary">Update Barang</button>
        <a href="barang.php" class="btn btn-secondary ms-2">Batal</a>
      </form>
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
