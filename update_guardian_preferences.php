<?php
header('Content-Type: application/json');
include "dbconn.php";

$guardian_id = $_POST['guardian_id'];
$alert = $_POST['alert_on_sos'];
$auto = $_POST['auto_sound_alert'];
$vibrate = $_POST['vibrate'];
$journey = $_POST['journey_notifications'];
$checkin = $_POST['daily_checkin'];
$mute = $_POST['mute_all'];

$stmt = $conn->prepare("
    INSERT INTO guardian_preferences (guardian_id, alert_on_sos, auto_sound_alert, vibrate, journey_notifications, daily_checkin, mute_all)
    VALUES (?, ?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE
        alert_on_sos = VALUES(alert_on_sos),
        auto_sound_alert = VALUES(auto_sound_alert),
        vibrate = VALUES(vibrate),
        journey_notifications = VALUES(journey_notifications),
        daily_checkin = VALUES(daily_checkin),
        mute_all = VALUES(mute_all)
");
$stmt->bind_param("iiiiiii", $guardian_id, $alert, $auto, $vibrate, $journey, $checkin, $mute);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "Preferences updated"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}
$conn->close();
?>
