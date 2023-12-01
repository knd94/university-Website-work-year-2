<?php
session_start();
include('db.php');
include('db_functions.php');

// Fetch user profile picture, etc.
$profilePicture = isset($_SESSION['username']) ? getUserProfilePicture($conn, $_SESSION['username']) : null;

// Fetch and display events if the function is defined
$events = function_exists('getEvents') ? getEvents($conn) : [];

// Close the database connection
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Event Management System</title>
    <style>
        .dropdown {
            position: relative;
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f1f1f1;
            min-width: 160px;
            box-shadow: 0 8px 16px 0 rgba(0,0,0,0.2);
            z-index: 1;
        }

        .dropdown:hover .dropdown-content {
            display: block;
        }

        .profile-pic {
            width: 50px;
            height: 50px;
            border-radius: 50%;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <header>
        <!-- Profile Picture -->
        <?php
        if (!empty($profilePicture) && isset($_SESSION['username'])) {
            echo '<div class="dropdown">';
            echo '<img class="profile-pic" src="' . $profilePicture . '" alt="Profile Picture" onclick="toggleDropdown()" />';
            echo '<div class="dropdown-content">';
            echo '<a href="dashboard.php">Dashboard</a>';
            echo '<a href="events.php">Events</a>';
            echo '<a href="logout.php">Logout</a>';
            echo '</div>';
            echo '</div>';
        } else {
            // If no profile picture or not logged in, display the menu button
            echo '<div class="dropdown">';
            echo '<button class="profile-pic dropbtn" onclick="toggleDropdown()">Menu</button>';
            echo '<div class="dropdown-content">';
            if (isset($_SESSION['username'])) {
                // If logged in, show these options
                echo '<a href="dashboard.php">Dashboard </a>';
                echo '<a href="events.php">Events </a>';
                echo '<a href="logout.php">Logout</a>';
            } else {
                // If not logged in, show login and events link
                echo '<a href="login_twig_cookies.php">Login </a>';
                echo '<a href="events.php">Check out the events!</a>';
            }
            echo '</div>';
            echo '</div>';
        }
        ?>
    </header>

    <main>
        <!-- Home Page Content -->
        <h2><?php echo isset($message) ? $message : (isset($_SESSION['username']) ? 'Welcome, ' . $_SESSION['username'] : 'Welcome!'); ?></h2>

        <!-- Display Events -->
        <?php
        foreach ($events as $event) {
            echo '<div>';
            echo '<h3>' . $event['title'] . '</h3>';
            echo '<p>' . $event['description'] . '</p>';
            echo '<p>Date: ' . $event['event_date'] . '</p>';
            echo '<img src="' . $event['image_path'] . '" alt="Event Image" style="width: 100px; height: 100px; border-radius: 50%;">';

            // Check if the user is the creator of the event to allow deletion
            if (isset($_SESSION['user_id']) && $event['user_id'] == $_SESSION['user_id']) {
                echo '<form method="post" action=""><input type="hidden" name="event_id" value="' . $event['id'] . '"><input type="submit" name="delete_event" value="Delete Event"></form>';
            }

            echo '</div>';
        }
        ?>
    </main>

    <script>
        function toggleDropdown() {
            var dropdownMenu = document.querySelector(".dropdown-content");
            dropdownMenu.classList.toggle("show");
        }

        window.onclick = function(event) {
            if (!event.target.matches('.profile-pic')) {
                var dropdowns = document.getElementsByClassName("dropdown-content");
                var i;
                for (i = 0; i < dropdowns.length; i++) {
                    var openDropdown = dropdowns[i];
                    if (openDropdown.classList.contains('show')) {
                        openDropdown.classList.remove('show');
                    }
                }
            }
        }
    </script>
</body>
</html>
