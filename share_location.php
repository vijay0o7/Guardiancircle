<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "gaurdian_circle";

// Establish connection
$conn = new mysqli($servername, $username, $password, $db);

// Response array
$response = array();

if ($conn->connect_error) {
    $response['status'] = "error";
    $response['message'] = "Connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit();
}

// Get POST values
$user_id = isset($_POST['user_id']) ? intval($_POST['user_id']) : 0;
$shared_with = isset($_POST['shared_with']) ? $_POST['shared_with'] : '';
$latitude = isset($_POST['latitude']) ? floatval($_POST['latitude']) : 0;
$longitude = isset($_POST['longitude']) ? floatval($_POST['longitude']) : 0;

if ($user_id == 0 || empty($shared_with) || empty($latitude) || empty($longitude)) {
    $response['status'] = "error";
    $response['message'] = "Missing required parameters";
    echo json_encode($response);
    exit();
}

// Insert or update
$stmt = $conn->prepare("
    INSERT INTO shared_locations (user_id, shared_with, latitude, longitude, timestamp) 
    VALUES (?, ?, ?, ?, NOW())
    ON DUPLICATE KEY UPDATE latitude=VALUES(latitude), longitude=VALUES(longitude), timestamp=NOW()
");

$stmt->bind_param("isdd", $user_id, $shared_with, $latitude, $longitude);

if ($stmt->execute()) {
    $response['status'] = "success";
    $response['message'] = "Location saved/updated successfully";
} else {
    $response['status'] = "error";
    $response['message'] = "DB Error: " . $stmt->error;
}

$stmt->close();
$conn->close();

echo json_encode($response);
?>
