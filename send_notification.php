<?php
header("Content-Type: application/json");

// Include DB connection
$conn = new mysqli("localhost", "root", "", "gaurdian_circle");

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed: " . $conn->connect_error]);
    exit();
}

// Get POST values
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
$message = isset($_POST['message']) ? $_POST['message'] : null;
$type = isset($_POST['type']) ? $_POST['type'] : null;
$is_read = isset($_POST['is_read']) ? $_POST['is_read'] : 0;

// Validate required fields
if (!$user_id || !$message || !$type) {
    echo json_encode([
        "status" => "error",
        "message" => "All fields (user_id, message, type) are required."
    ]);
    exit();
}

// Prepare and execute SQL
$stmt = $conn->prepare("INSERT INTO notifications (user_id, message, type, timestamp, is_read) VALUES (?, ?, ?, NOW(), ?)");
$stmt->bind_param("issi", $user_id, $message, $type, $is_read);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Notification inserted successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to insert notification."]);
}

$stmt->close();
$conn->close();
?>
