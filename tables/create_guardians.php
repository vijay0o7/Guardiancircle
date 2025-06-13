<?php
include_once("../config/db_connect.php");
$sql = "CREATE TABLE IF NOT EXISTS guardians (
    guardian_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(100),
    phone VARCHAR(20),
    email VARCHAR(100),
    status VARCHAR(20),
    added_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);";
if ($conn->query($sql) === TRUE) {
    echo "Guardians table created successfully.<br>";
} else {
    echo "Error creating guardians table: " . $conn->error . "<br>";
}
?>