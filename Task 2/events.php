<?php
session_start();
include('db.php');

// Fetch all events
$sql = "SELECT e.id, e.title, e.description, e.event_date, e.image_path, u.id as user_id, u.username, u.profile_picture
        FROM events e
        JOIN users u ON e.user_id = u.id
        ORDER BY e.event_date ASC";
$result = $conn->query($sql);

// Check for and delete expired events
$today = date("Y-m-d");
$deleteSql = "DELETE FROM events WHERE event_date < '$today'";
$conn->query($deleteSql);

// Display all events
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <!-- Include any additional stylesheets or scripts if needed -->
</head>
<body>
    <?php
    while ($row = $result->fetch_assoc()) {
        // Display individual events
        ?>
        <div>
            <!-- Link to user profile page -->
            <a href="user_profile.php?user_id=<?php echo $row['user_id']; ?>">
                <img src="<?php echo $row['profile_picture']; ?>" alt="Profile Picture" width="50">
                <strong><?php echo $row['username']; ?></strong>
            </a>

            <!-- Display event information -->
            <h3><?php echo $row['title']; ?></h3>
            <p><?php echo $row['description']; ?></p>
            <p>Date: <?php echo $row['event_date']; ?></p>

            <!-- Display event picture -->
            <img src="<?php echo $row['image_path']; ?>" alt="Event Picture" width="300">

            <!-- Registration form -->
<?php
if (isset($_SESSION['username'])) {
    $event_id = $row['id'];
    $username = $_SESSION['username'];

    // Fetch the user ID based on the username
    $getUserIDSql = "SELECT id FROM users WHERE username = '$username'";
    $getUserIDResult = $conn->query($getUserIDSql);

    if ($getUserIDResult && $getUserIDResult->num_rows > 0) {
        $user_id = $getUserIDResult->fetch_assoc()['id'];

        // Check if the user is already registered for the event
        $checkSql = "SELECT * FROM event_registrations WHERE event_id = $event_id AND user_id = $user_id";
        $checkResult = $conn->query($checkSql);

        // Button text based on registration status
        $buttonText = $checkResult && $checkResult->num_rows > 0 ? "Unregister from Event" : "Register for Event";
        $formAction = "register_event.php";

        ?>
        <!-- Registration form with dynamic button text -->
        <form method="post" action="<?php echo $formAction; ?>">
            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>">
            <input type="submit" name="register_event" value="<?php echo $buttonText; ?>">
        </form>
        <?php
    } else {
        echo "User not found or error in fetching user ID.";
    }
} else {
    echo "Username not found in session. Please log in.";
}
?>
<!-- Add more styling and HTML structure as needed -->
        </div>
        <?php
    }
    ?>
</body>
</html>
