<?php
header('Content-Type: application/json');
include 'dbconn.php';

$response = array("success" => false, "status" => 0);

if (isset($_GET['user_id'])) {
    $user_id = intval($_GET['user_id']);

    $sql = "SELECT status, updated_at, name, phone, lat, lng, location 
            FROM sos_status 
            WHERE user_id = ? 
            ORDER BY id DESC 
            LIMIT 1";  // get latest SOS record
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($row = $result->fetch_assoc()) {
        $response['success'] = true;
        $response['status'] = intval($row['status']);
        $response['updated_at'] = $row['updated_at'];
        $response['name'] = $row['name'];
        $response['phone'] = $row['phone'];
        $response['lat'] = floatval($row['lat']);
        $response['lng'] = floatval($row['lng']);
        $response['location'] = $row['location'];
    } else {
        $response['success'] = true;
        $response['status'] = 0; // Default OFF
    }
    $stmt->close();
} else {
    $response['message'] = "Missing user_id";
}

echo json_encode($response);
$conn->close();
?>
