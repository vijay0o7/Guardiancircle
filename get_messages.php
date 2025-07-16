<?php
header('Content-Type: application/json');
include "dbconn.php";

$sender_id = $_GET['sender_id'];
$receiver_id = $_GET['receiver_id'];

$stmt = $conn->prepare("
    SELECT * FROM messages 
    WHERE 
        (sender_id = ? AND receiver_id = ?) 
        OR 
        (sender_id = ? AND receiver_id = ?)
    ORDER BY sent_at ASC
");
$stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);
$stmt->execute();
$result = $stmt->get_result();

$messages = [];
while ($row = $result->fetch_assoc()) {
    $messages[] = $row;
}

echo json_encode([
    "status" => "success",
    "messages" => $messages
]);

$conn->close();
?>
