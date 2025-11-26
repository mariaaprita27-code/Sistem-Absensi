<?php
$host = 'localhost';
$user = 'root'; // Default XAMPP
$pass = ''; // Kosongkan jika default
$db = 'absensi_siswa';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>