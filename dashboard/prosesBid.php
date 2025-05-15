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
$tanggal = date('Y-m-d H:i:s');

// Validasi nilai penawaran (misalnya tidak boleh kosong atau negatif)
if (empty($penawaran) || $penawaran <= 0) {
    // Redirect kembali dengan pesan error (jika ingin)
    header("Location: sesiLelang.php?id=$id_barang&error=invalid_bid");
    exit;
}

// Simpan ke history_lelang
$tanggal = date('Y-m-d H:i:s');

$stmt = $koneksi->prepare("
    INSERT INTO history_lelang (id_lelang, id_barang, id_user, penawaran_harga, created_at)
    VALUES (?, ?, ?, ?, ?)
");
$stmt->bind_param("iiids", $id_lelang, $id_barang, $id_user, $penawaran, $tanggal);
$stmt->execute();
$stmt->close();

header("Location: sesiLelang.php?id=$id_barang");
exit;
?>
