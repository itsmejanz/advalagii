<?php
session_start();
include "ojan.php";

$id = $_SESSION['user_id'];

$nama = $_POST['nama'];
$username = $_POST['username'];
$email = $_POST['email'];
$bio = $_POST['bio'];

if(isset($_FILES['avatar']) && $_FILES['avatar']['name'] != ""){

    $ext = pathinfo($_FILES['avatar']['name'], PATHINFO_EXTENSION);
    $path = "profile/" . time() . "." . $ext;

    move_uploaded_file($_FILES['avatar']['tmp_name'], $path);

    $sql = "UPDATE users SET 
        nama='$nama',
        username='$username',
        email='$email',
        bio='$bio',
        avatar='$path'
        WHERE id='$id'";

}else{
    $sql = "UPDATE users SET 
        nama='$nama',
        username='$username',
        email='$email',
        bio='$bio'
        WHERE id='$id'";
}

$conn->query($sql);

header("Location: profile.php");