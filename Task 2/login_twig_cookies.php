<?php
// Start the session
session_start();

// Check if the user is already logged in
if (isset($_SESSION['username'])) {
    header("Location: index.php");
    exit;
}

require_once 'vendor/autoload.php'; // Autoload the Composer dependencies
include('db.php');

$loader = new \Twig\Loader\ArrayLoader([
    'login' => '
        <!DOCTYPE html>
        <html>
        <head>
            <title>Login</title>
        </head>
        <body>
            <h2>Login</h2>
            <form method="post" action="login_twig_cookies.php">
                Username: <input type="text" name="username" required><br>
                Password: <input type="password" name="password" required><br>
                <label for="rememberMe">Remember me</label>
                <input type="checkbox" name="rememberMe" id="rememberMe"><br>
                <input type="submit" value="Login">
            </form>
            <a href="registration_twig_cookies.php"> Not a user? Register! </a>
        </body>
        </html>
    ',
]);

$twig = new \Twig\Environment($loader);

// Handle login logic
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result) {
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();

            // Check if the entered password matches the hashed password stored in the database for the given username
            if (password_verify($password, $row['hashed_password'])) {
                $_SESSION['username'] = $username;

                // Check if "Remember me" is checked
                if (isset($_POST['rememberMe']) && $_POST['rememberMe'] == 'on') {
                    // Set a cookie to remember the user for 30 days
                    setcookie('rememberedUser', $username, time() + 30 * 24 * 60 * 60);
                }

                header("Location: index.php");
                exit;
            } else {
                echo "Entered password: " . $password . "<br>";
                echo "Hashed password from the database: " . $row['hashed_password'] . "<br>";
                echo "Incorrect password. Please try again.";
            }
        } else {
            echo "User not found";
        }
    } else {
        echo "Error in query: " . $conn->error;
    }
}

$conn->close();

// Render the Twig template
echo $twig->render('login');
?>
