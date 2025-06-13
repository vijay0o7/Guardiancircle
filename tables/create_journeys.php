<?php
include_once("../config/db_connect.php");
$sql = "CREATE TABLE IF NOT EXISTS journeys (
    journey_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    start_time DATETIME,
    end_time DATETIME,
    start_location VARCHAR(255),
    end_location VARCHAR(255),
    status VARCHAR(20),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);";
if ($conn->query($sql) === TRUE) {
    echo "Journeys table created successfully.<br>";
} else {
    echo "Error creating journeys table: " . $conn->error . "<br>";
}
?>