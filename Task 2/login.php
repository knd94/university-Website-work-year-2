<?php
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Debugging: Print hashed password from the database
        echo "Stored hashed password: " . $row['password'] . "<br>";

        // Check if the stored password needs rehashing
        if (password_needs_rehash($row['password'], PASSWORD_DEFAULT)) {
            // Rehash the password and update it in the database
            $new_hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE users SET password = '$new_hashed_password' WHERE username = '$username'";
            $conn->query($update_sql);

            echo "Password has been rehashed and updated.<br>";
        }

        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header("Location: dashboard.php"); // Redirect to the dashboard after successful login
            exit; // Make sure to exit after redirect
        } else {
            echo "Entered password: " . $password . "<br>";
            echo "Hashed password: " . password_hash($password, PASSWORD_DEFAULT) . "<br>";
            echo "Incorrect password";
        }
    } else {
        echo "User not found";
    }
}

$conn->close();
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
