<?php
header('Content-Type: application/json');
include "dbconn.php";

$guardian_id = $_POST['guardian_id'];
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$gender = $_POST['gender'];

// Validate gender
$allowed_genders = ['Male', 'Female', 'Other'];
if (!in_array($gender, $allowed_genders)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid gender value."
    ]);
    exit;
}

$stmt = $conn->prepare("UPDATE guardians SET name = ?, email = ?, gender = ? WHERE id = ?");
$stmt->bind_param("sssi", $full_name, $email, $gender, $guardian_id);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Guardian updated successfully."
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to update guardian."
    ]);
}

$conn->close();
?>
