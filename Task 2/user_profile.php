<?php
session_start();
include('db.php');

// Check if a user_id is provided in the URL
if (isset($_GET['user_id'])) {
    $user_id = $_GET['user_id'];

    // Fetch user details
    $userSql = "SELECT id, username, profile_picture FROM users WHERE id = ?";
    $stmtUser = $conn->prepare($userSql);
    $stmtUser->bind_param("i", $user_id);
    $stmtUser->execute();
    $stmtUser->bind_result($user_id, $username, $profile_picture);
    $stmtUser->fetch();
    $stmtUser->close();

    // Fetch user's events
    $eventsSql = "SELECT id, title, description, event_date, image_path FROM events WHERE user_id = ?";
    $stmtEvents = $conn->prepare($eventsSql);
    $stmtEvents->bind_param("i", $user_id);
    $stmtEvents->execute();
    $resultEvents = $stmtEvents->get_result();
    $stmtEvents->close();

    // Display a button to send a friend request if the user is logged in and not viewing their own profile
    $sendFriendRequestButton = '';
    if (isset($_SESSION['user_id']) && $_SESSION['user_id'] != $user_id) {
        $sendFriendRequestButton = '<form method="post" action="send_friend_request.php">
            <input type="hidden" name="receiver_id" value="' . $user_id . '">
            <input type="submit" name="send_friend_request" value="Send Friend Request">
        </form>';
    }
} else {
    // Redirect if user_id is not provided
    header("Location: events.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <!-- Include any additional stylesheets or scripts if needed -->
</head>
<body>
    <h2>User Profile: <?php echo $username; ?></h2>

    <!-- Display user's profile picture -->
    <img src="<?php echo $profile_picture; ?>" alt="Profile Picture" width="100">

    <!-- Display a button to send a friend request -->
    <?php echo $sendFriendRequestButton; ?>

    <!-- Display user's events -->
    <?php
    while ($row = $resultEvents->fetch_assoc()) {
        ?>
        <div>
            <h3><?php echo $row['title']; ?></h3>
            <p><?php echo $row['description']; ?></p>
            <p>Date: <?php echo $row['event_date']; ?></p>
            
            <!-- Display event picture -->
            <img src="<?php echo $row['image_path']; ?>" alt="Event Picture" width="300">
            
        </div>
        <?php
    }
    ?>
</body>
</html>
