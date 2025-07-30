<?php
require 'dbconn.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $token = $_POST['token'];
    $newPassword = $_POST['new_password'];
    $confirmPassword = $_POST['confirm_password'];

    if ($newPassword !== $confirmPassword) {
        die("Passwords do not match.");
    }

    // Hash password
    $hashedPassword = password_hash($newPassword, PASSWORD_BCRYPT);

    // Update password and clear token
    $stmt = $mysqli->prepare("UPDATE auth SET password = ?, token = NULL WHERE token = ?");
    $stmt->bind_param("ss", $hashedPassword, $token);

    if ($stmt->execute() && $stmt->affected_rows > 0) {
        echo "Password updated successfully!";
    } else {
        echo "Invalid or expired token.";
    }
    $stmt->close();
}
?>
