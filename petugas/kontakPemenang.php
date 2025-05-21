<?php
session_start();
$koneksi = new mysqli("localhost", "root", "", "lelang_online");

// Check connection
if ($koneksi->connect_error) {
    die("Connection failed: " . $koneksi->connect_error);
}

$id_lelang = $_GET['id_lelang'] ?? 0;
$id_user = $_GET['id_user'] ?? 0;

// Get winner information
$query = "SELECT nama_lengkap, username FROM tb_masyarakat WHERE id_user = ?";
$stmt = $koneksi->prepare($query);

if (!$stmt) {
    die("Error in prepare: " . $koneksi->error);
}

$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
$pemenang = $result->fetch_assoc();
$stmt->close();

// Get item information for this auction
$query = "SELECT tp.nama_barang 
          FROM tb_lelang tl
          JOIN tb_barang tp ON tl.id_barang = tp.id_barang
          WHERE tl.id_lelang = ?";
$stmt = $koneksi->prepare($query);

if (!$stmt) {
    die("Error in prepare: " . $koneksi->error);
}

$stmt->bind_param("i", $id_lelang);
$stmt->execute();
$result = $stmt->get_result();
$barang = $result->fetch_assoc();
$stmt->close();
?>

<!DOCTYPE html>
<html lang="id">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
<style>
    /* Masukkan style sidebar dan topbar yang kamu kasih */
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: 'Segoe UI', sans-serif;
    }

    body {
        display: flex;
        min-height: 100vh;
        background-color: #e6e6e6;
        margin: 0;
        padding: 0;
    }

    .sidebar {
        width: 250px;
        background-color: #f4f4f4;
        border-right: 1px solid #ccc;
        padding: 20px 0;
        position: fixed;
        top: 0;
        left: 0;
        height: 100vh;
        overflow-y: auto;
    }

    .sidebar h2 {
        text-align: center;
        color: white;
        background-color: #2a7fc1;
        padding: 15px 0;
    }

    .sidebar a {
        display: block;
        padding: 10px 20px;
        color: #333;
        text-decoration: none;
        font-weight: bold;
    }

    .sidebar a:hover,
    .sidebar-link:hover {
        background-color: #d0e8f8;
    }

    .sidebar .section {
        padding: 10px 20px;
        font-weight: bold;
        color: #666;
    }

    .sidebar-link {
        display: block;
        padding: 8px 10px;
        color: #333;
        text-decoration: none;
        font-weight: normal;
    }

    .main {
        flex: 1;
        margin-left: 250px;
        padding: 20px;
        min-height: 100vh;
        background-color: #e6e6e6;
    }

    .topbar {
        background-color: #2a7fc1;
        padding: 15px;
        display: flex;
        justify-content: space-between;
        color: white;
    }

    h3 {
        margin-top: 20px;
        color: #2a7fc1;
        font-weight: bold;
    }
</style>

<body>
    <?php include 'sidebar.php'; ?>

    <div class="main">
        <?php include 'topbar.php'; ?>
        <h3>Kontak Pemenang Lelang</h3>
        <p>Anda akan menghubungi <strong><?= htmlspecialchars($pemenang['nama_lengkap'] ?? 'Pemenang'); ?></strong></p>

        <form action="kirimNotifikasi.php" method="post">
            <input type="hidden" name="id_user" value="<?= $id_user ?>">
            <input type="hidden" name="id_lelang" value="<?= $id_lelang ?>">

            <div class="mb-3">
                <label for="subjek" class="form-label">Subjek Pesan</label>
                <input type="text" name="subjek" id="subjek" class="form-control" required value="Selamat! Anda Menang Lelang">
            </div>

            <div class="mb-3">
                <label for="pesan" class="form-label">Isi Pesan</label>
                <textarea name="pesan" id="pesan" class="form-control" rows="5" required>Selamat <?= htmlspecialchars($pemenang['nama_lengkap'] ?? 'Pemenang'); ?>,
Anda telah memenangkan lelang untuk barang: <?= htmlspecialchars($barang['nama_barang'] ?? 'Barang Lelang'); ?>.

Silakan hubungi kami untuk proses lebih lanjut.</textarea>
            </div>

            <button type="submit" class="btn btn-success">Kirim Notifikasi</button>
            <a href="detailLelang.php?id=<?= $id_lelang ?>" class="btn btn-secondary">Kembali</a>
        </form>
    </div>

    <script>
        function logoutAlert() {
            if (confirm("Yakin ingin logout?")) {
                window.location.href = 'logoutPetugas.php';
            }
        }
    </script>
</body>

</html>