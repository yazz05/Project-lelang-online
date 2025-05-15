<?php
session_start();
$koneksi = new mysqli("localhost", "root", "", "lelang_online");

if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$id_lelang = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($id_lelang <= 0) {
    die("ID lelang tidak valid.");
}

// Ambil data lelang dan pemenang tertinggi
$stmt = $koneksi->prepare("
    SELECT l.*, b.nama_barang, b.foto_barang, b.deskripsi_barang, b.harga_awal,
           h.penawaran_harga, h.id_user, m.nama_lengkap
    FROM tb_lelang l
    JOIN tb_barang b ON l.id_barang = b.id_barang
    LEFT JOIN history_lelang h ON h.id_lelang = l.id_lelang
    LEFT JOIN tb_masyarakat m ON h.id_user = m.id_user
    WHERE l.id_lelang = ?
    ORDER BY h.penawaran_harga DESC
    LIMIT 1
");
$stmt->bind_param("i", $id_lelang);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();
$stmt->close();

if (!$data) {
    die("Data lelang atau pemenang tidak ditemukan.");
}

// Proses penutupan lelang dan update pemenang
if (isset($_POST['konfirmasi'])) {
    $id_pemenang = isset($_POST['id_user']) ? (int)$_POST['id_user'] : 0;
    $harga_akhir = isset($_POST['harga_akhir']) ? (int)$_POST['harga_akhir'] : 0;

    if ($id_pemenang > 0 && $harga_akhir > 0) {
        $stmt = $koneksi->prepare("UPDATE tb_lelang SET status = 'ditutup', id_user = ?, harga_akhir = ? WHERE id_lelang = ?");
        $stmt->bind_param("iii", $id_pemenang, $harga_akhir, $id_lelang);
        if ($stmt->execute()) {
            echo "<script>alert('Lelang berhasil ditutup dan pemenang telah dipilih!'); window.location='lelang.php';</script>";
            exit;
        } else {
            echo "Update error: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "<script>alert('Data pemenang tidak valid.');</script>";
    }
}
?>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8" />
    <title>Pilih Pemenang Lelang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
</head>

<body>
    <div class="container mt-5">
        <h3>Pilih Pemenang untuk: <?= htmlspecialchars($data['nama_barang']) ?></h3>
        <p><strong>Deskripsi:</strong> <?= htmlspecialchars($data['deskripsi_barang']) ?></p>
        <p><strong>Harga Awal:</strong> Rp<?= number_format($data['harga_awal'], 0, ',', '.') ?></p>

        <h4>Penawaran Tertinggi:</h4>
        <p><strong>Nama Pemenang Sementara:</strong> <?= htmlspecialchars($data['nama_lengkap']) ?></p>
        <p><strong>Harga Akhir:</strong> Rp<?= number_format($data['penawaran_harga'], 0, ',', '.') ?></p>

        <form method="POST">
            <input type="hidden" name="id_user" value="<?= (int)$data['id_user'] ?>" />
            <input type="hidden" name="harga_akhir" value="<?= (int)$data['penawaran_harga'] ?>" />
            <button type="submit" name="konfirmasi" class="btn btn-success">Konfirmasi & Akhiri Lelang</button>
            <a href="detailLelang.php?id=<?= $id_lelang ?>" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</body>

</html>
