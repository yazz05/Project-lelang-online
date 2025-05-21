<?php
// Database configuration
$host = "localhost";
$user = "root";
$pass = "";
$db   = "lelang_online";

// Create connection with error handling
$koneksi = mysqli_connect($host, $user, $pass, $db);

// Check connection
if (!$koneksi) {
    // Log error securely (in production, don't expose details)
    error_log("Database connection failed: " . mysqli_connect_error());

    // Display user-friendly message
    die("Maaf, terjadi gangguan sistem. Silakan coba lagi nanti.");
}

// Set charset to prevent encoding issues
mysqli_set_charset($koneksi, "utf8mb4");
