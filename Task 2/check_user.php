<?php
include('db.php'); // Include your database connection

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = mysqli_real_escape_string($conn, $_POST['username']);
    $password = mysqli_real_escape_string($conn, $_POST['password']);

    $sql = "SELECT * FROM users WHERE username = '$username'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        if (password_verify($password, $row['password'])) {
            // If login is successful, return 'success'
            echo 'success';
        } else {
            // If password is incorrect, return 'failure'
            echo 'failure';
        }
    } else {
        // If username is not found, return 'failure'
        echo 'failure';
    }
}

$conn->close();
?>
