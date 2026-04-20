<?php
session_start();
include "ojan.php";

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode(["status"=>"login_required"]);
    exit;
}

$user_id = $_SESSION['user_id'];

if(!isset($_FILES['image'])){
    echo json_encode(["status"=>"no_file"]);
    exit;
}

$lat = $_POST['lat'] ?? 0;
$lng = $_POST['lng'] ?? 0;

// upload file
$folder = "uploads/";
if(!is_dir($folder)) mkdir($folder);

$filename = time() . "_" . $_FILES['image']['name'];
$path = $folder . $filename;

move_uploaded_file($_FILES['image']['tmp_name'], $path);

// simpan ke DB
$conn->query("
    INSERT INTO stories (user_id, image, lat, lng)
    VALUES ('$user_id', '$path', '$lat', '$lng')
");

echo json_encode(["status"=>"success"]);