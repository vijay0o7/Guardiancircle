<?php
header('Content-Type: application/json');
include "dbconn.php";

$user_id = $_GET['user_id']; // passed from frontend

$stmt = $conn->prepare("SELECT id, name, photo_url, is_active FROM guardians WHERE user_id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$guardians = [];
while ($row = $result->fetch_assoc()) {
    $guardians[] = $row;
}

echo json_encode([
    "status" => "success",
    "guardians" => $guardians
]);

$conn->close();
?>
