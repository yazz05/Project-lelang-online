<?php
session_start();
if (!isset($_SESSION['nama'])) {
  header("Location: ../login/login.php");
  exit();
}

$koneksi = new mysqli("localhost", "root", "", "lelang_online");

$error = '';
if (isset($_POST['submit'])) {
  $nama = $_POST['nama_barang'];
  $tgl = $_POST['tgl'];
  $harga = $_POST['harga_awal'];
  $deskripsi = $_POST['deskripsi_barang'];
  $kategori = $_POST['kategori'];

  $allowed_types = ['image/jpeg', 'image/png', 'image/webp'];
  $max_size = 2 * 1024 * 1024;
  $foto = $_FILES['foto_barang'];

  if ($foto['error'] === 0) {
    if (in_array($foto['type'], $allowed_types)) {
      if ($foto['size'] <= $max_size) {
        $ext = pathinfo($foto['name'], PATHINFO_EXTENSION);
        $new_name = uniqid('barang_', true) . '.' . $ext;
        $upload_dir = "../uploads/";
        $upload_path = $upload_dir . $new_name;

        if (move_uploaded_file($foto['tmp_name'], $upload_path)) {
          $koneksi->query("INSERT INTO tb_barang 
            (nama_barang, tgl, harga_awal, deskripsi_barang, kategori, foto_barang) 
            VALUES 
            ('$nama', '$tgl', '$harga', '$deskripsi', '$kategori', '$upload_path')");
          header("Location: barang.php");
          exit();
        } else {
          $error = "Gagal menyimpan file.";
        }
      } else {
        $error = "Ukuran gambar maksimal 2MB.";
      }
    } else {
      $error = "Jenis file tidak diperbolehkan. Gunakan JPG, PNG, atau WEBP.";
    }
  } else {
    $error = "Terjadi kesalahan saat upload gambar.";
  }
}

date_default_timezone_set('Asia/Jakarta');
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Input Barang - Admin</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
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
      <?php if (!empty($error)): ?>
        <div class="alert alert-danger"><?= $error ?></div>
      <?php endif; ?>

      <form method="POST" enctype="multipart/form-data" class="bg-white p-4 rounded shadow-sm">
        <div class="mb-3">
          <label class="form-label">Nama Barang</label>
          <input type="text" name="nama_barang" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Tanggal</label>
          <input type="date" name="tgl" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Harga Awal</label>
          <input type="number" name="harga_awal" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Deskripsi</label>
          <textarea name="deskripsi_barang" class="form-control" rows="4" required></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">Kategori</label>
          <select name="kategori" class="form-select" required>
            <option value="">-- Pilih Kategori --</option>
            <option value="Elektronik">Elektronik</option>
            <option value="Furnitur">Furnitur</option>
            <option value="Pakaian">Pakaian</option>
            <option value="Alat">Alat</option>
            <option value="Kendaraan">Kendaraan</option>
            <option value="Barang Lainnya">Barang Lainnya</option>
          </select>
        </div>
        <div class="mb-3">
          <label class="form-label">Foto Barang</label>
          <input type="file" name="foto_barang" class="form-control" accept="image/*" required>
        </div>
        <button type="submit" name="submit" class="btn btn-primary w-100">Simpan Barang</button>
      </form>
    </div>
  </div>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    function logoutAlert() {
      if (confirm("Yakin ingin logout?")) {
        window.location.href = 'logoutAdmin.php';
      }
    }
  </script>
</body>

</html>
