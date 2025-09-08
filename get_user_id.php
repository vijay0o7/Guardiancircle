<?php
// Include database connection
include 'dbconn.php';

// Initialize response
$response = array();

// Check if 'email' is provided
if (isset($_POST['email'])) {
    $email = $conn->real_escape_string($_POST['email']);

    // Query to get all user_names with the given email
    $sql = "SELECT user_name FROM guardians WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $user_names = array();
        while ($row = $result->fetch_assoc()) {
            $user_names[] = $row['user_name'];
        }
        $response['status'] = "success";
        $response['user_names'] = $user_names;
    } else {
        $response['status'] = "error";
        $response['message'] = "No user found with that email";
    }
} else {
    $response['status'] = "error";
    $response['message'] = "No email provided";
}

// Return JSON
echo json_encode($response);

// Close connection
$conn->close();
?>
