<?php
header('Content-Type: application/json');
include "dbconn.php";

$input = $_POST['email_or_phone'];  // Single field for email or phone
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM auth WHERE email = ? OR phone_number = ?");
$stmt->bind_param("ss", $input, $input);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    if (password_verify($password, $user['password'])) {
        echo json_encode([
            "status" => "success",
            "message" => "Login successful",
            "name" => $user['name'],
            "email" => $user['email'],
            "phone_number" => $user['phone_number'],
            "role" => $user['role']
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid email/phone or password."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email/phone or password."
    ]);
}

$conn->close();
?>
