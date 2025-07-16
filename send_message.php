<?php
header('Content-Type: application/json');
include "dbconn.php";

$sender_id = $_POST['sender_id'];
$receiver_id = $_POST['receiver_id'];
$message = $_POST['message'];

$stmt = $conn->prepare("INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)");
$stmt->bind_param("iis", $sender_id, $receiver_id, $message);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message_id" => $stmt->insert_id,
        "sent_at" => date("Y-m-d H:i:s")
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to send message"
    ]);
}

$conn->close();
?>
