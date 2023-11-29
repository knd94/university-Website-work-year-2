<?php
// Include the database connection
include('db.php');

// Include database functions
include('db_functions.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login_twig_cookies.php");
    exit();
}

// Check if the accept form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['accept_request'])) {
    // Get the request ID to be accepted
    $request_id = $_POST['request_id'];

    // Include the friend request acceptance logic here
    acceptFriendRequest($conn, $request_id);

    // Display a success message or perform other actions after accepting the friend request
    $successMessage = "Friend request accepted successfully!";
}

// Redirect to the dashboard page
header("Location: dashboard.php");
exit();
?>
