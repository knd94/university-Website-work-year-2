<?php
session_start();
include('db.php');

// Fetch all events
$sql = "SELECT e.id, e.title, e.description, e.event_date, e.image_path, u.username, u.profile_picture
        FROM events e
        JOIN users u ON e.user_id = u.id
        ORDER BY e.event_date ASC";
$result = $conn->query($sql);

// Check for and delete expired events
$today = date("Y-m-d");
$deleteSql = "DELETE FROM events WHERE event_date < '$today'";
$conn->query($deleteSql);

// Display events
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
        ?>
        <div>
            <!-- Display user information (username and profile picture) -->
            <img src="<?php echo $row['profile_picture']; ?>" alt="Profile Picture" width="50">
            <strong><?php echo $row['username']; ?></strong>

            <!-- Display event information -->
            <h3><?php echo $row['title']; ?></h3>
            <p><?php echo $row['description']; ?></p>
            <p>Date: <?php echo $row['event_date']; ?></p>

            <!-- Display event picture -->
            <img src="<?php echo $row['image_path']; ?>" alt="Event Picture" width="300">

            <!-- Add more styling and HTML structure as needed -->
        </div>
        <?php
    }
    ?>

</body>
</html>
