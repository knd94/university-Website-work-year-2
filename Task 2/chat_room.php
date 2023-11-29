<?php
// Start the session
session_start();

// Include the database connection
include('db.php');

// Include database functions
require_once('db_functions.php');

// Retrieve chat room ID from the URL
$chatRoomId = isset($_GET['chat_room_id']) ? $_GET['chat_room_id'] : null;

// Ensure the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login_twig_cookies.php");
    exit();
}

// Function to get chat messages for a specific chat room
function getChatMessages($conn, $chatRoomId) {
    // Placeholder logic to fetch chat messages from the database
    // Implement your database query here and return the chat messages

    // Example query to retrieve messages from the chat_messages table
    $sql = "SELECT username, message FROM chat_messages
            JOIN users ON chat_messages.user_id = users.id
            WHERE chat_messages.chat_room_id = ?";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $chatRoomId);
    $stmt->execute();
    $result = $stmt->get_result();
    $messages = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();

    return $messages;
}

// Retrieve chat messages for the current chat room
$chatMessages = getChatMessages($conn, $chatRoomId);

// Display chat messages
foreach ($chatMessages as $message) {
    echo '<p>' . $message['username'] . ': ' . $message['message'] . '</p>';
}

// Function to insert a new chat message into the database
function insertChatMessage($conn, $chatRoomId, $userId, $message) {
    // Placeholder logic to insert a new chat message into the database
    // Implement your database query here

    // Example query using prepared statement
    $insertSql = "INSERT INTO chat_messages (chat_room_id, user_id, message) VALUES (?, ?, ?)";
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("iis", $chatRoomId, $userId, $message);
    $stmt->execute();
    $stmt->close();
}

// Retrieve chat messages for the current chat room
$chatMessages = getChatMessages($conn, $chatRoomId);

// Display chat messages
foreach ($chatMessages as $message) {
    echo '<p>' . $message['username'] . ': ' . $message['message'] . '</p>';
}

// Form to submit new messages
?>
<form method="post" action="">
    <input type="text" name="message" placeholder="Type your message...">
    <input type="submit" name="send_message" value="Send">
</form>
<?php

// Handle the form submission to send a new message
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['send_message'])) {
    $message = $_POST['message'];
    $userId = $_SESSION['user_id'];

    // Insert the new message into the database
    insertChatMessage($conn, $chatRoomId, $userId, $message);

    // Refresh the page to show the new message
    header("Location: chat_room.php?chat_room_id=" . $chatRoomId);
    exit();
}

// Include any additional scripts or styling as needed
?>
