<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_friend_request'])) {
    // Check if the user is logged in
    if (!isset($_SESSION['user_id'])) {
        // Redirect if not logged in
        header("Location: login_twig_cookies.php");
        exit();
    }

    // Get the receiver's user ID from the form
    $receiverID = $_POST['receiver_id'];

    // Check if the friend request already exists
    $checkRequestSql = "SELECT id FROM friend_requests WHERE sender_id = ? AND receiver_id = ?";
    $stmtCheckRequest = $conn->prepare($checkRequestSql);
    $stmtCheckRequest->bind_param("ii", $_SESSION['user_id'], $receiverID);
    $stmtCheckRequest->execute();
    $stmtCheckRequest->store_result();

    if ($stmtCheckRequest->num_rows == 0) {
        // If the friend request doesn't exist, insert a new request
        $insertRequestSql = "INSERT INTO friend_requests (sender_id, receiver_id, status, request_date) VALUES (?, ?, 'pending', NOW())";
        $stmtInsertRequest = $conn->prepare($insertRequestSql);
        $stmtInsertRequest->bind_param("ii", $_SESSION['user_id'], $receiverID);
        $stmtInsertRequest->execute();
        $stmtInsertRequest->close();

        // Redirect to the user profile or any other page
        header("Location: user_profile.php?user_id=$receiverID");
        exit();
    } else {
        // Redirect if the friend request already exists
        header("Location: user_profile.php?user_id=$receiverID");
        exit();
    }
} else {
    // Redirect if accessed without proper form submission
    header("Location: events.php");
    exit();
}
?>
