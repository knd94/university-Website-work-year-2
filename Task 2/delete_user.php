<?php
// Start the session
session_start();

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login_twig_cookies.php");
    exit();
}

// Include the database connection
include('db.php');

// Get the username of the logged-in user
$username = $_SESSION['username'];

// Delete the user from the database
$sql = "DELETE FROM users WHERE username = '$username'";

if ($conn->query($sql) === TRUE) {
    // Unset all session variables
    $_SESSION = array();

    // Destroy the session
    session_destroy();

    // Redirect to the login page with a success message
    header("Location: login_twig_cookies.php?message=account_deleted");
    exit();
} else {
    // Redirect to the dashboard with an error message
    header("Location: dashboard.php?error=delete_failed&message=" . urlencode($conn->error));
    exit();
}

// Close the database connection
$conn->close();
?>
