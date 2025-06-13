<?php

require __DIR__ . '/PHPMailer/src/PHPMailer.php';
require __DIR__ . '/PHPMailer/src/SMTP.php';
require __DIR__ . '/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'vendor/autoload.php'; // Make sure you've installed PHPMailer via Composer

function sendResetEmail($recipientEmail, $token) {
    $mail = new PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'vijaysankarcr@gmail.com';       // ✅ Your Gmail
        $mail->Password   = 'Vijay@0307';           // ✅ App password (not your Gmail password)
        $mail->SMTPSecure = 'tls';
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('vijaysankarcr@gmail.com', 'Guardian Circle');
        $mail->addAddress($recipientEmail);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Password Reset - Guardian Circle';
        $mail->Body    = "
            <h3>Reset Your Password</h3>
            <p>Click the link below to reset your password:</p>
            <a href='http://localhost/gaurdian_circle/reset_password.php?token=$token'>Reset Password</a>
        ";

        $mail->send();
        return true;

    } catch (Exception $e) {
        error_log("Email could not be sent. Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
