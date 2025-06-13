<?php
include_once("../config/db_connect.php");
$sql = "CREATE TABLE IF NOT EXISTS sos_alerts (
    alert_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    location VARCHAR(255),
    timestamp DATETIME DEFAULT CURRENT_TIMESTAMP,
    alert_type VARCHAR(50),
    notified_guardians TEXT,
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);";
if ($conn->query($sql) === TRUE) {
    echo "SOS Alerts table created successfully.<br>";
} else {
    echo "Error creating SOS Alerts table: " . $conn->error . "<br>";
}
?>