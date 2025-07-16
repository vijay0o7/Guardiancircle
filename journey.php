<?php
header('Content-Type: application/json');
include "dbconn.php";

// Collect post data
$action = $_POST['action'];
$user_id = $_POST['user_id'];

function generateCustomJourneyId($conn) {
    do {
        $randomNumber = random_int(10000, 99999); // 5-digit random int
        $journey_id = "journey_" . $randomNumber;

        // Check for uniqueness
        $check = $conn->prepare("SELECT journey_id FROM journeys WHERE journey_id = ?");
        $check->bind_param("s", $journey_id);
        $check->execute();
        $result = $check->get_result();
    } while ($result->num_rows > 0); // repeat if ID exists

    return $journey_id;
}

if ($action === 'start') {
    $journey_id = generateCustomJourneyId($conn);
    $current_location = $_POST['current_location'];
    $destination = $_POST['destination'];
    $estimated_time = $_POST['estimated_time'];

    $stmt = $conn->prepare("INSERT INTO journeys (journey_id, user_id, current_location, destination, estimated_time) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("sissi", $journey_id, $user_id, $current_location, $destination, $estimated_time);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Journey started!",
            "journey_id" => $journey_id
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to start journey: " . $stmt->error
        ]);
    }

} elseif ($action === 'cancel' || $action === 'arrived') {
    $journey_id = $_POST['journey_id'];
    $new_status = $action === 'cancel' ? 'cancelled' : 'arrived';

    $stmt = $conn->prepare("UPDATE journeys SET status = ? WHERE journey_id = ?");
    $stmt->bind_param("ss", $new_status, $journey_id);

    if ($stmt->execute()) {
        echo json_encode([
            "status" => "success",
            "message" => "Journey status updated to $new_status"
        ]);
    } else {
        echo json_encode([
            "status" => "error",
            "message" => "Failed to update journey status"
        ]);
    }
} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid action"
    ]);
}

$conn->close();
?>
