<?php
header("Content-Type: application/json");

// DB connection
$conn = new mysqli("localhost", "root", "", "gaurdian_circle");
if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "DB Connection failed"]);
    exit();
}

// Get POST values
$user_id = isset($_POST['user_id']) ? $_POST['user_id'] : null;
$setting_key = isset($_POST['setting_key']) ? $_POST['setting_key'] : null;
$setting_value = isset($_POST['setting_value']) ? $_POST['setting_value'] : null;

// Validate input
if (!$user_id || !$setting_key || !$setting_value) {
    echo json_encode(["status" => "error", "message" => "All fields (user_id, setting_key, setting_value) are required."]);
    exit();
}

// Optional: update if exists, otherwise insert
$sql = "INSERT INTO settings (user_id, setting_key, setting_value)
        VALUES (?, ?, ?)
        ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)";

$stmt = $conn->prepare($sql);
$stmt->bind_param("iss", $user_id, $setting_key, $setting_value);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Setting saved successfully."]);
} else {
    echo json_encode(["status" => "error", "message" => "Failed to save setting."]);
}

$stmt->close();
$conn->close();
?>
