<?php
session_start();
var_dump($_SESSION); // Output session variables for debugging

include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['register_event'])) {
    // Check if 'username' key exists in the $_SESSION array
    if (isset($_SESSION['username'])) {
        $event_id = $_POST['event_id'];
        $username = $_SESSION['username'];

        // Fetch the user ID based on the username
        $getUserIDSql = "SELECT id FROM users WHERE username = '$username'";
        $getUserIDResult = $conn->query($getUserIDSql);

        if ($getUserIDResult) {
            if ($getUserIDResult->num_rows > 0) {
                $row = $getUserIDResult->fetch_assoc();
                $user_id = $row['id'];

                // Check if the user is already registered for the event
                $checkSql = "SELECT * FROM event_registrations WHERE event_id = $event_id AND user_id = $user_id";
                $checkResult = $conn->query($checkSql);

                if ($checkResult) {
                    if ($checkResult->num_rows == 0) {
                        // If not registered, insert into the event_registrations table
                        $insertSql = "INSERT INTO event_registrations (event_id, user_id, username) VALUES ($event_id, $user_id, '$username')";
                        if ($conn->query($insertSql)) {
                            echo "Successfully registered for the event!";
                        } else {
                            echo "Error: " . $conn->error;
                        }
                    } else {
                        // User is already registered, provide option to unregister
                        $unregisterSql = "DELETE FROM event_registrations WHERE event_id = $event_id AND user_id = $user_id";
                        if ($conn->query($unregisterSql)) {
                            echo "Successfully unregistered from the event!";
                        } else {
                            echo "Error: " . $conn->error;
                        }
                    }
                } else {
                    echo "Error: " . $conn->error;
                }

                // Redirect back to events.php after registration or unregistration
                header("Location: events.php");
                exit;
            } else {
                echo "User not found.";
            }
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Username not found in session. Please log in.";
    }
}
?>
