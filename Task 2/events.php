<?php
session_start();
include('db.php');

// Initialize variables
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;
$today = date("Y-m-d");

// Handle filter form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['filter_events'])) {
    $filterOption = $_POST['filter_option'];

    // Set a cookie to remember the user's filter preference
    setcookie('filter_option', $filterOption, time() + (30 * 24 * 60 * 60), '/');
} else {
    // Use the cookie value if available
    $filterOption = isset($_COOKIE['filter_option']) ? $_COOKIE['filter_option'] : 'all_events';
}

// Fetch events based on the filter option
switch ($filterOption) {
    case 'your_events':
        $sql = "SELECT e.id, e.title, e.description, e.event_date, e.image_path, u.id as user_id, u.username, u.profile_picture
                FROM events e
                JOIN users u ON e.user_id = u.id
                WHERE u.username = '$username'
                ORDER BY e.event_date ASC";
        break;

    case 'registered_events':
        $sql = "SELECT e.id, e.title, e.description, e.event_date, e.image_path, u.id as user_id, u.username, u.profile_picture
                FROM events e
                JOIN users u ON e.user_id = u.id
                WHERE EXISTS (
                    SELECT 1 FROM event_registrations er
                    WHERE er.event_id = e.id AND er.username = '$username'
                )
                ORDER BY e.event_date ASC";
        break;

    case 'others_events':
        $sql = "SELECT e.id, e.title, e.description, e.event_date, e.image_path, u.id as user_id, u.username, u.profile_picture
                FROM events e
                JOIN users u ON e.user_id = u.id
                WHERE u.username <> '$username'
                ORDER BY e.event_date ASC";
        break;

    default:
        $sql = "SELECT e.id, e.title, e.description, e.event_date, e.image_path, u.id as user_id, u.username, u.profile_picture
                FROM events e
                JOIN users u ON e.user_id = u.id
                ORDER BY e.event_date ASC";
}

// Fetch registered events for the user
$registeredEvents = array();
if ($filterOption === 'registered_events') {
    $getRegisteredEventsSql = "SELECT event_id FROM event_registrations WHERE username = '$username'";
    $getRegisteredEventsResult = $conn->query($getRegisteredEventsSql);

    if ($getRegisteredEventsResult) {
        while ($row = $getRegisteredEventsResult->fetch_assoc()) {
            $registeredEvents[] = $row['event_id'];
        }
    }
}
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
    <!-- Filter form -->
    <form method="post" action="">
        <label for="filter_option">Filter Events:</label>
        <select name="filter_option" id="filter_option">
            <option value="your_events" <?php echo ($filterOption === 'your_events') ? 'selected' : ''; ?>>Your Events</option>
            <option value="registered_events" <?php echo ($filterOption === 'registered_events') ? 'selected' : ''; ?>>Registered Events</option>
            <option value="others_events" <?php echo ($filterOption === 'others_events') ? 'selected' : ''; ?>>Other People's Events</option>
            <option value="all_events" <?php echo ($filterOption === 'all_events') ? 'selected' : ''; ?>>All Events</option>
        </select>
        <input type="submit" name="filter_events" value="Filter">
    </form>

    <?php
    $result = $conn->query($sql);

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
            <form method="post" action="register_event.php">
                <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
                <input type="submit" name="register_event" value="<?php echo (in_array($row['id'], $registeredEvents)) ? 'Unregister from Event' : 'Register for Event'; ?>">
            </form>

            <!-- Add more styling and HTML structure as needed -->
        </div>
        <?php
    }
    ?>

    <!-- Add more styling and HTML structure as needed -->
</body>
</html>
