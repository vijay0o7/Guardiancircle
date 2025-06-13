<?php
include_once("../config/db_connect.php");
$sql = "CREATE TABLE IF NOT EXISTS locations (
    location_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    latitude DOUBLE,
    longitude DOUBLE,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);";
if ($conn->query($sql) === TRUE) {
    echo "Locations table created successfully.<br>";
} else {
    echo "Error creating Locations table: " . $conn->error . "<br>";
}
?>