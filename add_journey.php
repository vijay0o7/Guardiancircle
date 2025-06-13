<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gaurdian_circle";

// Connect to MySQL
$conn = new mysqli($servername, $username, $password, $dbname);

// Set header
header('Content-Type: application/json');

// Check connection
if ($conn->connect_error) {
    echo json_encode([
        "status" => "error",
        "message" => "Connection failed: " . $conn->connect_error
    ]);
    exit;
}

// Get POST data
$user_id = $_POST['user_id'] ?? null;
$start_time = $_POST['start_time'] ?? null;
$end_time = $_POST['end_time'] ?? null;
$start_location = $_POST['start_location'] ?? null;
$end_location = $_POST['end_location'] ?? null;
$status = $_POST['status'] ?? 'active';

// Validate input
if (!$user_id || !$start_time || !$start_location || !$end_location) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing required fields"
    ]);
    exit;
}

// Insert into journeys table
$sql = "INSERT INTO journeys (user_id, start_time, end_time, start_location, end_location, status)
        VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssss", $user_id, $start_time, $end_time, $start_location, $end_location, $status);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Journey added successfully"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Insert failed: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
