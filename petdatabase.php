<?php

// Database connection settings
$hostname = "";
$username = "";
$password = "";
$database = "";

// Create a new MySQLi connection
$mysqli = new mysqli($hostname, $username, $password, $database);

// Check for connection errors
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
    exit();
}   else {
    echo "Connected to the database sucessfully";
}

// Run SQL query
$query = "SELECT name, species, age FROM pet";
$result = $mysqli->query($query);

// Check for query execution errors
if (!$result) {
    die("MySQL error: " . $mysqli->error);
}

// Check if there are any rows returned
if ($result->num_rows > 0) {
    echo "<p>" . $result->num_rows . " record(s) were returned...</p>";

    // Loop through the result set and display each row
    while ($row = $result->fetch_assoc()) {
        echo $row['name'] . " - " . $row['species'] . " - " . $row['age'] . "<br>";
    }
} else {
    echo "No records found.";
}

// Close the database connection
$mysqli->close();

?>
