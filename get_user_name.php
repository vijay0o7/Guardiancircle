<?php
header('Content-Type: application/json');

$mysqli = new mysqli("localhost", "root", "", "gaurdian_circle");

if ($mysqli->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

if (!isset($_GET['user_id'])) {
    echo json_encode(["error" => "Missing user_id"]);
    exit();
}

$user_id = intval($_GET['user_id']);

$sql = "SELECT name FROM auth WHERE s_no = ?";
$stmt = $mysqli->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($row = $result->fetch_assoc()) {
    echo json_encode(["name" => $row['name']]);
} else {
    echo json_encode(["error" => "User not found"]);
}

$stmt->close();
$mysqli->close();
?>
