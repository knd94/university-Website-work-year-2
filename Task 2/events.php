<?php
session_start();
include('db.php');

// Function to get dynamic button text
function getButtonText($filterOption, $eventId, $registeredEvents) {
    switch ($filterOption) {
        case 'your_events':
            return 'Edit Event';

        case 'registered_events':
            return in_array($eventId, $registeredEvents) ? 'Unregister from Event' : 'Register for Event';

        case 'others_events':
            return in_array($eventId, $registeredEvents) ? 'Unregister from Event' : 'Register for Event';

        default:
            return in_array($eventId, $registeredEvents) ? 'Unregister from Event' : 'Register for Event';
    }
}

// Initialize variables
$username = isset($_SESSION['username']) ? $_SESSION['username'] : null;

// Handle filter form submission
$filterOption = isset($_GET['filter_option']) ? $_GET['filter_option'] : 'all_events';
$searchTerm = isset($_GET['search_term']) ? $_GET['search_term'] : '';

// Set a cookie to remember the user's filter preference
setcookie('filter_option', $filterOption, time() + (30 * 24 * 60 * 60), '/');

// Fetch events based on the filter option and search term
$sql = "SELECT e.id, e.title, e.description, e.event_date, e.image_path, u.id as user_id, u.username, u.profile_picture
        FROM events e
        JOIN users u ON e.user_id = u.id";

switch ($filterOption) {
    case 'your_events':
        $sql .= " WHERE u.username = '$username'";
        break;

    case 'registered_events':
        $sql .= " WHERE EXISTS (
                    SELECT 1 FROM event_registrations er
                    WHERE er.event_id = e.id AND er.username = '$username'
                )";
        break;

    case 'others_events':
        $sql .= " WHERE u.username <> '$username'";
        break;
}

if (!empty($searchTerm)) {
    $sql .= " AND (e.title LIKE '%$searchTerm%' OR e.description LIKE '%$searchTerm%' OR u.username LIKE '%$searchTerm%')";
}

$sql .= " ORDER BY e.event_date ASC";

// Fetch registered events for the user
$registeredEvents = array();
$getRegisteredEventsSql = "SELECT event_id FROM event_registrations WHERE username = '$username'";
$getRegisteredEventsResult = $conn->query($getRegisteredEventsSql);

if ($getRegisteredEventsResult) {
    while ($row = $getRegisteredEventsResult->fetch_assoc()) {
        $registeredEvents[] = $row['event_id'];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events</title>
    <!-- Add this script for autocomplete functionality -->
    <script>
        function handleAutocomplete() {
            var searchInput = document.getElementById('search_term');
            var resultsContainer = document.getElementById('event_results');

            if (searchInput.value.length >= 1) {
                var xhr = new XMLHttpRequest();
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4) {
                        console.log('Response:', xhr.responseText);
                        if (xhr.status === 200) {
                            resultsContainer.innerHTML = xhr.responseText;
                        } else {
                            console.error('Error:', xhr.statusText);
                        }
                    }
                };
                xhr.open('GET', 'search_suggestions.php?search_term=' + searchInput.value, true);
                xhr.send();
            } else {
                resultsContainer.innerHTML = '';
            }
        }
    </script>
</head>
<body>
    <!-- Filter form -->
    <form method="get" action="">
        <label for="filter_option">Filter Events:</label>
        <select name="filter_option" id="filter_option">
            <option value="your_events" <?php echo ($filterOption === 'your_events') ? 'selected' : ''; ?>>Your Events</option>
            <option value="registered_events" <?php echo ($filterOption === 'registered_events') ? 'selected' : ''; ?>>Registered Events</option>
            <option value="others_events" <?php echo ($filterOption === 'others_events') ? 'selected' : ''; ?>>Other People's Events</option>
            <option value="all_events" <?php echo ($filterOption === 'all_events') ? 'selected' : ''; ?>>All Events</option>
        </select>
        <input type="text" name="search_term" id="search_term" oninput="handleAutocomplete()" placeholder="Search events">
        <input type="submit" value="Search">
    </form>

    <div id="event_results">
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
                <?php
                $buttonText = getButtonText($filterOption, $row['id'], $registeredEvents);
                $formAction = ($filterOption === 'your_events') ? 'edit_event.php?event_id=' . $row['id'] : 'register_event.php';
                ?>
                <form method="post" action="<?php echo $formAction; ?>">
                    <input type="hidden" name="event_id" value="<?php echo $row['id']; ?>">
                    <input type="submit" name="<?php echo ($filterOption === 'your_events') ? 'edit_event' : 'register_event'; ?>" value="<?php echo $buttonText; ?>">
                </form>
            </div>
            <?php
        }
        ?>
    </div>
</body>
</html>
