<!-- Edit Masyarakat dengan Collapse Layout -->
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

if (!isset($_GET['id'])) {
  header("Location: petugas.php");
  exit();
}

$id = $_GET['id'];
$query = mysqli_query($koneksi, "SELECT * FROM tb_petugas WHERE id_petugas='$id'");
$data = mysqli_fetch_assoc($query);

if (!$data) {
  echo "Data tidak ditemukan";
  exit();
}

if (isset($_POST['update'])) {
  $nama = $_POST['nama_petugas'];
  $username = $_POST['username'];
  $password = $_POST['password'];

  mysqli_query($koneksi, "UPDATE tb_petugas SET 
    nama_petugas='$nama', username='$username', password='$password'
    WHERE id_petugas='$id'") or die(mysqli_error($koneksi));

  header("Location: petugas.php");
  exit();
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Edit Masyarakat - LELON</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <style>
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
          <label class="form-label">Nama Lengkap</label>
          <input type="text" name="nama_petugas" class="form-control" value="<?= htmlspecialchars($data['nama_petugas']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" value="<?= htmlspecialchars($data['username']) ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password</label>
          <input type="text" name="password" class="form-control" value="<?= htmlspecialchars($data['password']) ?>" required>
        </div>
        <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
        <a href="petugas.php" class="btn btn-secondary ms-2">Batal</a>
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
