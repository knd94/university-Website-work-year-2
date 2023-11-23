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

// Fetch user profile picture from the database
$profilePicture = getUserProfilePicture($conn, $_SESSION['username']);
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
    <?php
    if (!empty($profilePicture)) {
        echo '<img src="' . $profilePicture . '" alt="Profile Picture" style="width: 100px; height: 100px; border-radius: 50%;">';
    }
    ?>
    
    <!-- Display a list of upcoming events -->

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

    <!-- Include any additional scripts if needed -->
</body>
</html>
