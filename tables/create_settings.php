<?php
include_once("../config/db_connect.php");
$sql = "CREATE TABLE IF NOT EXISTS settings (
    setting_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    setting_key VARCHAR(100),
    setting_value VARCHAR(255),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);";
if ($conn->query($sql) === TRUE) {
    echo "Settings table created successfully.<br>";
} else {
    echo "Error creating Settings table: " . $conn->error . "<br>";
}
?>