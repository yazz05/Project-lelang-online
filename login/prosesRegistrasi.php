<?php
// Koneksi ke database
$host = "localhost";
$user = "root";
$pass = ""; // Ubah jika ada password
$db   = "lelang_online";

$conn = new mysqli($host, $user, $pass, $db);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$nama_lengkap = $_POST['nama_lengkap'];
$username     = $_POST['username'];
$password     = $_POST['password']; // Tidak mengenkripsi password
$telp         = $_POST['telp'];

// Query insert
$sql = "INSERT INTO tb_masyarakat (nama_lengkap, username, password, telp)
        VALUES ('$nama_lengkap', '$username', '$password', '$telp')";

if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Registrasi berhasil! Silakan login.'); location='../login/login.php';</script>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
