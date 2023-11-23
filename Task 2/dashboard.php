<?php
// Start the session
session_start();
include('db.php');

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login_twig_cookies.php");
    exit();
}

// Check if the delete form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_user'])) {
    // Include the delete_user.php script
    include('delete_user.php');
}

// Render the HTML using a template engine (e.g., Twig)
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

    <!-- Display user information -->
    <!-- Display a list of upcoming events -->

    <!-- Delete User Form -->
    <form method="post" action="">
        <input type="submit" name="delete_user" value="Delete My Account">
    </form>

    <!-- Add more content and features as needed -->

    <a href="logout.php">Logout</a>

    <!-- Include any additional scripts if needed -->
</body>
</html>
