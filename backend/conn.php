<?php
$host = "localhost";       // Nama host, biasanya "localhost"
$user = "root";            // Username database
$password = "";            // Password database (kosong jika default di XAMPP)
$database = "shopex";   // Nama database yang akan digunakan

// Membuat koneksi
$conn = new mysqli($host, $user, $password, $database);

// Cek koneksi
if ($conn->connect_error) {
    die("koneksi gagal: " . $conn->connect_error);
}
?>
