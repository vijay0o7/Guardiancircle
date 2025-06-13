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

// Get POST values
$user_id = $_POST['user_id'] ?? null;
$name = $_POST['name'] ?? null;
$phone = $_POST['phone'] ?? null;
$email = $_POST['email'] ?? null;
$status = $_POST['status'] ?? 'pending';
$added_at = date("Y-m-d H:i:s");

// Validate input
if (!$user_id || !$name || !$phone || !$email) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing required fields"
    ]);
    exit;
}

// Insert guardian
$sql = "INSERT INTO guardians (user_id, name, phone, email, status, added_at) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("isssss", $user_id, $name, $phone, $email, $status, $added_at);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Guardian added successfully"
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
