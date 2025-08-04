<?php
header('Content-Type: application/json');
include "dbconn.php";

// Log incoming POST data for debugging
error_log("POST data: " . print_r($_POST, true));

// Sanitize and validate inputs
$user_id   = isset($_POST['user_id']) ? trim($_POST['user_id']) : null;
$full_name = isset($_POST['full_name']) ? trim($_POST['full_name']) : null;
$email     = isset($_POST['email']) ? trim($_POST['email']) : null;
$phone     = isset($_POST['phone']) ? trim($_POST['phone']) : null;
$gender    = isset($_POST['gender']) ? trim($_POST['gender']) : null;

// Check for missing fields
$missing_fields = [];
if (empty($user_id))   $missing_fields[] = "user_id";
if (empty($full_name)) $missing_fields[] = "full_name";
if (empty($email))     $missing_fields[] = "email";
if (empty($phone))     $missing_fields[] = "phone";
if (empty($gender))    $missing_fields[] = "gender";

if (!empty($missing_fields)) {
    echo json_encode([
        "status" => "error",
        "message" => "Missing fields: " . implode(', ', $missing_fields)
    ]);
    exit;
}

// Validate gender value
$allowed_genders = ['Male', 'Female', 'Other'];
if (!in_array($gender, $allowed_genders)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid gender value. Allowed: Male, Female, Other"
    ]);
    exit;
}

// Check for duplicate guardian
$check = $conn->prepare("SELECT id FROM guardians WHERE user_id = ? AND email = ?");
$check->bind_param("is", $user_id, $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "Guardian with this email already exists for this user."
    ]);
    exit;
}

// Insert guardian
$stmt = $conn->prepare("INSERT INTO guardians (user_id, name, email, phone, gender) VALUES (?, ?, ?, ?, ?)");
$stmt->bind_param("issss", $user_id, $full_name, $email, $phone, $gender);

if ($stmt->execute()) {
    echo json_encode([
        "status" => "success",
        "message" => "Guardian added successfully!",
        "guardian_id" => $stmt->insert_id
    ]);
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Failed to add guardian. Please try again."
    ]);
}

$conn->close();
?>
