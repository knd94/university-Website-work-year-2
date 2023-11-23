<?php
require_once 'vendor/autoload.php'; // Autoload the Composer dependencies

// Create a Twig loader and environment
$loader = new \Twig\Loader\ArrayLoader([
    'login' => '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Login</title>
        </head>
        <body>
            <h2>Login</h2>
            <form method="post" action="login_twig.php">
                Username: <input type="text" name="username" required><br>
                Password: <input type="password" name="password" required><br>
                <input type="submit" value="Login">
            </form>
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

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_needs_rehash($row['password'], PASSWORD_DEFAULT)) {
            $new_hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $update_sql = "UPDATE users SET password = '$new_hashed_password' WHERE username = '$username'";
            $conn->query($update_sql);
            echo "Password has been rehashed and updated." . "<br>";
        }

        if (password_verify($password, $row['password'])) {
            $_SESSION['username'] = $username;
            header("Location: dashboard.php");
            exit;
        } else {
            echo "Entered password: " . $password . "<br>";
            echo "Incorrect password. Please try again.";
        }
    } else {
        echo "User not found";
    }
}

$conn->close();

// Render the Twig template
echo $twig->render('login');
?>