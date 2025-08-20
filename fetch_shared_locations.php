<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "gaurdian_circle";

// Connect to database
$conn = new mysqli($servername, $username, $password, $db);

$response = array();

if ($conn->connect_error) {
    $response['status'] = "error";
    $response['message'] = "Connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit();
}

$shared_with = isset($_GET['shared_with']) ? $_GET['shared_with'] : '';

if (empty($shared_with)) {
    $response['status'] = "error";
    $response['message'] = "Missing guardian identifier";
    echo json_encode($response);
    exit();
}

// Fetch latest location per user shared with this guardian
// For simplicity, fetch latest location overall for this guardian
$sql = "SELECT user_id, latitude, longitude, timestamp 
        FROM shared_locations 
        WHERE shared_with = ? 
        ORDER BY timestamp DESC 
        LIMIT 1";

$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $shared_with);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $response['status'] = "success";
    $response['location'] = $row;
} else {
    $response['status'] = "error";
    $response['message'] = "No shared locations found";
}

$stmt->close();
$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
