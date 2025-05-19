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
    // Cek apakah lelang sedang berlangsung
    $cekLelang = "SELECT status FROM tb_lelang WHERE id_barang = ?";
    if ($stmt = mysqli_prepare($koneksi, $cekLelang)) {
        mysqli_stmt_bind_param($stmt, "i", $id_barang);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_bind_result($stmt, $status_lelang);
        if (mysqli_stmt_fetch($stmt)) {
            if ($status_lelang === 'dibuka') {
                $_SESSION['lelang_aktif'] = true;
                mysqli_stmt_close($stmt);
                header("Location: barang.php");
                exit();
            }
        }
        mysqli_stmt_close($stmt);
    }

    // Hapus history lelang
    $hapusHistory = "DELETE FROM history_lelang WHERE id_barang = ?";
    if ($stmtHistory = mysqli_prepare($koneksi, $hapusHistory)) {
        mysqli_stmt_bind_param($stmtHistory, "i", $id_barang);
        mysqli_stmt_execute($stmtHistory);
        mysqli_stmt_close($stmtHistory);
    }

    // Hapus data lelang
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

        $_SESSION['lelang_dan_barang_terhapus'] = true;
    }

    header("Location: barang.php");
    exit();
} else {
    $_SESSION['lelang_aktif'] = true;
    header("Location: barang.php");
    exit();
}

mysqli_close($koneksi);
