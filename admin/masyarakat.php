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

// Proses hapus dengan pengecekan partisipasi lelang
if (isset($_GET['hapus'])) {
  $id = $_GET['hapus'];

  // Cek apakah user pernah ikut lelang
  $checkLelang = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM history_lelang WHERE id_user='$id'");
  $result = mysqli_fetch_assoc($checkLelang);

  if ($result['total'] > 0) {
    // Jika user pernah ikut lelang, tampilkan alert dan jangan hapus
    echo "<script>alert('Tidak dapat menghapus! Masyarakat ini sedang/telah mengikuti lelang.'); window.location.href='masyarakat.php';</script>";
    exit();
  } else {
    // Jika user tidak pernah ikut lelang, lanjutkan penghapusan
    mysqli_query($koneksi, "DELETE FROM tb_masyarakat WHERE id_user='$id'");
    header("Location: masyarakat.php");
    exit();
  }
}

$data = mysqli_query($koneksi, "SELECT * FROM tb_masyarakat");
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Data Masyarakat - LELON</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/js/all.min.js"></script>
  <style>
    body {
      background-color: #e6e6e6;
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

    .table-custom thead {
      background-color: #2a7fc1;
      color: white;
    }

    .table-custom th,
    .table-custom td {
      border: 1px solid rgb(199, 195, 195);
      vertical-align: middle;
    }

    .table-custom tbody tr:nth-child(even) {
      background-color: #e1f0fb;
    }

    .table-custom tbody tr:nth-child(odd) {
      background-color: #ffffff;
    }

    .badge-lelang {
      background-color: #ffc107;
      color: #000;
    }
  </style>
</head>

<body>
  <?php include 'sidebar.php'; ?>

  <div class="main">
    <?php include 'topbar.php'; ?>

    <div class="content">
      <h3 class="dashboard-title">Data Masyarakat</h3>
      <table class="table table-striped table-custom">
        <thead>
          <tr>
            <th>No</th>
            <th>Nama Lengkap</th>
            <th>Username</th>
            <th>Password</th>
            <th>Telp</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          <?php
          $no = 1;
          while ($row = mysqli_fetch_assoc($data)) :
            // Cek apakah user pernah ikut lelang
            $id_user = $row['id_user'];
            $checkLelang = mysqli_query($koneksi, "SELECT COUNT(*) as total FROM history_lelang WHERE id_user='$id_user'");
            $partisipasi = mysqli_fetch_assoc($checkLelang);
            $ikut_lelang = $partisipasi['total'] > 0;
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= htmlspecialchars($row['nama_lengkap']) ?></td>
              <td><?= htmlspecialchars($row['username']) ?></td>
              <td><?= str_repeat('*', 8) ?></td>
              <td><?= htmlspecialchars($row['telp']) ?></td>
              <td>
                <?php if ($ikut_lelang): ?>
                  <span class="badge badge-lelang">Sedang/Telah Mengikuti Lelang</span>
                <?php else: ?>
                  <span class="badge bg-secondary">Tidak Aktif</span>
                <?php endif; ?>
              </td>
              <td>
                <a href="editMasyarakat.php?id=<?= $row['id_user'] ?>" class="btn btn-warning btn-sm">Edit</a>
                <?php if (!$ikut_lelang): ?>
                  <a href="masyarakat.php?hapus=<?= $row['id_user'] ?>" class="btn btn-danger btn-sm" onclick="return confirm('Yakin ingin hapus data ini?')">Hapus</a>
                <?php else: ?>
                  <button class="btn btn-danger btn-sm" disabled title="Tidak dapat dihapus karena sedang/telah mengikuti lelang">Hapus</button>
                <?php endif; ?>
              </td>
            </tr>
          <?php endwhile; ?>
        </tbody>
      </table>
    </div>
  </div>
  <script>
    function logoutAlert() {
      if (confirm('Yakin ingin logout?')) {
        window.location.href = 'logoutAdmin.php';
      }
    }
  </script>

</body>

</html>