<?php
include "dbconn.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $user_id = $_POST['user_id'];
    $guardian_email = $_POST['guardian_email'];
    $message = $_POST['message'];

    if (!empty($user_id) && !empty($guardian_email) && !empty($message)) {
        // 🔎 Lookup guardian_id from email
        $guardian_email = $conn->real_escape_string($guardian_email);
        $user_id = (int)$user_id;
        $message = $conn->real_escape_string($message);

        $sqlGuardian = "SELECT id FROM guardians WHERE email='$guardian_email' LIMIT 1";
        $result = $conn->query($sqlGuardian);

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $guardian_id = $row['id'];

            // Insert into user_messages
            $sql = "INSERT INTO user_messages (user_id, guardian_id, message) 
                    VALUES ('$user_id', '$guardian_id', '$message')";

            if ($conn->query($sql) === TRUE) {
                echo json_encode(["status" => "success", "message" => "Message sent"]);
            } else {
                echo json_encode(["status" => "error", "message" => $conn->error]);
            }
        } else {
            echo json_encode(["status" => "error", "message" => "Guardian not found"]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Missing fields"]);
    }
}
?>
