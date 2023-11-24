<?php
// Check if a session is not already started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Check if the user is logged in
if (!isset($_SESSION['username'])) {
    header("Location: login_twig_cookies.php");
    exit();
}

// Include the database connection
include('db.php');

// Get the username of the logged-in user
$username = $_SESSION['username'];

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['upload_picture'])) {

    // Define the target directory for profile pictures
    $target_dir = "profile_pictures/";

    // Define the target file path
    $target_file = $target_dir . basename($_FILES["profile_picture"]["name"]);

    // Check if the file is an image
    $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
    $allowedExtensions = array("jpg", "jpeg", "png", "gif");

    if (in_array($imageFileType, $allowedExtensions)) {
        // Move the uploaded file to the target directory
        if (move_uploaded_file($_FILES["profile_picture"]["tmp_name"], $target_file)) {
            // Update the user's profile picture in the database
            $updateSql = "UPDATE users SET profile_picture = '$target_file' WHERE username = '$username'";
            if ($conn->query($updateSql) === TRUE) {
            } else {
                echo "Error updating profile picture: " . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    } else {
        echo "Invalid file type. Allowed types: jpg, jpeg, png, gif.";
    }
}

// Close the database connection
$conn->close();
?>
