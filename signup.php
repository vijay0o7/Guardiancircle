<?php
header('Content-Type: application/json');
include "dbconn.php";

// Get POST data; add 'role'
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone_number'];   // Make sure column 'phone_number' exists in DB or rename as needed
$password = $_POST['password'];     // Ideally, use password_hash() here
$role = $_POST['role'];             // Add role parameter from client

// Validate role value (optional but recommended)
$allowed_roles = ['USER', 'GUARDIAN'];
if (!in_array($role, $allowed_roles)) {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid role selected."
    ]);
    exit;
}

// Use prepared statement to avoid duplicates based on email or phone_number
$check = $conn->prepare("SELECT * FROM auth WHERE email = ? OR phone_number = ?");
$check->bind_param("ss", $email, $phone);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo json_encode([
        "status" => "error",
        "message" => "User already exists with this email or phone number."
    ]);
} else {
    // Hash the password securely
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

    // Insert with role included
    $stmt = $conn->prepare("INSERT INTO auth (name, email, phone_number, password, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $phone, $hashedPassword, $role);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Signup successful!",
            "user" => [
                "s_no" => $stmt->insert_id,
                "name" => $name,
                "email" => $email,
                "phone" => $phone,
                "role" => $role
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Database error: " . $stmt->error
        ]);
    }
}

$conn->close();
?>
