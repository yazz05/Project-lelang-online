<?php
include 'koneksi.php';
session_start();

// Pastikan user login
if (!isset($_SESSION['id_user'])) {
  header("Location: login.php");
  exit;
}

$id_user = $_SESSION['id_user'];
$id_lelang = $_POST['id_lelang'];
$id_barang = $_POST['id_barang'];
$penawaran = $_POST['penawaran_harga'];
$tanggal = date('Y-m-d H:i:s');

$koneksi->query("
  INSERT INTO history_lelang (id_lelang, id_barang, id_user, penawaran_harga, tgl_lelang)
  VALUES ('$id_lelang', '$id_barang', '$id_user', '$penawaran', '$tanggal')
");

header("Location: sesiLelang.php?id=$id_barang");
