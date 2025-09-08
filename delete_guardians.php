<?php
header('Content-Type: application/json');
include "dbconn.php";

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        "status" => "error",
        "message" => "Only POST requests are allowed."
    ]);
    exit;
}

$guardian_id = isset($_POST['guardian_id']) ? intval($_POST['guardian_id']) : 0;

if ($guardian_id <= 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid or missing guardian_id."
    ]);
    exit;
}

// Check if guardian exists
$check = $conn->prepare("SELECT id FROM guardians WHERE id = ?");
$check->bind_param("i", $guardian_id);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Guardian not found."
    ]);
    exit;
}

// Delete guardian
$stmt = $conn->prepare("DELETE FROM guardians WHERE id = ?");
$stmt->bind_param("i", $guardian_id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Guardian deleted successfully.",
        "guardian_id" => $guardian_id
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to delete guardian."
    ]);
}

$conn->close();
?>
