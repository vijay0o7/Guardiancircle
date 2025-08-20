<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "gaurdian_circle";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Database connection failed"]));
}

// Debug incoming data
// file_put_contents("debug.txt", print_r($_POST, true));

if (
    isset($_POST['user_id']) &&
    isset($_POST['name']) &&
    isset($_POST['phone']) &&
    isset($_POST['relation'])
) {
    $user_id = $_POST['user_id'];
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $relation = $_POST['relation'];

    $stmt = $conn->prepare("INSERT INTO emergency_contacts (user_id, name, phone, relation) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $user_id, $name, $phone, $relation);

    if ($stmt->execute()) {
        echo json_encode(["status" => "success", "message" => "Contact saved"]);
    } else {
        echo json_encode(["status" => "error", "message" => "Failed to save contact"]);
    }

    $stmt->close();
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Missing fields",
        "received" => $_POST // 👈 debug
    ]);
}

$conn->close();
?>
