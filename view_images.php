<?php
$conn = new mysqli("localhost", "root", "", "gaurdian_circle");
$result = $conn->query("SELECT * FROM images");

while ($row = $result->fetch_assoc()) {
    echo "<img src='uploads/" . $row['filename'] . "' width='200' style='margin:10px'>";
}
?>
