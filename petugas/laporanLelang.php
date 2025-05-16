<?php
session_start();
$koneksi = new mysqli("localhost", "root", "", "lelang_online");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$kategori = $_GET['kategori'] ?? '';
$tanggal_mulai = $_GET['tanggal_mulai'] ?? '';
$tanggal_selesai = $_GET['tanggal_selesai'] ?? '';

$query = "
    SELECT l.*, b.nama_barang, b.kategori, b.harga_awal 
    FROM tb_lelang l 
    JOIN tb_barang b ON l.id_barang = b.id_barang 
    WHERE 1=1
";

if (!empty($kategori)) {
    $query .= " AND b.kategori = '" . $koneksi->real_escape_string($kategori) . "'";
}

if (!empty($tanggal_mulai)) {
    $query .= " AND l.tgl_lelang >= '" . $koneksi->real_escape_string($tanggal_mulai) . "'";
}

if (!empty($tanggal_selesai)) {
    $query .= " AND l.tgl_lelang <= '" . $koneksi->real_escape_string($tanggal_selesai) . "'";
}

$query .= " ORDER BY l.tgl_lelang DESC";
$result = $koneksi->query($query);

$kategoriList = ["Elektronik", "Furnitur", "Pakaian", "Alat", "Kendaraan", "Barang Lainnya"];
?>

<!DOCTYPE html>
<html lang="id">

<head>
  <meta charset="UTF-8">
  <title>Laporan Lelang</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
    flex-direction: row;
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

  .judul {
    padding-top : 40px;
                font-size: 30px;
            font-weight: bold;
            color: #2a7fc1;
            margin-bottom : 15px;
            
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

/* Gambar Barang */
 .content {
      flex: 1;
      padding: 40px;
      background-color: #e6e6e6;
    }

/* Styling untuk Deskripsi Barang */
.content p {
    font-size: 16px;
    line-height: 1.6;
    margin-bottom: 15px;
}

.content p strong {
    font-weight: bold;
}

/* Tabel Riwayat Bid */
.table {
    margin-top: 20px;
    border: 1px solid #ddd;
    border-collapse: collapse;
}

.table th,
.table td {
    padding: 12px;
    text-align: left;
    border-bottom: 1px solid #ddd;
    vertical-align: middle !important;
}

.table th {
    background-color: #f4f4f4;
    font-weight: bold;
}

.table td {
    background-color: white;
}

.table tbody tr:hover {
    background-color: #f1f1f1;
}

/* Tombol Lelang */
.btn-danger {
    background-color: #e74c3c;
    border: none;
    color: white;
    font-size: 16px;
    padding: 10px 20px;
    cursor: pointer;
}

.btn-danger:hover {
    background-color: #c0392b;
}

/* Badge Status Lelang */
.badge {
    font-size: 14px;
    padding: 8px 12px;
    border-radius: 15px;
    color: white;
}

.bg-success {
    background-color: #2ecc71;
}

.bg-danger {
    background-color: #e74c3c;
}



  </style>
</head>

<body>

  <?php include('sidebar.php'); ?>
 

 <div class="main">
    
    <?php include('topbar.php'); ?>
<h2 class="judul">Laporan Lelang</h2>

    <form method="get" class="row g-3 mb-4">
      <div class="col-md-3">
        <label for="kategori" class="form-label">Kategori</label>
        <select name="kategori" id="kategori" class="form-select">
          <option value="">-- Semua Kategori --</option>
          <?php foreach ($kategoriList as $kategoriItem): ?>
            <option value="<?= $kategoriItem ?>" <?= ($kategori === $kategoriItem) ? 'selected' : '' ?>>
              <?= htmlspecialchars($kategoriItem) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>
      <div class="col-md-3">
        <label for="tanggal_mulai" class="form-label">Tanggal Mulai</label>
        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control" value="<?= htmlspecialchars($tanggal_mulai) ?>">
      </div>
      <div class="col-md-3">
        <label for="tanggal_selesai" class="form-label">Tanggal Selesai</label>
        <input type="date" name="tanggal_selesai" id="tanggal_selesai" class="form-control" value="<?= htmlspecialchars($tanggal_selesai) ?>">
      </div>
      <div class="col-md-3 align-self-end">
        <button type="submit" class="btn btn-primary">Filter</button>
        <a href="laporanLelang.php" class="btn btn-secondary">Reset</a>
      </div>
    </form>

    <?php if ($result->num_rows > 0): ?>
      <div class="table-responsive">
        <table class="table table-bordered table-hover bg-white">
          <thead class="table-light">
            <tr>
              <th>No</th>
              <th>Nama Barang</th>
              <th>Kategori</th>
              <th>Harga Awal</th>
              <th>Tanggal Lelang</th>
              <th>Status</th>
               <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            <?php $no = 1; while ($row = $result->fetch_assoc()): ?>
              <tr>
                <td><?= $no++ ?></td>
                <td><?= htmlspecialchars($row['nama_barang']) ?></td>
                <td><?= htmlspecialchars($row['kategori']) ?></td>
                <td>Rp<?= number_format($row['harga_awal'], 0, ',', '.') ?></td>
                <td><?= date("d M Y", strtotime($row['tgl_lelang'])) ?></td>
                <td>
                  <span class="badge <?= $row['status'] === 'dibuka' ? 'bg-success' : 'bg-danger' ?>">
    <?= ucfirst($row['status']) ?>
  </span>
</td>
<td>
<a href="cetakInvoice.php?id_lelang=<?= $row['id_lelang'] ?>" class="badge bg-success text-decoration-none">
      <i class="fas fa-print"></i> Cetak
    </a>
    <a href="cetakInvoicePdf.php?id_lelang=<?= $row['id_lelang'] ?>" target="_blank" class="badge bg-danger text-decoration-none">
      <i class="fas fa-file-pdf"></i> PDF
    </a>
                </td>
              </tr>
            <?php endwhile; ?>
          </tbody>
        </table>
      </div>
    <?php else: ?>
      <div class="alert alert-warning">Data lelang tidak ditemukan.</div>
    <?php endif; ?>
  </div>

</body>
</html>
