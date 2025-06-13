<?php
require 'dbconn.php';

// Check if sender_id and receiver_id are set
if (isset($_GET['sender_id']) && isset($_GET['receiver_id'])) {
    $sender_id = $_GET['sender_id'];
    $receiver_id = $_GET['receiver_id'];

    // Prepare the SQL statement to fetch messages
    if ($stmt = $mysqli->prepare("SELECT * FROM messages 
                                  WHERE (sender_id = ? AND receiver_id = ?) 
                                     OR (sender_id = ? AND receiver_id = ?)
                                  ORDER BY sent_at ASC")) {

        // Bind parameters to the statement
        $stmt->bind_param("iiii", $sender_id, $receiver_id, $receiver_id, $sender_id);

        // Execute the statement
        if ($stmt->execute()) {

            // Get the result
            $result = $stmt->get_result();

            // Fetch all messages as an associative array
            $messages = $result->fetch_all(MYSQLI_ASSOC);

            // Output the result as JSON
            echo json_encode($messages);
        } else {
            // Error executing query
            echo json_encode(['status' => 'error', 'message' => 'Error executing query.']);
        }

        // Close the statement
        $stmt->close();
    } else {
        // Error preparing statement
        echo json_encode(['status' => 'error', 'message' => 'Error preparing the SQL statement.']);
    }
} else {
    // Missing sender_id or receiver_id
    echo json_encode(['status' => 'error', 'message' => 'sender_id and receiver_id are required.']);
}
?>
