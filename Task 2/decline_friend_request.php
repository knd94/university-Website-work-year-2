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

// Check if the decline form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['decline_request'])) {
    // Get the request ID to be declined
    $request_id = $_POST['request_id'];

    // Include the friend request decline logic here
    declineFriendRequest($conn, $request_id);

    // Display a success message or perform other actions after declining the friend request
    $successMessage = "Friend request declined successfully!";
}

// Redirect to the dashboard page
header("Location: dashboard.php");
exit();
?>