<?php
$conn = new mysqli("localhost", "root", "", "ojan");

if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}
?>