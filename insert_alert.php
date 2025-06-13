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

// Get JSON POST data
$data = json_decode(file_get_contents("php://input"), true);

// Extract fields
$user_id = $data['user_id'] ?? null;
$location = $data['location'] ?? null;
$timestamp = $data['timestamp'] ?? date('Y-m-d H:i:s');
$alert_type = $data['alert_type'] ?? 'general';
$notified_guardians = $data['notified_guardians'] ?? null;

// Validate input
if (!$user_id || !$location || !$notified_guardians) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing required fields"
    ]);
    exit;
}

// Insert into sos_alerts table
$sql = "INSERT INTO sos_alerts (user_id, location, timestamp, alert_type, notified_guardians)
        VALUES (?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("issss", $user_id, $location, $timestamp, $alert_type, $notified_guardians);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Alert inserted successfully"
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
