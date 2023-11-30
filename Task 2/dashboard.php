<?php
// Start the session
session_start();

// Include the database connection
include('db.php');

// Include database functions
require_once('db_functions.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login_twig_cookies.php");
    exit();
}

// Fetch user profile picture from the database
$profilePicture = getUserProfilePicture($conn, $_SESSION['username']);

// Fetch user events
$userEvents = getUserEvents($conn, $_SESSION['username']);

// Check if the delete form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    // Include the delete_user.php script
    include('delete_user.php');
}

// Check if the profile picture form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_picture'])) {
    // Include the upload_picture.php script
    include('upload_picture.php');
}

// Check if the delete event form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_event'])) {
    // Get the event ID to be deleted
    $event_id = $_POST['event_id'];

    // Delete the event and notify
    deleteEventAndNotify($conn, $event_id);
    // Redirect to refresh the page after deletion
    header("Location: dashboard.php");
    exit();
}

// Fetch notifications
$notifications = getNotifications($conn, $_SESSION['user_id']);

// Check if a friend request is accepted
if (isset($_GET['accept_request']) && isset($_GET['request_id'])) {
    $request_id = $_GET['request_id'];

    // Accept the friend request
    acceptFriendRequest($conn, $request_id);

    // Display a success message or handle errors
    $successMessage = "Friend request accepted successfully!";
}

// Fetch friend requests
$friendRequests = getFriendRequests($conn, $_SESSION['user_id']);

// Check if a friend request is declined
if (isset($_GET['decline_request']) && isset($_GET['request_id'])) {
    $request_id = $_GET['request_id'];

    // Decline the friend request
    declineFriendRequest($conn, $request_id);

    // Display a success message or perform other actions after declining the friend request
    $successMessage = "Friend request declined successfully!";
}

// Fetch user's friends
$userFriends = getUserFriends($conn, $_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?> to the dashboard!</h2>

    <!-- Display user profile picture -->
    <?php
    if (!empty($profilePicture)) {
        echo '<img src="' . $profilePicture . '" alt="Profile Picture" style="width: 100px; height: 100px; border-radius: 50%;">';
    } else {
        echo '<p>No profile picture found for user ' . $_SESSION['username'] . '</p>';
    }
    ?>

    <!-- Display notifications or success messages -->
    <?php
    if (!empty($notifications)) {
        echo '<h3>Notifications:</h3>';
        var_dump($notifications);  // Output notifications for debugging purposes

        foreach ($notifications as $notification) {
            echo '<p>' . $notification['message'] . '</p>';
        }
    }

    if (isset($successMessage)) {
        echo '<p>' . $successMessage . '</p>';
    }

    if (isset($errorMessage)) {
        echo '<p style="color: red;">' . $errorMessage . '</p>';
    }
    ?>

    <!-- Display a list of friend requests -->
    <h3>Friend Requests</h3>
    <?php
    if (!empty($friendRequests)) {
        foreach ($friendRequests as $request) {
            echo '<p>Friend request from ' . getUsernameById($conn, $request['sender_id']) . '</p>';
            // Add logic for Accept and Decline buttons as needed
            echo '<a href="?accept_request=1&request_id=' . $request['id'] . '">Accept</a>';
            echo '<a href="?decline_request=1&request_id=' . $request['id'] . '">Decline</a>';
        }
    } else {
        echo '<p>No friend requests found.</p>';
    }
    ?>

    <!-- Display a list of user events -->
    <h3>Your Events</h3>
    <?php
    if (!empty($userEvents)) {
        foreach ($userEvents as $event) {
            ?>
            <div>
                <!-- Display event information -->
                <h3><?php echo $event['title']; ?></h3>
                <p><?php echo $event['description']; ?></p>
                <p>Date: <?php echo $event['event_date']; ?></p>

                <!-- Display event picture -->
                <img src="<?php echo $event['image_path']; ?>" alt="Event Picture" width="300">


                <!-- Delete Event Form -->
                <form method="post" action="">
                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                    <input type="submit" name="delete_event" value="Delete Event">
                </form>

                <!-- Register/Unregister Form -->
                <form method="post" action="">
                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                    <?php
                        $isRegistered = isUserRegistered($conn, $_SESSION['username'], $event['id']);
                        if ($isRegistered) {
                            echo '<input type="submit" name="unregister_event" value="Unregister">';
                        } else {
                            echo '<input type="submit" name="register_event" value="Register">';
                        }
                    ?>
                </form>
            </div>
            <?php
        }
    } else {
        echo '<p>No events found for user ' . $_SESSION['username'] . '</p>';
    }
    ?>

<h3>Your Friends</h3>
<?php
if (!empty($userFriends)) {
    foreach ($userFriends as $friend) {
        // Add chat_room_id parameter to the link
        $chatRoomId = getChatRoomId($conn, $_SESSION['user_id'], $friend['friend_id']);
        echo '<p><a href="chat_room.php?friend_id=' . $friend['friend_id'] . '&chat_room_id=' . $chatRoomId . '">Chat with ' . getUsernameById($conn, $friend['friend_id']) . '</a></p>';
    }
} else {
    echo '<p>No friends found.</p>';
}
?>

    <!-- Delete User Form -->
    <form method="post" action="">
        <input type="submit" name="delete_user" value="Delete My Account">
    </form>

    <!-- Profile Picture Form -->
    <form method="post" action="" enctype="multipart/form-data">
        <label for="profile_picture">Profile Picture:</label>
        <input type="file" name="profile_picture" id="profile_picture" accept="image/*">
        <input type="submit" name="upload_picture" value="Upload Picture">
    </form>

    <a href="logout.php">Logout</a>
    <a href="create_event.php">Create an event!</a>

</body>
</html>
