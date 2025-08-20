<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gaurdiancircle";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(["status" => "error", "message" => "Connection failed"]);
    exit;
}

$user_id = $_GET['user_id'] ?? '';

if (empty($user_id)) {
    echo json_encode(["status" => "error", "message" => "Missing user_id"]);
    exit;
}

$sql = "SELECT contact_id, name, phone, relation, created_at 
        FROM emergency_contacts WHERE user_id = '$user_id'";

$result = $conn->query($sql);

$contacts = [];
while ($row = $result->fetch_assoc()) {
    $contacts[] = $row;
}

echo json_encode(["status" => "success", "contacts" => $contacts]);

$conn->close();
?>
