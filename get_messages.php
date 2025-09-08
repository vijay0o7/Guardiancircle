<?php
include "dbconn.php";

if (isset($_GET['guardian_email'])) {
    $guardian_email = $_GET['guardian_email'];

    // Get guardian_id from email
    $stmt = $conn->prepare("SELECT id FROM guardians WHERE email = ?");
    $stmt->bind_param("s", $guardian_email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $guardian_id = $row['id'];

        // Now fetch messages
        $stmt2 = $conn->prepare("SELECT id, user_id, message, created_at 
                                 FROM user_messages 
                                 WHERE guardian_id = ? 
                                 ORDER BY created_at ASC");
        $stmt2->bind_param("i", $guardian_id);
        $stmt2->execute();
        $res2 = $stmt2->get_result();

        $messages = [];
        while ($msg = $res2->fetch_assoc()) {
            $messages[] = $msg;
        }

        echo json_encode($messages);

    } else {
        echo json_encode(["status" => "error", "message" => "guardian not found"]);
    }

} else {
    echo json_encode(["status" => "error", "message" => "guardian_email is required"]);
}
?>
