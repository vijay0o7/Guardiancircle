<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "gaurdian_circle";

// Establish connection
$conn = new mysqli($servername, $username, $password, $db);

// Set response format to JSON
header('Content-Type: application/json');
$response = array();

// Check connection
if ($conn->connect_error) {
    $response['status'] = "error";
    $response['message'] = "Connection failed: " . $conn->connect_error;
    echo json_encode($response);
    exit;
}

// Fetch all emergency contacts
$sql = "SELECT * FROM emergency_contacts";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $contacts = array();
    while ($row = $result->fetch_assoc()) {
        $contacts[] = $row;
    }
    $response['status'] = "success";
    $response['data'] = $contacts;
} else {
    $response['status'] = "success";
    $response['message'] = "No contacts found";
    $response['data'] = [];
}

echo json_encode($response);
$conn->close();
?>
