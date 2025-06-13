<?php
require 'dbconn.php'; // make sure this defines $conn (MySQLi)

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = $_POST['email'];
    $token = bin2hex(random_bytes(50));

    // Prepare the SQL statement
    $stmt = $conn->prepare("INSERT INTO auth (email, token) VALUES (?, ?)");
    
    if ($stmt) {
        $stmt->bind_param("ss", $email, $token);
        $stmt->execute();
        $stmt->close();

        // Send email logic here
        $reset_link = "http://localhost/guardian_circle/public/reset_password.php?token=$token";
        // Uncomment when ready to send emails
        // mail($email, "Password Reset", "Click here to reset your password: $reset_link");

        echo "Password reset link has been sent.";
    } else {
        echo "Database error: " . $conn->error;
    }
}
?>
