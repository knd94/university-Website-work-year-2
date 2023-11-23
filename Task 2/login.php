<?php
session_start(); // Start the connection between MySQL and the website
include('db.php'); // Get the db.php

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']); // Fetch the username
    $password = mysqli_real_escape_string($conn, $_POST['password']); // Fetch the password

    $sql = "SELECT * FROM users WHERE username = '$username'"; // Selecting all from my database's user and it's selecting all the available usernames.
    $result = $conn->query($sql); // Execute the SQL query and store the result set in $result
	
	// Check if there are rows in the result set
    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc(); // If rows exist, retrieve the first row as an associative array and store it in $row

        // Check if the stored password needs rehashing
        if (password_needs_rehash($row['password'], PASSWORD_DEFAULT)) {
            // Rehash the password and update it in the database
            $new_hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE users SET password = '$new_hashed_password' WHERE username = '$username'";
            $conn->query($update_sql); // Updating the MySQL database 

            echo "Password has been rehashed and updated." . "<br>"; // Printing the words on the website 
        }

		// Verify if the entered password matches the hashed password stored in the database for the given username
        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username; // If the passwords match, set the 'username' key in the session to the entered username
            header("Location: dashboard.php"); // Redirect to the dashboard after successful login
            exit; // Make sure to exit after redirect
        } else {
            echo "Entered password: " . $password . "<br>"; // Printing the words on the website 
            echo "Incorrect password. Please try again."; // Printing the words on the website 
        }
    } else {
        echo "User not found"; // Printing the words on the website 
    }
}

$conn->close(); // Closing connection 

// The only way I can think of making the code work properly. 
?>


<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <h2>Login</h2>
    <form method="post" action="login.php">
        Username: <input type="text" name="username" required><br>
        Password: <input type="password" name="password" required><br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
