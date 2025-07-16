<?php
header('Content-Type: application/json');
include "dbconn.php";

$guardian_id = $_POST['guardian_id'];
$is_active = $_POST['is_active']; // 1 or 0

$stmt = $conn->prepare("UPDATE guardians SET is_active = ? WHERE id = ?");
$stmt->bind_param("ii", $is_active, $guardian_id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Status updated"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to update status"
    ]);
}

$conn->close();
?>
