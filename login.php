<?php
include "dbconn.php";

$email = $_POST['email'];
$password = $_POST['password'];

$stmt = $conn->prepare("SELECT * FROM auth WHERE email = ? AND password = ?");
$stmt->bind_param("ss", $email, $password); // Use password_verify() in real apps
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows == 1) {
    echo "Login successful!";
} else {
    echo "Invalid email or password.";
}
$conn->close();
?>
