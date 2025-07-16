<?php
header('Content-Type: application/json');
include "dbconn.php";

$email = $_POST['email'];
$password = $_POST['password'];

// Prepare and execute the SQL statement
$stmt = $conn->prepare("SELECT * FROM auth WHERE email = ?");
$stmt->bind_param("s", $email);
$stmt->execute();
$result = $stmt->get_result();

// Check if login was successful
if ($result->num_rows === 1) {
    $user = $result->fetch_assoc();

    // In production, use password_verify($password, $user['password'])
    if ($user['password'] === $password) {
        echo json_encode([
            "status" => "success",
            "message" => "Login successful!",
            "user" => [
                "id" => $user['id'],
                "name" => $user['name'],
                "email" => $user['email'],
                "phone" => $user['phone_number']
            ]
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Invalid email or password."
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid email or password."
    ]);
}

$conn->close();
?>
