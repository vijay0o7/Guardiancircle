<?php
$servername = "localhost";
$username = "root";
$password = "";
$db = "gaurdian_circle";

// Establish connection
$conn = new mysqli($servername, $username, $password, $db);

$response = array();

// Check if the connection was successful
if ($conn->connect_error) {
    // Connection failed
    $response['status'] = "error";
    $response['message'] = "Connection failed: " . $conn->connect_error;
} else {
    // Connection successful
    $response['status'] = "success";
    $response['message'] = "Connected successfully";
}

// Encode the response to JSON format
$json_response = json_encode($response);

// Set the content type to application/json and output the response
header('Content-Type: application/json');
echo $json_response;
?>
