<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Enable error reporting (development only)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection
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

    // Validate input
    if (!isset($data["email"]) || empty(trim($data["email"])) || 
        !isset($data["otp"]) || empty(trim($data["otp"]))) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Email and OTP are required"]);
        exit;
    }

    $email = filter_var(trim($data["email"]), FILTER_VALIDATE_EMAIL);
    $otp = trim($data["otp"]);

    if (!$email) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Valid email is required"]);
        exit;
    }

    // Check if OTP matches
    $stmt = $conn->prepare("SELECT s_no, token FROM auth WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $stmt->store_result();

    if ($stmt->num_rows === 0) {
        http_response_code(404);
        echo json_encode(["status" => "error", "message" => "No account found with this email."]);
        exit;
    }

    $stmt->bind_result($s_no, $storedOtp);
    $stmt->fetch();
    $stmt->close();

    // Check if OTP exists
    if (empty($storedOtp)) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "OTP already used or not generated"]);
        exit;
    }

    // Verify OTP
    if ($storedOtp !== $otp) {
        http_response_code(400);
        echo json_encode(["status" => "error", "message" => "Invalid OTP"]);
        exit;
    }

    // Clear OTP after successful verification (one-time use)
    $stmtClear = $conn->prepare("UPDATE auth SET token = NULL WHERE email = ?");
    $stmtClear->bind_param("s", $email);
    $stmtClear->execute();
    $stmtClear->close();

    // Return success
    echo json_encode([
        "status" => "success",
        "message" => "OTP verified successfully"
    ]);

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode([
        "status" => "error",
        "message" => "Server error: " . $e->getMessage()
    ]);
}
?>
