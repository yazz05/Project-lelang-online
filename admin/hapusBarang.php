<?php
session_start();
if (!isset($_SESSION['nama'])) {
    header("Location: ../login/login.php");
    exit();
}

$koneksi = mysqli_connect("localhost", "root", "", "lelang_online");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$id_barang = $_GET['id'] ?? null;

if ($id_barang) {
    // Hapus history lelang jika ada
    $hapusHistory = "DELETE FROM history_lelang WHERE id_barang = ?";
    if ($stmtHistory = mysqli_prepare($koneksi, $hapusHistory)) {
        mysqli_stmt_bind_param($stmtHistory, "i", $id_barang);
        mysqli_stmt_execute($stmtHistory);
        mysqli_stmt_close($stmtHistory);
    }

    // Hapus data lelang jika ada
    $hapusLelang = "DELETE FROM tb_lelang WHERE id_barang = ?";
    if ($stmtLelang = mysqli_prepare($koneksi, $hapusLelang)) {
        mysqli_stmt_bind_param($stmtLelang, "i", $id_barang);
        mysqli_stmt_execute($stmtLelang);
        mysqli_stmt_close($stmtLelang);
    }

    // Hapus barang
    $hapusBarang = "DELETE FROM tb_barang WHERE id_barang = ?";
    if ($stmtDelete = mysqli_prepare($koneksi, $hapusBarang)) {
        mysqli_stmt_bind_param($stmtDelete, "i", $id_barang);
        mysqli_stmt_execute($stmtDelete);
        mysqli_stmt_close($stmtDelete);

        // Set notifikasi sukses
        $_SESSION['success'] = "Barang sudah dihapus.";
    }

    header("Location: barang.php");
    exit();
} else {
    $_SESSION['error'] = "Barang tidak ditemukan.";
    header("Location: barang.php");
    exit();
}

mysqli_close($koneksi);
