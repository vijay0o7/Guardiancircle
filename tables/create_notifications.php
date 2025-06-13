<?php
include_once("../config/db_connect.php");
$sql = "CREATE TABLE IF NOT EXISTS notifications (
    notification_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    message TEXT,
    type VARCHAR(50),
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    is_read BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);";
if ($conn->query($sql) === TRUE) {
    echo "Notifications table created successfully.<br>";
} else {
    echo "Error creating Notifications table: " . $conn->error . "<br>";
}
?>