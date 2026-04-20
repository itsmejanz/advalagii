<?php
session_start();
include "ojan.php";

$user_id = $_SESSION['user_id'];
$lat = $_POST['lat'];
$lng = $_POST['lng'];

// cek sudah ada atau belum
$cek = $conn->query("SELECT * FROM users_location WHERE user_id='$user_id'");

if($cek->num_rows > 0){
    // update
    $conn->query("UPDATE users_location SET lat='$lat', lng='$lng' WHERE user_id='$user_id'");
}else{
    // insert
    $conn->query("INSERT INTO users_location (user_id, lat, lng) VALUES ('$user_id','$lat','$lng')");
}

echo json_encode(["status"=>"ok"]);