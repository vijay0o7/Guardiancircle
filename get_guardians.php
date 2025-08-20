<?php
header('Content-Type: application/json');
include "dbconn.php";

$user_id = isset($_POST['user_id']) ? trim($_POST['user_id']) : null;

if (empty($user_id)) {
    echo json_encode([
        "status" => "error",
        "message" => "User ID is required."
    ]);
    exit;
}

$stmt = $conn->prepare("SELECT id, user_id, name, email, phone, gender, status FROM guardians WHERE user_id = ?");
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
