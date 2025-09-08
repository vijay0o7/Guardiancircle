<?php
header('Content-Type: application/json');
include "dbconn.php";

$response = array();

// Validate input
if (!isset($_GET['user_id']) || !is_numeric($_GET['user_id'])) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid or missing user_id"
    ]);
    exit();
}

$user_id = intval($_GET['user_id']);

// Prepare and execute
$stmt = $conn->prepare("
    SELECT latitude, longitude, speed, status, updated_at 
    FROM user_locations 
    WHERE user_id = ? 
    ORDER BY updated_at DESC 
    LIMIT 1
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result && $result->num_rows > 0) {
    $data = $result->fetch_assoc();
    $response = [
        "status" => "success",
        "location" => $data
    ];
} else {
    $response = [
        "status" => "error",
        "message" => "No location found for this user"
    ];
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
