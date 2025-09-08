<?php
header('Content-Type: application/json');
include 'dbconn.php';

$response = array();

if (isset($_POST['user_id']) && isset($_POST['status']) && isset($_POST['name']) 
    && isset($_POST['phone']) && isset($_POST['lat']) && isset($_POST['lng']) && isset($_POST['location'])) {

    $user_id = intval($_POST['user_id']);
    $status = intval($_POST['status']);
    $name = $_POST['name'];
    $phone = $_POST['phone'];
    $lat = $_POST['lat'];
    $lng = $_POST['lng'];
    $location = $_POST['location'];

    // Try updating existing row
    $updateSql = "UPDATE sos_status 
                  SET status = ?, name = ?, phone = ?, lat = ?, lng = ?, location = ?, updated_at = NOW() 
                  WHERE user_id = ?";
    $stmt = $conn->prepare($updateSql);

    if ($stmt) {
        $stmt->bind_param("issddsi", $status, $name, $phone, $lat, $lng, $location, $user_id);
        $stmt->execute();

        if ($stmt->affected_rows > 0) {
            $response['success'] = true;
            $response['message'] = "SOS info updated successfully.";
        } else {
            // Row not found → insert
            $insertSql = "INSERT INTO sos_status (user_id, status, name, phone, lat, lng, location, updated_at) 
                          VALUES (?, ?, ?, ?, ?, ?, ?, NOW())";
            $insertStmt = $conn->prepare($insertSql);
            if ($insertStmt) {
                $insertStmt->bind_param("iissdds", $user_id, $status, $name, $phone, $lat, $lng, $location);
                if ($insertStmt->execute()) {
                    $response['success'] = true;
                    $response['message'] = "SOS info created successfully.";
                } else {
                    $response['success'] = false;
                    $response['message'] = "Insert error: " . $insertStmt->error;
                }
                $insertStmt->close();
            }
        }
        $stmt->close();
    } else {
        $response['success'] = false;
        $response['message'] = "Update error: " . $conn->error;
    }
} else {
    $response['success'] = false;
    $response['message'] = "Missing parameters.";
}

echo json_encode($response);
$conn->close();
?>
