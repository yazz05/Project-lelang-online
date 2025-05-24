<?php
require '../dompdf/vendor/autoload.php';
use Dompdf\Dompdf;

// Koneksi dan data
$koneksi = new mysqli("localhost", "root", "", "lelang_online");
$id_lelang = $_GET['id_lelang'] ?? 0;

$query = "
    SELECT l.*, b.nama_barang, b.kategori, b.harga_awal, b.foto_barang, 
           m.nama_lengkap, l.harga_akhir 
    FROM tb_lelang l
    JOIN tb_barang b ON l.id_barang = b.id_barang
    LEFT JOIN tb_masyarakat m ON l.id_user = m.id_user
    WHERE l.id_lelang = '$id_lelang'
";
$result = $koneksi->query($query);
$data = $result->fetch_assoc();

// Path ke folder gambar (sesuaikan sesuai struktur project kamu)
$baseImagePath = realpath(__DIR__ . '/../uploads/') . '/'; // GANTI SESUAI FOLDER UPLOAD

// Pastikan file gambar benar
$imgFullPath = $baseImagePath . ($data['foto_barang'] ?? '');
$imageExists = file_exists($imgFullPath);

// Convert image ke data URI jika file ditemukan
$imageDataUri = '';
if ($imageExists) {
    $imageType = pathinfo($imgFullPath, PATHINFO_EXTENSION);
    $imageContent = file_get_contents($imgFullPath);
    $imageBase64 = base64_encode($imageContent);
    $imageDataUri = "data:image/{$imageType};base64,{$imageBase64}";
}

// HTML untuk invoice
$html = '
<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <style>
    body { font-family: sans-serif; font-size: 14px; padding: 20px; }
    h2 { color: #2a7fc1; margin-bottom: 20px; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
    th, td { text-align: left; padding: 8px; border-bottom: 1px solid #ccc; }
    .badge { display: inline-block; font-size: 12px; padding: 6px 10px; border-radius: 12px; color: #fff; }
    .bg-success { background-color: #2ecc71; }
    .bg-danger { background-color: #e74c3c; }
    .footer { font-size: 12px; color: #777; margin-top: 30px; }
    .price-section { 
      margin: 20px 0; 
      padding: 15px;
      background-color: #f8f9fa;
      border-left: 4px solid #2a7fc1;
    }
    .price-row { 
      display: flex; 
      justify-content: space-between;
      margin: 8px 0;
    }
    .price-label { font-weight: bold; color: #555; }
    .price-value { font-weight: bold; }
    .price-difference {
      margin-top: 10px;
      padding-top: 10px;
      border-top: 1px dashed #ccc;
      font-weight: bold;
      color: #2a7fc1;
    }
    .barang-image {
      text-align: center;
      margin-bottom: 25px;
    }
    .barang-image img {
      max-width: 400px;
      height: auto;
      border: 1px solid #ddd;
      border-radius: 8px;
    }
  </style>
</head>
<body>
  <h2>Invoice Lelang</h2>';

if ($data) {
    // Tambahkan gambar jika tersedia
    if (!empty($imageDataUri)) {
        $html .= '
        <div class="barang-image">
          <img src="' . $imageDataUri . '" alt="Foto Barang">
        </div>';
    }

    $statusBadge = $data['status'] === 'dibuka' ? 'bg-success' : 'bg-danger';
    $html .= '
    <table>
      <tr><th>Nama Barang</th><td>' . htmlspecialchars($data['nama_barang']) . '</td></tr>
      <tr><th>Kategori</th><td>' . htmlspecialchars($data['kategori']) . '</td></tr>
      <tr><th>Tanggal Lelang</th><td>' . date("d M Y", strtotime($data['tgl_lelang'])) . '</td></tr>
      <tr><th>Status</th><td><span class="badge ' . $statusBadge . '">' . ucfirst($data['status']) . '</span></td></tr>';

    if ($data['status'] === 'ditutup') {
        $html .= '
        <tr><th>Pemenang</th><td>' . htmlspecialchars($data['nama_lengkap'] ?? '-') . '</td></tr>';
    }

    $html .= '</table>';

    // Price section
    $html .= '
    <div class="price-section">
      <div class="price-row">
        <span class="price-label">Harga Awal:</span>
        <span class="price-value">Rp' . number_format($data['harga_awal'], 0, ',', '.') . '</span>
      </div>
      <div class="price-row">
        <span class="price-label">Harga Akhir:</span>
        <span class="price-value">Rp' . number_format($data['harga_akhir'] ?? $data['harga_awal'], 0, ',', '.') . '</span>
      </div>';

    if ($data['status'] === 'ditutup' && $data['harga_akhir'] > $data['harga_awal']) {
        $difference = $data['harga_akhir'] - $data['harga_awal'];
        $percentage = round(($difference / $data['harga_awal']) * 100, 2);
        $html .= '
        <div class="price-difference">
          Kenaikan: Rp' . number_format($difference, 0, ',', '.') . ' (' . $percentage . '%)
        </div>';
    }

    $html .= '</div>';
} else {
    $html .= '<p>Data tidak ditemukan.</p>';
}

$html .= '<div class="footer">LELON &copy; ' . date('Y') . '</div>
</body></html>';

// Generate PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();
$dompdf->stream("invoice-lelang.pdf", ["Attachment" => false]);
