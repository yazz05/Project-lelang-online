<?php
session_start();

// Cek apakah petugas sudah login
if (!isset($_SESSION['id_petugas']) || !isset($_SESSION['id_level'])) {
    die("Akses ditolak. Anda harus login.");
}

if ($_SESSION['id_level'] != 1 && $_SESSION['id_level'] != 2) {
    die("Akses hanya untuk petugas atau admin.");
}


// Koneksi database
$koneksi = new mysqli("localhost", "root", "", "lelang_online");

// Cek koneksi
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

// Ambil data dari form POST
$id_petugas = $_SESSION['id_petugas'];
$id_user = $_POST['id_user'] ?? 0;
$id_lelang = $_POST['id_lelang'] ?? 0;
$subjek = $_POST['subjek'] ?? '';
$pesan = $_POST['pesan'] ?? '';

// Validasi dasar
if (empty($id_user) || empty($id_lelang) || empty($pesan)) {
    die("Semua data harus diisi.");
}

// Simpan ke tabel tb_notifikasi
$stmt = $koneksi->prepare("INSERT INTO tb_notif (id_petugas, id_user, id_lelang, pesan, created_at) VALUES (?, ?, ?, ?, NOW())");
$stmt->bind_param("iiis", $id_petugas, $id_user, $id_lelang, $pesan);

if ($stmt->execute()) {
    // Redirect kembali ke detail lelang
    header("Location: detailLelang.php?id=" . $id_lelang . "&notif=berhasil");
    exit;
} else {
    echo "Gagal mengirim notifikasi: " . $stmt->error;
}

$stmt->close();
$koneksi->close();
