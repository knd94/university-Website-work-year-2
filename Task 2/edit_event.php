<?php
session_start();
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit;
}

// Check if the event ID is provided in the URL
if (!isset($_GET['event_id'])) {
    echo "Event ID not provided.";
    exit;
}

$event_id = $_GET['event_id'];
$username = $_SESSION['username'];

// Fetch event details
$eventDetailsSql = "SELECT e.id, e.title, e.description, e.event_date, e.image_path, u.username
                    FROM events e
                    JOIN users u ON e.user_id = u.id
                    WHERE e.id = $event_id AND u.username = '$username'";
$eventDetailsResult = $conn->query($eventDetailsSql);

if (!$eventDetailsResult || $eventDetailsResult->num_rows === 0) {
    echo "Event not found or you don't have permission to edit.";
    exit;
}

$row = $eventDetailsResult->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['edit_event'])) {
    $title = isset($_POST['title']) ? $_POST['title'] : '';
    $description = isset($_POST['description']) ? $_POST['description'] : '';
    $event_date = isset($_POST['event_date']) ? $_POST['event_date'] : '';

    // Check if the date is not empty
    if (!empty($event_date)) {
        // Use prepared statement to update the event
        $updateEventSql = "UPDATE events SET title = ?, description = ?, event_date = ? WHERE id = ? AND user_id = (SELECT id FROM users WHERE username = ?)";

        $stmt = $conn->prepare($updateEventSql);
        $stmt->bind_param("sssis", $title, $description, $event_date, $event_id, $username);

        if ($stmt->execute()) {
            echo "Event updated successfully!";
        } else {
            echo "Error updating event: " . $stmt->error;
        }

        $stmt->close();
}
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Event</title>
    <!-- Include any additional stylesheets or scripts if needed -->
</head>
<body>

<!-- Display event details -->
<h1>Edit Event</h1>
<form method="post" action="">
    <label for="title">Title:</label>
    <input type="text" name="title" value="<?php echo isset($row['title']) ? $row['title'] : ''; ?>" required><br>

    <label for="description">Description:</label>
    <textarea name="description" rows="4" required><?php echo isset($row['description']) ? $row['description'] : ''; ?></textarea><br>

    <label for="event_date">Event Date:</label>
    <input type="date" name="event_date" value="<?php echo isset($row['event_date']) ? $row['event_date'] : ''; ?>" required><br>

    <!-- Include other event details fields as needed -->

    <input type="submit" name="edit_event" value="Update Event">
</form>

    <a href="events.php">Back to Events</a>

</body>
</html>
