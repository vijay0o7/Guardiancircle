<?php
header('Content-Type: application/json');
include "dbconn.php";

$user_id = $_GET['user_id'];

$stmt = $conn->prepare("SELECT latitude, longitude, speed, status, updated_at FROM user_locations WHERE user_id = ? ORDER BY updated_at DESC LIMIT 1");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$data = $result->fetch_assoc();

echo json_encode(["status" => "success", "location" => $data]);
?>
