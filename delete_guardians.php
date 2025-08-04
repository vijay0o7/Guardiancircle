<?php
header('Content-Type: application/json');
include "dbconn.php";

$guardian_id = isset($_POST['guardian_id']) ? intval($_POST['guardian_id']) : 0;

if ($guardian_id <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid or missing guardian_id."
    ]);
    exit;
}

$stmt = $conn->prepare("DELETE FROM guardians WHERE id = ?");
$stmt->bind_param("i", $guardian_id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Guardian deleted successfully."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to delete guardian."
    ]);
}

$conn->close();
?>
