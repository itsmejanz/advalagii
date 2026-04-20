<?php
include "ojan.php";

$result = $conn->query("
    SELECT u.nama, u.avatar, l.lat, l.lng
    FROM users u
    LEFT JOIN users_location l ON u.id = l.user_id
");

$data = [];

while($row = $result->fetch_assoc()){
    $data[] = $row;
}

echo json_encode($data);