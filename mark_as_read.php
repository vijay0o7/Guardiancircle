<?php
header('Content-Type: application/json');
include "dbconn.php";

$sender_id = $_POST['sender_id'];
$receiver_id = $_POST['receiver_id'];

$stmt = $conn->prepare("
    UPDATE messages 
    SET is_read = 1 
    WHERE sender_id = ? AND receiver_id = ?
");
$stmt->bind_param("ii", $sender_id, $receiver_id);

if ($stmt->execute()) {
    echo json_encode(["status" => "success"]);
} else {
    echo json_encode(["status" => "error"]);
}

$conn->close();
?>
