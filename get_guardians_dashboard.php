<?php
header('Content-Type: application/json');
include "dbconn.php";

$guardian_id = $_GET['guardian_id'];

$stmt = $conn->prepare("
    SELECT u.name AS protected_user, u.last_seen, g.live_sos_monitor, u.status
    FROM guardians g
    JOIN users u ON u.id = g.user_id
    WHERE g.guardian_id = ?
");
$stmt->bind_param("i", $guardian_id);
$stmt->execute();
$result = $stmt->get_result();

$dashboard = $result->fetch_all(MYSQLI_ASSOC);

echo json_encode([
    "status" => "success",
    "data" => $dashboard
]);
?>
