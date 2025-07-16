<?php
header('Content-Type: application/json');
include "dbconn.php";

// Get data from POST
$user_id = $_POST['user_id']; // who is adding the guardian
$full_name = $_POST['full_name'];
$email = $_POST['email'];
$gender = $_POST['gender']; // Expect: Male, Female, Other

// Optional: Validate gender value
$allowed_genders = ['Male', 'Female', 'Other'];
if (!in_array($gender, $allowed_genders)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid gender value."
    ]);
    exit;
}

// Optional: Check for duplicate guardian (email)
$check = $conn->prepare("SELECT id FROM guardians WHERE user_id = ? AND email = ?");
$check->bind_param("is", $user_id, $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Guardian with this email already exists."
    ]);
    exit;
}

// Insert new guardian
$stmt = $conn->prepare("INSERT INTO guardians (user_id, full_name, email, gender) VALUES (?, ?, ?, ?)");
$stmt->bind_param("isss", $user_id, $full_name, $email, $gender);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Guardian added successfully!",
        "guardian_id" => $stmt->insert_id
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to add guardian."
    ]);
}

$conn->close();
?>
