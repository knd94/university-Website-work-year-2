<?php

function getUserProfilePicture($conn, $username) {
    $profilePicture = ''; // Default profile picture URL

    $sql = "SELECT profile_picture FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result) {
        $row = $result->fetch_assoc();
        if ($row) {
            $profilePicture = $row['profile_picture'];
        } else {
            // Debugging information
            echo "No rows found for user $username";
        }
        $result->free();  // Free the result set
    } else {
        // Handle query error
        echo "Error: " . $conn->error;
    }

    return $profilePicture;
}

function deleteEvent($conn, $event_id) {
    // Use prepared statement to prevent SQL injection
    $deleteSql = "DELETE FROM events WHERE id = ?";

    // Prepare the statement
    $stmt = $conn->prepare($deleteSql);

    // Bind the event ID parameter
    $stmt->bind_param("i", $event_id);

    // Execute the statement
    $stmt->execute();

    // Close the statement
    $stmt->close();
}

function getUserEvents($conn, $username) {
    // Fetch events for a specific user
    $sql = "SELECT id, title, description, event_date, image_path FROM events WHERE username = '$username' ORDER BY event_date ASC";
    $result = $conn->query($sql);

    if ($result) {
        $events = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();  // Free the result set
        return $events;
    } else {
        // Handle query error
        echo "Error: " . $conn->error;
        return array();
    }
}
?>
