<?php
session_start();

// Hapus semua data session
$_SESSION = array();

// Hancurkan session
session_destroy();

// Redirect ke halaman login (pastikan path benar)
header("Location: ../login/login.php"); // Ganti dengan path sesuai struktur folder Anda
exit();