<?php
include_once("../config/db_connect.php");
$sql = "CREATE TABLE IF NOT EXISTS recordings (
    recording_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    file_path VARCHAR(255),
    duration INT,
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);";
if ($conn->query($sql) === TRUE) {
    echo "Recordings table created successfully.<br>";
} else {
    echo "Error creating Recordings table: " . $conn->error . "<br>";
}
?>