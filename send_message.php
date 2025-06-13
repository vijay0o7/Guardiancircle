<?php
// DB Connection
$host = "localhost";
$user = "root";
$password = "";
$database = "gaurdian_circle";

$conn = new mysqli($host, $user, $password, $database);

// Check Connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get POST Data
$sender_id = $_POST['sender_id'] ?? null;
$receiver_id = $_POST['receiver_id'] ?? null;
$sent_message = $_POST['sent_message'] ?? null;
$received_message = $_POST['received_message'] ?? null;
$sent_at = date("Y-m-d H:i:s");

// Input Validation
if (!$sender_id || !$receiver_id || !$sent_message || !$received_message) {
    echo json_encode([
        "status" => "error",
        "message" => "All fields (sender_id, receiver_id, sent_message, received_message) are required."
    ]);
    exit;
}

// Prepare SQL Statement
$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, sent_at, sent_message, received_message) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("iisss", $sender_id, $receiver_id, $sent_at, $sent_message, $received_message);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Message inserted successfully."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to insert message: " . $stmt->error
    ]);
}

$stmt->close();
$conn->close();
?>
