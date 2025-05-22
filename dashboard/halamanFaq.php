<?php
session_start();

// Cek login
if (!isset($_SESSION['nama'])) {
    header("Location: login.php");
    exit();
}

$koneksi = new mysqli("localhost", "root", "", "lelang_online");
if ($koneksi->connect_error) {
    die("Koneksi gagal: " . $koneksi->connect_error);
}

$id_user = $_SESSION['id_user'] ?? 0;
$notifikasi = [];
$jumlah_notif = 0;

if ($id_user) {
    $stmt = $koneksi->prepare("SELECT id_notif, pesan, created_at FROM tb_notif WHERE id_user = ? ORDER BY created_at DESC LIMIT 5");
    $stmt->bind_param("i", $id_user);
    $stmt->execute();
    $result = $stmt->get_result();
    $notifikasi = $result->fetch_all(MYSQLI_ASSOC);
    $jumlah_notif = count($notifikasi);
    $stmt->close();
}


?>
<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FAQ - Website Lelang</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <link rel="stylesheet" href="style.css" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" />
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
            color: #333;
            padding-top: 20px;
            /* Memberi jarak antara navbar dan konten */
        }

        .maskot-container {
            padding-right: 2rem;
            margin-top: 20px;
            /* Memberi jarak dari navbar */
        }

        .maskot {
            width: 100%;
            max-width: 300px;
            border-radius: 0;
        }

        .faq-header {
            color: #2c3e50;
            margin-top: 4rem;
            margin-bottom: 2rem;
            font-weight: 700;
        }

        .accordion-button {
            font-weight: 600;
            color: #2c3e50;
            background-color: rgba(255, 255, 255, 0.8);
        }

        .accordion-button:not(.collapsed) {
            color: #0d6efd;
            background-color: rgba(13, 110, 253, 0.1);
        }

        .accordion-body {
            line-height: 1.6;
        }

        footer {
            background-color: #2c3e50;
            color: white;
            padding: 2rem 0;
            margin-top: 3rem;
        }

        .maskot-caption {
            font-size: 1.2rem;
            margin-top: 1rem;
            font-weight: 600;
            color: #2c3e50;
            padding: 15px;
            border: 2px solid #2c3e50;
            border-radius: 5px;
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .content-wrapper {
            margin-top: 30px;
            /* Memberi jarak tambahan antara navbar dan konten */
        }

        .navbar {
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <?php include 'navbar.php'; ?>

    <!-- Konten Utama -->
    <div class="container py-5 content-wrapper">
        <div class="row">
            <!-- Kolom Maskot -->
            <div class="col-md-4 mb-4 mb-md-0">
                <div class="maskot-container">
                    <img src="../maskot/maskotPointing.png" alt="Maskot Luna" class="maskot img-fluid">
                    <div class="maskot-caption text-center">Luna disini siap menjawab pertanyaan kamu!</div>
                </div>
            </div>

            <!-- Kolom FAQ -->
            <div class="col-md-8">
                <h1 class="faq-header display-4 mb-4">Pertanyaan yang Sering Diajukan</h1>

                <div class="accordion" id="faqAccordion">
                    <!-- Pertanyaan 1 -->
                    <div class="accordion-item mb-3 shadow-sm rounded">
                        <h2 class="accordion-header" id="headingOne">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
                                Website apa Ini?
                            </button>
                        </h2>
                        <div id="collapseOne" class="accordion-collapse collapse" aria-labelledby="headingOne" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Ini adalah platform lelang online terpercaya yang menghubungkan penjual dan pembeli untuk berbagai macam barang berkualitas. Kami menyediakan sistem lelang yang aman, transparan, dan mudah digunakan untuk semua kebutuhan lelang Anda.
                            </div>
                        </div>
                    </div>

                    <!-- Pertanyaan 2 -->
                    <div class="accordion-item mb-3 shadow-sm rounded">
                        <h2 class="accordion-header" id="headingTwo">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
                                Bagaimana cara melakukan lelang?
                            </button>
                        </h2>
                        <div id="collapseTwo" class="accordion-collapse collapse" aria-labelledby="headingTwo" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <ol>
                                    <li>Daftar atau login ke akun Anda</li>
                                    <li>Cari barang yang ingin Anda ikuti lelangnya</li>
                                    <li>Klik "Ikut Lelang" pada halaman barang</li>
                                    <li>Masukkan nominal tawaran Anda (harus lebih tinggi dari harga saat ini)</li>
                                    <li>Konfirmasi tawaran Anda</li>
                                    <li>Pantau terus lelang hingga waktu berakhir</li>
                                </ol>
                                <p class="mt-2">Pemenang lelang akan dihubungi oleh sistem kami untuk proses pembayaran dan pengiriman.</p>
                            </div>
                        </div>
                    </div>

                    <!-- Pertanyaan 3 -->
                    <div class="accordion-item mb-3 shadow-sm rounded">
                        <h2 class="accordion-header" id="headingThree">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
                                Apakah barang lelang disini bagus?
                            </button>
                        </h2>
                        <div id="collapseThree" class="accordion-collapse collapse" aria-labelledby="headingThree" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                Ya, semua barang yang dilelang di platform kami melalui proses kurasi ketat untuk memastikan kualitas dan keasliannya. Kami bekerja sama dengan penjual terpercaya dan menyediakan deskripsi lengkap serta foto barang dari berbagai sudut. Setiap barang juga dilengkapi dengan informasi kondisi yang transparan.
                            </div>
                        </div>
                    </div>

                    <!-- Pertanyaan 4 -->
                    <div class="accordion-item mb-3 shadow-sm rounded">
                        <h2 class="accordion-header" id="headingFour">
                            <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
                                Apakah website ini terpercaya?
                            </button>
                        </h2>
                        <div id="collapseFour" class="accordion-collapse collapse" aria-labelledby="headingFour" data-bs-parent="#faqAccordion">
                            <div class="accordion-body">
                                <p>Website kami sangat terpercaya dengan:</p>
                                <ul>
                                    <li>Sistem keamanan transaksi berbasis enkripsi</li>
                                    <li>Proteksi data pengguna yang ketat</li>
                                    <li>Tim verifikasi profesional</li>
                                    <li>Layanan pelanggan 24/7</li>
                                    <li>Ribuan transaksi sukses setiap bulannya</li>
                                </ul>
                                <p>Kami juga terdaftar resmi dan diawasi oleh lembaga terkait untuk memastikan semua transaksi berjalan aman dan adil.</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <!-- Wave (langsung sebagai bagian dari footer) -->
    <div style="background-color: #f8f9fa;">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 1440 320" style="display: block; margin: 0; padding: 0;">
            <path fill="#273036" d="M0,160L48,186.7C96,213,192,267,288,277.3C384,288,480,256,576,224C672,192,768,160,864,165.3C960,171,1056,213,1152,224C1248,235,1344,213,1392,202.7L1440,192L1440,320L0,320Z"></path>
        </svg>
    </div>

    <!-- Footer -->
    <footer style="background-color: #273036; color: #fff; padding: 40px 0 0 0; margin-top: -5px;">

        <div class="container">
            <div class="row">
                <!-- Kolom 1: Info Perusahaan -->
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.4rem; margin-bottom: 20px;">Tentang LeLon</h5>
                    <p style="font-family: 'Poppins', sans-serif; font-size: 1rem; color: #dcdcdc;">
                        LeLon adalah platform lelang online yang menawarkan berbagai barang keren dengan harga yang bisa Anda tawar sendiri. Temukan barang impian Anda melalui sistem lelang yang transparan dan aman!
                    </p>
                </div>

                <!-- Kolom 2: Navigasi -->
                <div class="col-md-4 mb-4 mb-md-0">
                    <h5 style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.4rem; margin-bottom: 20px;">Navigasi</h5>
                    <ul class="list-unstyled" style="font-family: 'Poppins', sans-serif; font-size: 1rem; color: #dcdcdc;">
                        <li><a href="index.php" style="color: #dcdcdc; text-decoration: none;">Home</a></li>
                        <li><a href="lelang.php" style="color: #dcdcdc; text-decoration: none;">Lelang</a></li>
                        <li><a href="kategori.php" style="color: #dcdcdc; text-decoration: none;">Kategori</a></li>
                        <li><a href="aboutUs.php" style="color: #dcdcdc; text-decoration: none;">Tentang Kami</a></li>
                    </ul>
                </div>

                <!-- Kolom 3: Social Media -->
                <div class="col-md-4 mb-4 mb-md-0 text-center text-md-end">
                    <h5 style="font-family: 'Poppins', sans-serif; font-weight: 600; font-size: 1.4rem; margin-bottom: 20px;">Ikuti Kami</h5>
                    <a href="#" style="margin-right: 15px; color: #dcdcdc; font-size: 1.5rem; text-decoration: none;">
                        <i class="bi bi-facebook"></i>
                    </a>
                    <a href="#" style="margin-right: 15px; color: #dcdcdc; font-size: 1.5rem; text-decoration: none;">
                        <i class="bi bi-twitter"></i>
                    </a>
                    <a href="#" style="margin-right: 15px; color: #dcdcdc; font-size: 1.5rem; text-decoration: none;">
                        <i class="bi bi-instagram"></i>
                    </a>
                    <a href="#" style="margin-right: 15px; color: #dcdcdc; font-size: 1.5rem; text-decoration: none;">
                        <i class="bi bi-linkedin"></i>
                    </a>
                </div>
            </div>

            <!-- Copyright -->
            <div class="row mt-4">
                <div class="col text-center">
                    <p style="font-family: 'Poppins', sans-serif; font-size: 1rem; color: #dcdcdc; margin-bottom: 0;">
                        &copy; <?php echo date('Y'); ?> LeLon. All Rights Reserved.
                    </p>
                </div>
            </div>
        </div>
    </footer>


    <script>
        function logoutAlert() {
            if (confirm("Yakin mau logout?")) {
                window.location.href = "logout.php";
            }
        }
    </script>


</body>

</html>