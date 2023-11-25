<?php
session_start();
include('db.php');
include('register.php'); // Include the registration script

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login_twig_cookies.php");
    exit();
}

// Set user_id if it exists in the session and is a valid integer
$user_id = (isset($_SESSION['user_id']) && is_numeric($_SESSION['user_id'])) ? $_SESSION['user_id'] : null;

// Initialize $stmt outside the conditional block
$stmt = null;

// Check if the create event form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['create_event'])) {
    // Validate and process the form data
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $event_date = $_POST['event_date'];  // Assuming you have a date input in your form

    // Upload image (you'll need to implement the image upload logic)
    $image_path = '';  // Replace this with the actual image path

    // Check if user_id is a valid integer
    if (!is_numeric($user_id)) {
        echo "Invalid user ID.";
        exit();
    }

    // Insert event data into the database
    $insertSql = "INSERT INTO events (user_id, title, description, event_date, image_path) 
                  VALUES (?, ?, ?, ?, ?)";

    // Use prepared statement to prevent SQL injection
    $stmt = $conn->prepare($insertSql);
    $stmt->bind_param("issss", $user_id, $title, $description, $event_date, $image_path);

    if ($stmt->execute()) {
        // Event created successfully, redirect to index.php
        header("Location: index.php");
        exit();
    } else {
        echo "Error creating event: " . $stmt->error;
    }

    // Close the statement
    $stmt->close();
}

// Close the database connection
$conn->close();
?>
<!-- Create Event Form HTML -->
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Event</title>
    <!-- Include any additional stylesheets or scripts if needed -->
</head>
<body>
    <h2>Create Event</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <label for="title">Title:</label>
        <input type="text" name="title" required><br>

        <label for="description">Description:</label>
        <textarea name="description" required></textarea><br>

        <label for="event_date">Event Date:</label>
        <input type="date" name="event_date" required><br>

        <!-- Add an input for image upload -->

        <input type="submit" name="create_event" value="Create Event">
    </form>
</body>
</html>
