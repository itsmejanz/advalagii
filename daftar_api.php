<?php
include "ojan.php";

$nama = $_POST['nama'];
$username = $_POST['username'];
$email = trim($_POST['email']);
$password = $_POST['password'];

// hash password
$hash = password_hash($password, PASSWORD_DEFAULT);

// cek email
$cek = $conn->query("SELECT * FROM users WHERE email='$email'");
if($cek->num_rows > 0){
    die("Email sudah terdaftar!");
}

// simpan
$conn->query("INSERT INTO users (nama, username, email, password)
VALUES ('$nama','$username','$email','$hash')");

echo "<script>
alert('Register berhasil');
window.location='login.html';
</script>";
?>