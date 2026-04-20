<?php
session_start();
include "ojan.php";

header('Content-Type: application/json');

if(!isset($_SESSION['user_id'])){
    echo json_encode([]);
    exit;
}

$last_id = isset($_GET['last_id']) ? intval($_GET['last_id']) : 0;

$result = $conn->query("
    SELECT message.id, message.sender_id, message.message, message.created_at, users.nama 
    FROM message
    JOIN users ON users.id = message.sender_id
    WHERE message.id > $last_id
    ORDER BY message.id ASC
");

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);