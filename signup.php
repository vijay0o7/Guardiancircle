<?php

include "dbconn.php";

$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone_number'];
$password = $_POST['password']; // Use password_hash() in real apps

// Check if user already exists
$check = $conn->prepare("SELECT * FROM auth WHERE email = ?");
$check->bind_param("s", $email);
$check->execute();
$result = $check->get_result();

if ($result->num_rows > 0) {
    echo "User already exists!";
} else {
    $stmt = $conn->prepare("INSERT INTO auth (name, email, phone_number, password) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("ssss", $name, $email, $phone, $password); // Use hashed password
    if ($stmt->execute()) {
        echo "Signup successful!";
    } else {
        echo "Error: " . $stmt->error;
    }
}
$conn->close();
?>
