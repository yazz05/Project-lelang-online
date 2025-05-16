<?php
require '../dompdf/vendor/autoload.php'; // path ke autoload Composer
use Dompdf\Dompdf;

// Koneksi dan data
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
  </style>
</head>
<body>
  <h2>Invoice Lelang</h2>';

if ($data) {
    $statusBadge = $data['status'] === 'dibuka' ? 'bg-success' : 'bg-danger';
    $html .= '
    <table>
      <tr><th>Nama Barang</th><td>' . htmlspecialchars($data['nama_barang']) . '</td></tr>
      <tr><th>Kategori</th><td>' . htmlspecialchars($data['kategori']) . '</td></tr>
      <tr><th>Harga Awal</th><td>Rp' . number_format($data['harga_awal'], 0, ',', '.') . '</td></tr>
      <tr><th>Tanggal Lelang</th><td>' . date("d M Y", strtotime($data['tgl_lelang'])) . '</td></tr>
      <tr><th>Status</th><td><span class="badge ' . $statusBadge . '">' . ucfirst($data['status']) . '</span></td></tr>';
    
    if ($data['status'] === 'ditutup') {
        $html .= '
        <tr><th>Pemenang</th><td>' . htmlspecialchars($data['nama_lengkap'] ?? '-') . '</td></tr>
        <tr><th>Harga Akhir</th><td>Rp' . number_format($data['harga_akhir'], 0, ',', '.') . '</td></tr>';
    }

    $html .= '</table>';
} else {
    $html .= '<p>Data tidak ditemukan.</p>';
}

$html .= '<div class="footer">LELON &copy; ' . date('Y') . '</div>
</body></html>';

// Buat dan render PDF
$dompdf = new Dompdf();
$dompdf->loadHtml($html);
$dompdf->setPaper('A4', 'portrait');
$dompdf->render();

// Output ke browser
$dompdf->stream("invoice-lelang.pdf", ["Attachment" => false]); // true untuk download langsung
