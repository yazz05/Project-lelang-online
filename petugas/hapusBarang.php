<?php
session_start();
if (!isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit();
}

$koneksi = mysqli_connect("localhost", "root", "", "lelang_online");
if (!$koneksi) {
    die("Koneksi gagal: " . mysqli_connect_error());
}

$id_barang = $_GET['id'] ?? null;

if ($id_barang) {
    $cekLelang = "SELECT * FROM tb_lelang WHERE id_barang = ? AND status = 'dibuka'";
    if ($stmt = mysqli_prepare($koneksi, $cekLelang)) {
        mysqli_stmt_bind_param($stmt, "i", $id_barang);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            $_SESSION['lelang_aktif'] = true;
            header("Location: barang.php");
            exit();
        } else {
            $query = "DELETE FROM tb_barang WHERE id_barang = ?";
            if ($stmtDelete = mysqli_prepare($koneksi, $query)) {
                mysqli_stmt_bind_param($stmtDelete, "i", $id_barang);
                mysqli_stmt_execute($stmtDelete);
                mysqli_stmt_close($stmtDelete);
                $_SESSION['barang_terhapus'] = true;
                header("Location: barang.php");
                exit();
            }
        }

        mysqli_stmt_close($stmt);
    }
} else {
    $_SESSION['lelang_aktif'] = true; // fallback jika tidak ada ID
    header("Location: barang.php");
    exit();
}

mysqli_close($koneksi);
