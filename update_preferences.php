<?php
header('Content-Type: application/json');
include "dbconn.php";

$user_id = $_POST['user_id'];
$loc = $_POST['location_access'];
$cam = $_POST['camera_access'];
$mic = $_POST['mic_access'];
$push = $_POST['push_enabled'];
$email = $_POST['email_enabled'];
$sms = $_POST['sms_enabled'];

$stmt = $conn->prepare("
    INSERT INTO user_preferences (user_id, location_access, camera_access, mic_access, push_enabled, email_enabled, sms_enabled)
    VALUES (?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        location_access = VALUES(location_access),
        camera_access = VALUES(camera_access),
        mic_access = VALUES(mic_access),
        push_enabled = VALUES(push_enabled),
        email_enabled = VALUES(email_enabled),
        sms_enabled = VALUES(sms_enabled)
");
$stmt->bind_param("iiiiiii", $user_id, $loc, $cam, $mic, $push, $email, $sms);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Preferences updated"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}
$conn->close();
?>
