<?php
session_start();
include "ojan.php";

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode(["status"=>"error"]);
    exit;
}

$user_id = $_SESSION['user_id'];
$message = trim($_POST['message'] ?? '');

if($message == ""){
    echo json_encode(["status"=>"empty"]);
    exit;
}

$conn->query("
    INSERT INTO message (sender_id, message)
    VALUES ('$user_id', '$message')
");

echo json_encode(["status"=>"ok"]);