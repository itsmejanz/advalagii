<?php
include "ojan.php";

header('Content-Type: application/json');

// hanya story 24 jam terakhir
$result = $conn->query("
    SELECT s.*, u.nama, u.avatar
    FROM stories s
    JOIN users u ON u.id = s.user_id
    WHERE s.created_at >= NOW() - INTERVAL 1 DAY
    ORDER BY s.id DESC
");

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);