<?php
include_once("../config/db_connect.php");
$sql = "CREATE TABLE IF NOT EXISTS emergency_contacts (
    contact_id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT,
    name VARCHAR(100),
    phone VARCHAR(20),
    relation VARCHAR(50),
    FOREIGN KEY (user_id) REFERENCES users(user_id) ON DELETE CASCADE
);";
if ($conn->query($sql) === TRUE) {
    echo "Emergency Contacts table created successfully.<br>";
} else {
    echo "Error creating Emergency Contacts table: " . $conn->error . "<br>";
}
?>