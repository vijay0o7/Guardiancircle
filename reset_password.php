<?php
require 'dbconn.php';
require 'send_email.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(16));

    // Save token in password_resets table
    $stmt = $mysqli->prepare("INSERT INTO auth (email, token) VALUES (?, ?)");
    $stmt->bind_param("ss", $email, $token);
    $stmt->execute();
    $stmt->close();

    // Send reset email
    if (sendResetEmail($email, $token)) {
        echo "Reset email sent!";
    } else {
        echo "Failed to send email.";
    }
}
?>
