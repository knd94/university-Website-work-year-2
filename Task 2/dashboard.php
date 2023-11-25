<?php
// Start the session
session_start();

// Include the database connection
include('db.php');

// Include database functions
include('db_functions.php');

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

    // Delete the event
    deleteEvent($conn, $event_id);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard</title>
    <!-- Include any additional stylesheets or scripts if needed -->
</head>
<body>
    <h2>Welcome, <?php echo $_SESSION['username']; ?>!</h2>

    <!-- Display user profile picture -->
    <?php
    if (!empty($profilePicture)) {
        echo '<img src="' . $profilePicture . '" alt="Profile Picture" style="width: 100px; height: 100px; border-radius: 50%;">';
    } else {
        echo '<p>No profile picture found for user ' . $_SESSION['username'] . '</p>';
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

                <!-- Add more styling and HTML structure as needed -->

                <!-- Delete Event Form -->
                <form method="post" action="">
                    <input type="hidden" name="event_id" value="<?php echo $event['id']; ?>">
                    <input type="submit" name="delete_event" value="Delete Event">
                </form>
            </div>
            <?php
        }
    } else {
        echo '<p>No events found for user ' . $_SESSION['username'] . '</p>';
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

    <!-- Add more content and features as needed -->

    <a href="logout.php">Logout</a>
    <a href="create_event.php">Create an event!</a>

    <!-- Include any additional scripts if needed -->
</body>
</html>
