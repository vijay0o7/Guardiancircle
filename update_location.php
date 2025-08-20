<?php
$servername = "localhost";
$username = "root"; 
$password = "";
$dbname = "gaurdian_circle";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$user_id = $_POST['user_id'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$shared_with = $_POST['shared_with']; // guardian email

$sql = "INSERT INTO locations (user_id, latitude, longitude, shared_with) 
        VALUES ('$user_id', '$latitude', '$longitude', '$shared_with')";
if ($conn->query($sql) === TRUE) {
    echo "Location updated";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
