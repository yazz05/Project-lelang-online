<?php
session_start();
include 'koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Cek ke tb_masyarakat
$query_masyarakat = mysqli_query($koneksi, "SELECT * FROM tb_masyarakat WHERE username='$username' AND password='$password'");
$data_masyarakat = mysqli_fetch_assoc($query_masyarakat);

if ($data_masyarakat) {
    $_SESSION['id_user'] = $data_masyarakat['id_user'];
    $_SESSION['username'] = $data_masyarakat['username'];
    $_SESSION['nama'] = $data_masyarakat['nama_lengkap'];
    header("Location: ../dashboard/index.php");
    exit;
}

// Cek ke tb_petugas (dengan join ke tb_level)
$query_petugas = mysqli_query($koneksi, "
    SELECT p.*, l.level 
    FROM tb_petugas p 
    JOIN tb_level l ON p.id_level = l.id_level 
    WHERE p.username='$username' AND p.password='$password'
");

$data_petugas = mysqli_fetch_assoc($query_petugas);

if ($data_petugas) {
    $_SESSION['username'] = $data_petugas['username'];
    $_SESSION['nama'] = $data_petugas['nama_petugas'];
    $_SESSION['level'] = $data_petugas['level']; // 'administrator' atau 'petugas'
    $_SESSION['id_petugas'] = $data_petugas['id_petugas'];
    $_SESSION['id_level'] = $data_petugas['id_level']; // 1 atau 2

    // Arahkan sesuai level-nya
    if ($data_petugas['level'] == 'administrator') {
        header("Location: ../admin/index.php");
    } else {
        header("Location: ../petugas/index.php");
    }
    exit;
}

// Jika tidak ditemukan di kedua tabel
header("Location: gagalLogin.php");
exit;
?>
