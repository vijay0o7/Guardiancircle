<?php
header('Content-Type: application/json');

// Database connection
$mysqli = new mysqli("localhost", "root", "", "gaurdian_circle");

if ($mysqli->connect_error) {
    echo json_encode(["error" => "Database connection failed"]);
    exit();
}

// Get user_id from GET
if (!isset($_GET['user_id'])) {
    echo json_encode(["error" => "Missing user_id"]);
    exit();
}

$user_id = intval($_GET['user_id']);
$response = [];

// 1. Get user name from auth table
$user_sql = "SELECT name FROM auth WHERE id = ?";
$stmt = $mysqli->prepare($user_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$user_result = $stmt->get_result();
if ($row = $user_result->fetch_assoc()) {
    $response['name'] = $row['name'];
} else {
    $response['name'] = "User";
}
$stmt->close();

// 2. Get location sharing status
$loc_sql = "SELECT sharing_status FROM user_locations WHERE user_id = ? ORDER BY last_updated DESC LIMIT 1";
$stmt = $mysqli->prepare($loc_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$loc_result = $stmt->get_result();
if ($row = $loc_result->fetch_assoc()) {
    $response['sharing_status'] = $row['sharing_status'];
} else {
    $response['sharing_status'] = "OFF";
}
$stmt->close();

// 3. Get number of active guardians
$guard_sql = "SELECT COUNT(*) AS active_count FROM guardians WHERE user_id = ? AND status = 'ACTIVE'";
$stmt = $mysqli->prepare($guard_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$guard_result = $stmt->get_result();
if ($row = $guard_result->fetch_assoc()) {
    $response['guardians_count'] = intval($row['active_count']);
} else {
    $response['guardians_count'] = 0;
}
$stmt->close();

// 4. Get last journey hours
$journey_sql = "SELECT TIMESTAMPDIFF(HOUR, end_time, NOW()) AS hours_ago 
                FROM journeys 
                WHERE user_id = ? AND end_time IS NOT NULL 
                ORDER BY end_time DESC LIMIT 1";
$stmt = $mysqli->prepare($journey_sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$journey_result = $stmt->get_result();
if ($row = $journey_result->fetch_assoc()) {
    $response['last_journey_hours'] = $row['hours_ago'] ?? null;
} else {
    $response['last_journey_hours'] = null;
}
$stmt->close();

// Output JSON
echo json_encode($response, JSON_PRETTY_PRINT);

$mysqli->close();
?>
