<?php
require 'dbconn.php'; // MySQL connection
require 'send_email.php'; // Email sending function

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];

    if (empty($email)) {
        echo "Email is required.";
        exit;
    }

    // Check if email exists in auth table
    $stmt = $mysqli->prepare("SELECT s_no FROM auth WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows == 0) {
        echo "No account found with that email.";
        exit;
    }
    $stmt->close();

    // Generate token
    $token = bin2hex(random_bytes(16));

    // Save token in auth table
    $update_stmt = $mysqli->prepare("UPDATE auth SET token = ? WHERE email = ?");
    $update_stmt->bind_param("ss", $token, $email);
    if (!$update_stmt->execute()) {
        echo "Failed to store reset token.";
        exit;
    }
    $update_stmt->close();

    // Send reset email with link
    $resetLink = "http://yourdomain.com/reset_password_form.php?token=" . $token;
    if (sendResetEmail($email, $resetLink)) {
        echo "Reset email sent!";
    } else {
        echo "Failed to send email.";
    }
}
?>
