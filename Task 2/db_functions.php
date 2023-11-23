<?php
// Function to get user profile picture
function getUserProfilePicture($username) {
    // Include the database connection
    include('db.php');

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

    // Close the database connection
    $conn->close();

    return $profilePicture;
}
?>
