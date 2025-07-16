<?php
header('Content-Type: application/json');
include "dbconn.php";

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone_number'];
$password = $_POST['password']; // Use password_hash() in real apps

// Check if user with this email OR phone number already exists
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
    $stmt = $conn->prepare("INSERT INTO auth (name, email, phone_number, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $password); // Use hashed password
    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Signup successful!",
            "user" => [
                "id" => $stmt->insert_id,
                "name" => $name,
                "email" => $email,
                "phone" => $phone
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
