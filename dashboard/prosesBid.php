<?php
session_start();
include '../login/koneksi.php';

// Pastikan user sudah login
if (!isset($_SESSION['id_user'])) {
    header("Location: ../login/login.php");
    exit;
}

$id_user = $_SESSION['id_user'];
$id_lelang = $_POST['id_lelang'];
$id_barang = $_POST['id_barang'];
$penawaran = $_POST['penawaran_harga'];

// ==============================================
// VALIDASI HARGA MINIMAL (SERVER-SIDE)
// ==============================================
// Ambil harga minimal dari database
$query_min = $koneksi->prepare("
    SELECT 
        b.harga_awal,
        COALESCE(MAX(h.penawaran_harga), 0) AS max_terakhir
    FROM tb_barang b
    LEFT JOIN history_lelang h ON b.id_barang = h.id_barang
    WHERE b.id_barang = ?
");
$query_min->bind_param("i", $id_barang);
$query_min->execute();
$result = $query_min->get_result()->fetch_assoc();

$harga_min = max($result['harga_awal'], $result['max_terakhir'] + 1);

// Validasi 1: Nilai dasar
if (empty($penawaran) || $penawaran <= 0) {
    header("Location: sesiLelang.php?id=$id_barang&error=invalid_bid");
    exit;
}

// Validasi 2: Harus lebih besar dari harga minimal
if ($penawaran < $harga_min) {
    header("Location: sesiLelang.php?id=$id_barang&error=bid_too_low&min=" . $harga_min);
    exit;
}

// ==============================================
// SIMPAN KE DATABASE
// ==============================================
$tanggal = date('Y-m-d H:i:s');

$stmt = $koneksi->prepare("
    INSERT INTO history_lelang (id_lelang, id_barang, id_user, penawaran_harga, created_at)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("iiids", $id_lelang, $id_barang, $id_user, $penawaran, $tanggal);
$stmt->execute();
$stmt->close();

header("Location: sesiLelang.php?id=$id_barang&success=1");
exit;
