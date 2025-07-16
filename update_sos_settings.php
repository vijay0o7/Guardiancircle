<?php
header('Content-Type: application/json');
include "dbconn.php";

$user_id = $_POST['user_id'];
$shake = $_POST['shake_to_send'];
$power = $_POST['power_button_sos'];
$auto = $_POST['auto_record'];
$police = $_POST['police_number'];
$ambulance = $_POST['ambulance_number'];

$stmt = $conn->prepare("
    INSERT INTO sos_settings (user_id, shake_to_send, power_button_sos, auto_record, police_number, ambulance_number)
    VALUES (?, ?, ?, ?, ?, ?)
    ON DUPLICATE KEY UPDATE 
        shake_to_send = VALUES(shake_to_send),
        power_button_sos = VALUES(power_button_sos),
        auto_record = VALUES(auto_record),
        police_number = VALUES(police_number),
        ambulance_number = VALUES(ambulance_number)
");
$stmt->bind_param("iiiiss", $user_id, $shake, $power, $auto, $police, $ambulance);

if ($stmt->execute()) {
    echo json_encode(["status" => "success", "message" => "SOS settings saved"]);
} else {
    echo json_encode(["status" => "error", "message" => $stmt->error]);
}
$conn->close();
?>
