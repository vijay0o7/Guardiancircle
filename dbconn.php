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
?>
