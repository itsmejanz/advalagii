<?php
session_start();
include "ojan.php";

$email = trim($_POST['email']);
$password = $_POST['password'];

$q = $conn->query("SELECT * FROM users WHERE email='$email'");

if($q->num_rows == 0){
    die("❌ Email tidak ditemukan");
}

$user = $q->fetch_assoc();

// DEBUG (hapus nanti kalau sudah jalan)
echo "Password input: $password <br>";
echo "Password DB: " . $user['password'] . "<br>";

if(password_verify($password, $user['password'])){
    
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['nama'] = $user['nama'];

    echo "<script>
    alert('Login berhasil');
    window.location='peta.php';
    </script>";

}else{
    die("❌ Password salah");
}
?>