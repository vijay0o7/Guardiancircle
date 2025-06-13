<?php
include_once("../config/db_connect.php");
$sql = "CREATE TABLE IF NOT EXISTS safe_zones (
    zone_id INT AUTO_INCREMENT PRIMARY KEY,
    area_name VARCHAR(100),
    latitude DOUBLE,
    longitude DOUBLE,
    radius DOUBLE,
    is_safe BOOLEAN
);";
if ($conn->query($sql) === TRUE) {
    echo "Safe Zones table created successfully.<br>";
} else {
    echo "Error creating Safe Zones table: " . $conn->error . "<br>";
}
?>