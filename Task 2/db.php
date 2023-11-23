<?php
$hostname = "localhost";
$username = "2110491";
$password = "HulkHulk1987*";
$database = "db2110491";

$conn = new mysqli($hostname, $username, $password, $database);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
