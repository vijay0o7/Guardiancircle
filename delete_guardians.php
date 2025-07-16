<?php
header('Content-Type: application/json');
include "dbconn.php";

$guardian_id = $_POST['guardian_id'];

$stmt = $conn->prepare("DELETE FROM guardians WHERE id = ?");
$stmt->bind_param("i", $guardian_id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Guardian deleted"
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to delete guardian"
    ]);
}

$conn->close();
?>
