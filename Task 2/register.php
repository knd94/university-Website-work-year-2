<?php
include('db.php');

// Sample registration code
$username = "example_user";
$password = "example_password";
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

$sql = "INSERT INTO users (username, password, hashed_password) VALUES ('$username', '$password', '$hashed_password')";

if ($conn->query($sql) === TRUE) {
    echo "User registered successfully.";
} else {
    echo "Error registering user: " . $conn->error;
}
?>
