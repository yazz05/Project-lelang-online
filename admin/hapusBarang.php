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

// Mengambil ID barang dari URL
$id_barang = $_GET['id'] ?? null;

if ($id_barang) {
    // Query untuk menghapus data barang berdasarkan ID
    $query = "DELETE FROM tb_barang WHERE id_barang = ?";
    
    // Persiapkan dan eksekusi query
    if ($stmt = mysqli_prepare($koneksi, $query)) {
        mysqli_stmt_bind_param($stmt, "i", $id_barang); // Bind parameter ID
        if (mysqli_stmt_execute($stmt)) {
            // Jika sukses menghapus, redirect ke halaman data barang
            echo "<script>
                    alert('Barang berhasil dihapus.');
                    window.location.href = 'barang.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Gagal menghapus barang.');
                    window.location.href = 'barang.php';
                  </script>";
        }
        mysqli_stmt_close($stmt);
    } else {
        echo "<script>
                alert('Query gagal.');
                window.location.href = 'barang.php';
              </script>";
    }
} else {
    echo "<script>
            alert('ID barang tidak valid.');
            window.location.href = 'barang.php';
          </script>";
}

mysqli_close($koneksi);
?>
