<?php
require_once 'vendor/autoload.php'; // Autoload the Composer dependencies

// Create a Twig loader and environment
$loader = new \Twig\Loader\ArrayLoader([
    'registration' => '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Registration</title>
        </head>
        <body>
            <h2>Register</h2>
            <form method="post" action="registration_twig.php">
                Username: <input type="text" name="username" required><br>
                Password: <input type="password" name="password" required><br>
                <input type="submit" value="Register">
            </form>
            <a href="login_twig.php"> Already a user? Login! </a>
        </body>
        </html>
    ',
]);

$twig = new \Twig\Environment($loader);

// Start the session
session_start();
include('db.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);

    $sql = "INSERT INTO users (username, password, hashed_password) VALUES ('$username', '$password', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        echo "Registration successful!";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}

$conn->close();

// Render the Twig template
echo $twig->render('registration');
?>