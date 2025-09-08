<?php
// Include database connection
include 'dbconn.php';

// Initialize response
$response = array();

// Check if 'email' and 'userName' are passed
if (isset($_POST['email']) && isset($_POST['userName'])) {
    $email = $conn->real_escape_string($_POST['email']);
    $userName = $conn->real_escape_string($_POST['userName']);

    // Update query - change status to 'accepted' for given email and userName
    $sql = "UPDATE guardians SET status='rejected' WHERE email='$email' AND user_name='$userName'";

    if ($conn->query($sql) === TRUE) {
        if ($conn->affected_rows > 0) {
            $response['status'] = "success";
            $response['message'] = "Status updated to rejected for $userName with email $email";
        } else {
            $response['status'] = "error";
            $response['message'] = "No record found with the given email and userName";
        }
    } else {
        $response['status'] = "error";
        $response['message'] = "Error updating status: " . $conn->error;
    }
} else {
    $response['status'] = "error";
    $response['message'] = "Email and userName must be provided";
}

// Return JSON response
echo json_encode($response);

// Close connection
$conn->close();
?>
