<?php
$koneksi = new mysqli("localhost", "root", "", "lelang_online");
$id_lelang = $_GET['id_lelang'] ?? 0;

$query = "
    SELECT l.*, b.nama_barang, b.kategori, b.harga_awal, m.nama_lengkap, l.harga_akhir 
    FROM tb_lelang l
    JOIN tb_barang b ON l.id_barang = b.id_barang
    LEFT JOIN tb_masyarakat m ON l.id_user = m.id_user
    WHERE l.id_lelang = '$id_lelang'
";

$result = $koneksi->query($query);
$data = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <title>Invoice Lelang</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f9f9f9;
      padding: 40px;
      color: #333;
    }

    .invoice-box {
      background: #fff;
      max-width: 800px;
      margin: auto;
      padding: 30px;
      border-radius: 10px;
      box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }

    .header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      border-bottom: 2px solid #2a7fc1;
      padding-bottom: 10px;
      margin-bottom: 30px;
    }

    .logo {
      height: 60px;
    }

    .title {
      font-size: 24px;
      font-weight: bold;
      color: #2a7fc1;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    th, td {
      padding: 12px;
      text-align: left;
      border-bottom: 1px solid #eee;
    }

    th {
      background-color: #f2f2f2;
      width: 200px;
    }

    .badge {
      display: inline-block;
      padding: 6px 12px;
      font-size: 13px;
      border-radius: 20px;
      color: white;
    }

    .bg-success {
      background-color: #27ae60;
    }

    .bg-danger {
      background-color: #c0392b;
    }

    .price-comparison {
      margin: 20px 0;
      padding: 15px;
      background-color: #f8f9fa;
      border-radius: 8px;
      border-left: 4px solid #2a7fc1;
    }

    .price-row {
      display: flex;
      justify-content: space-between;
      margin: 8px 0;
    }

    .price-label {
      font-weight: bold;
      color: #555;
    }

    .price-value {
      font-weight: bold;
    }

    .price-difference {
      margin-top: 10px;
      padding-top: 10px;
      border-top: 1px dashed #ccc;
      font-weight: bold;
      color: #2a7fc1;
    }

    .footer {
      text-align: center;
      margin-top: 40px;
      font-size: 13px;
      color: #777;
    }

    .print-btn {
      margin-top: 30px;
      display: inline-block;
      background-color: #2a7fc1;
      color: white;
      padding: 10px 20px;
      text-decoration: none;
      border-radius: 6px;
      font-size: 14px;
    }

    .print-btn:hover {
      background-color: #1c5fa3;
    }

    @media print {
      .no-print {
        display: none;
      }
      body {
        background: white;
      }
      .invoice-box {
        box-shadow: none;
        margin: 0;
      }
    }
  </style>
</head>
<body>

<div class="invoice-box">
  <div class="header">
    <img src="../dashboard/img/lelonHitam.png" alt="Logo" class="logo">
    <div class="title">Invoice Lelang</div>
  </div>

  <?php if ($data): ?>
    <table>
      <tr>
        <th>Nama Barang</th>
        <td><?= htmlspecialchars($data['nama_barang']) ?></td>
      </tr>
      <tr>
        <th>Kategori</th>
        <td><?= htmlspecialchars($data['kategori']) ?></td>
      </tr>
      <tr>
        <th>Tanggal Lelang</th>
        <td><?= date("d M Y", strtotime($data['tgl_lelang'])) ?></td>
      </tr>
      <tr>
        <th>Status</th>
        <td>
          <span class="badge <?= strtolower($data['status']) === 'dibuka' ? 'bg-success' : 'bg-danger' ?>">
            <?= ucfirst($data['status']) ?>
          </span>
        </td>
      </tr>
    </table>

    <div class="price-comparison">
      <div class="price-row">
        <span class="price-label">Harga Awal:</span>
        <span class="price-value">Rp<?= number_format($data['harga_awal'], 0, ',', '.') ?></span>
      </div>
      <div class="price-row">
        <span class="price-label">Harga Akhir:</span>
        <span class="price-value">Rp<?= number_format($data['harga_akhir'] ?? $data['harga_awal'], 0, ',', '.') ?></span>
      </div>
      <?php if (isset($data['harga_akhir']) && $data['harga_akhir'] > $data['harga_awal']): ?>
        <div class="price-difference">
          Kenaikan: Rp<?= number_format($data['harga_akhir'] - $data['harga_awal'], 0, ',', '.') ?>
          (<?= round(($data['harga_akhir'] - $data['harga_awal']) / $data['harga_awal'] * 100, 2) ?>%)
        </div>
      <?php endif; ?>
    </div>

    <?php if ($data['status'] === 'ditutup'): ?>
    <table>
      <tr>
        <th>Pemenang</th>
        <td><?= htmlspecialchars($data['nama_lengkap'] ?? '-') ?></td>
      </tr>
    </table>
    <?php endif; ?>

    <div class="no-print">
      <a href="#" onclick="window.print()" class="print-btn">🖨️ Cetak Halaman</a>
    </div>
  <?php else: ?>
    <p>Data tidak ditemukan.</p>
  <?php endif; ?>

  <div class="footer">
    &copy; <?= date('Y') ?> LELON. Semua hak dilindungi.
  </div>
</div>

</body>
</html>
