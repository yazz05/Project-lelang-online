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
date_default_timezone_set('Asia/Jakarta');

// Proses hapus
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];
  mysqli_query($koneksi, "DELETE FROM tb_masyarakat WHERE id_user='$id'");
  header("Location: masyarakat.php");
  exit();
}

// Proses update
if (isset($_POST['update'])) {
  $id = $_POST['id_user'];
  $nama = $_POST['nama_lengkap'];
  $username = $_POST['username'];
  $password = $_POST['password'];
  $telp = $_POST['telp'];

  mysqli_query($koneksi, "UPDATE tb_masyarakat SET 
    nama_lengkap='$nama', username='$username', password='$password', telp='$telp' 
    WHERE id_user='$id'");
  header("Location: masyarakat.php");
  exit();
}

// Ambil data masyarakat
$data = mysqli_query($koneksi, "SELECT * FROM tb_masyarakat");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Data Masyarakat - LELON</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
  <style>
    /* style_admin.css */
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
  padding: 40px;
  background-color: #e6e6e6;
}

.dashboard-title {
  font-size: 30px;
  font-weight: bold;
  color: #2a7fc1;
  margin-bottom: 30px;
}

  </style>
</head>

<body>
  <?php include 'sidebar.php'; ?>

  <div class="main">
    <?php include 'topbar.php'; ?>

    <div class="content">
      <table class="table table-striped table-bordered">
        <thead class="table-dark">
          <tr>
            <th>No</th>
            <th>Nama Lengkap</th>
            <th>Username</th>
            <th>Password</th>
            <th>Telp</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php $no = 1;
          while ($row = mysqli_fetch_assoc($data)) : ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= htmlspecialchars($row['password']) ?></td>
              <td><?= htmlspecialchars($row['telp']) ?></td>
              <td>
                <a href="#" data-bs-toggle="modal" data-bs-target="#editModal<?= $row['id_user'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <a href="masyarakat.php?hapus=<?= $row['id_user'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus data ini?')">Hapus</a>
              </td>
            </tr>

            <!-- Modal Edit -->
            <div class="modal fade" id="editModal<?= $row['id_user'] ?>" tabindex="-1">
              <div class="modal-dialog">
                <form method="POST" class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title">Edit Data Masyarakat</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                  </div>
                  <div class="modal-body">
                    <input type="hidden" name="id_user" value="<?= $row['id_user'] ?>">
                    <div class="mb-3">
                      <label>Nama Lengkap</label>
                      <input type="text" name="nama_lengkap" class="form-control" value="<?= $row['nama_lengkap'] ?>" required>
                    </div>
                    <div class="mb-3">
                      <label>Username</label>
                      <input type="text" name="username" class="form-control" value="<?= $row['username'] ?>" required>
                    </div>
                    <div class="mb-3">
                      <label>Password</label>
                      <input type="text" name="password" class="form-control" value="<?= $row['password'] ?>" required>
                    </div>
                    <div class="mb-3">
                      <label>No. Telp</label>
                      <input type="text" name="telp" class="form-control" value="<?= $row['telp'] ?>" required>
                    </div>
                  </div>
                  <div class="modal-footer">
                    <button type="submit" name="update" class="btn btn-primary">Simpan Perubahan</button>
                  </div>
                </form>
              </div>
            </div>
          <?php endwhile; ?>
        </tbody>
      </table>
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
