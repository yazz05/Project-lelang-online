<?php
session_start();
include 'koneksi.php';

$username = $_POST['username'];
$password = $_POST['password'];

// Cek ke tb_masyarakat
$query_masyarakat = mysqli_query($conn, "SELECT * FROM tb_masyarakat WHERE username='$username' AND password='$password'");
$data_masyarakat = mysqli_fetch_assoc($query_masyarakat);

if ($data_masyarakat) {
    $_SESSION['username'] = $data_masyarakat['username'];
    $_SESSION['nama'] = $data_masyarakat['nama_lengkap'];
    $_SESSION['level'] = 'masyarakat';
    header("Location: ../dashboard/index.php");
    exit;
}

// Cek ke tb_petugas
$query_petugas = mysqli_query($conn, "SELECT * FROM tb_petugas WHERE username='$username' AND password='$password'");
$data_petugas = mysqli_fetch_assoc($query_petugas);

if ($data_petugas) {
    $_SESSION['username'] = $data_petugas['username'];
    $_SESSION['nama'] = $data_petugas['nama_petugas'];
    $_SESSION['level'] = 'petugas'; // bisa ditambah level admin jika pakai tb_level
    header("Location: ../admin/index.php");
    exit;
}

// Jika tidak ditemukan
echo "Login gagal. Username atau password salah.";
