<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting (development only)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Load Composer autoloader
require __DIR__ . '/vendor/autoload.php';

// Database connection (MySQLi)
include 'dbconn.php'; // $conn is defined here

header('Content-Type: application/json');

// Allow only POST requests
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    http_response_code(405);
    echo json_encode(["status" => "error", "message" => "Invalid request method"]);
    exit;
}

try {
    // Get JSON input
    $data = json_decode(file_get_contents("php://input"), true);

    if (!isset($data["email"]) || empty(trim($data["email"]))) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Email is required"]);
        exit;
    }

    // Validate email
    $email = filter_var(trim($data["email"]), FILTER_VALIDATE_EMAIL);
    if (!$email) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Valid email is required"]);
        exit;
    }

    // Check if email exists in auth table
    $stmtCheck = $conn->prepare("SELECT s_no, name FROM auth WHERE email = ?");
    $stmtCheck->bind_param("s", $email);
    $stmtCheck->execute();
    $stmtCheck->store_result();

    if ($stmtCheck->num_rows === 0) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "No account found with this email."]);
        exit;
    }

    $stmtCheck->bind_result($s_no, $name);
    $stmtCheck->fetch();
    $stmtCheck->close();

    // Generate 6-digit OTP
    $otp = random_int(100000, 999999);

    // Store OTP in token column
    $stmtUpdate = $conn->prepare("UPDATE auth SET token = ? WHERE email = ?");
    $stmtUpdate->bind_param("ss", $otp, $email);
    $stmtUpdate->execute();
    $stmtUpdate->close();

    // Try sending OTP via PHPMailer
    $mailSent = false;
    try {
        $mail = new PHPMailer(true);
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'kdhanalakshmi2005@gmail.com'; // Your Gmail
        $mail->Password = 'stth lhvp egwr ycfv';          // App Password
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom('kdhanalakshmi2005@gmail.com', 'Gaurdian Circlr ');
        $mail->addAddress($email);
        $mail->isHTML(true);
        $mail->Subject = 'Your OTP Code';
        $mail->Body = "<p>Hello <strong>$name</strong>,</p>
                       <p>Your OTP for password reset is: <strong>$otp</strong></p>
                       <p>If you did not request this, please ignore this email.</p>";

        $mail->send();
        $mailSent = true;
    } catch (Exception $e) {
        // If SMTP fails, we will just return OTP in JSON
        $mailSent = false;
    }

    // Return response
    if ($mailSent) {
        echo json_encode([
            "status" => "success",
            "message" => "OTP sent to your email."
        ]);
    } else {
        echo json_encode([
            "status" => "success",
            "message" => "OTP generated (SMTP failed, returned for local testing).",
            "otp" => $otp
        ]);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $e->getMessage()
    ]);
}
