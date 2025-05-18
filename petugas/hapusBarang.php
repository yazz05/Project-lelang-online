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
    // Cek apakah lelang barang ini sudah selesai dan punya pemenang
    $cekLelang = "SELECT * FROM tb_lelang WHERE id_barang = ? AND status = 'ditutup' AND id_user IS NOT NULL";
    if ($stmt = mysqli_prepare($koneksi, $cekLelang)) {
        mysqli_stmt_bind_param($stmt, "i", $id_barang);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);

        if (mysqli_stmt_num_rows($stmt) > 0) {
            // Hapus history lelang terlebih dahulu
            $hapusHistory = "DELETE FROM history_lelang WHERE id_barang = ?";
            if ($stmtHistory = mysqli_prepare($koneksi, $hapusHistory)) {
                mysqli_stmt_bind_param($stmtHistory, "i", $id_barang);
                mysqli_stmt_execute($stmtHistory);
                mysqli_stmt_close($stmtHistory);
            }

            // Hapus juga data lelang
            $hapusLelang = "DELETE FROM tb_lelang WHERE id_barang = ?";
            if ($stmtLelang = mysqli_prepare($koneksi, $hapusLelang)) {
                mysqli_stmt_bind_param($stmtLelang, "i", $id_barang);
                mysqli_stmt_execute($stmtLelang);
                mysqli_stmt_close($stmtLelang);
            }

            // Hapus barang dari tb_barang
            $hapusBarang = "DELETE FROM tb_barang WHERE id_barang = ?";
            if ($stmtDelete = mysqli_prepare($koneksi, $hapusBarang)) {
                mysqli_stmt_bind_param($stmtDelete, "i", $id_barang);
                mysqli_stmt_execute($stmtDelete);
                mysqli_stmt_close($stmtDelete);

                $_SESSION['barang_terhapus'] = true;
            }
        } else {
            // Jika lelang masih aktif atau belum ditentukan pemenang
            $_SESSION['lelang_aktif'] = true;
        }

        mysqli_stmt_close($stmt);
        header("Location: barang.php");
        exit();
    }
} else {
    $_SESSION['lelang_aktif'] = true; // fallback jika tidak ada ID
    header("Location: barang.php");
    exit();
}

mysqli_close($koneksi);
