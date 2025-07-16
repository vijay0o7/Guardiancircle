<?php
header('Content-Type: application/json');
include "dbconn.php";

$user_id = $_POST['user_id'];
$location = $_POST['location'];
$duration = $_POST['duration_seconds']; // optional, update later if needed

$stmt = $conn->prepare("INSERT INTO sos_events (user_id, location, duration_seconds) VALUES (?, ?, ?)");
$stmt->bind_param("isi", $user_id, $location, $duration);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "SOS triggered",
        "sos_id" => $stmt->insert_id
    ]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}
$conn->close();
?>
