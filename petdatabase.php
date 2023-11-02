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
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
    exit();
} else {
    echo "Connected to the database successfully.";
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

    // Display the data in an HTML table
    echo "<table border='1'>";
    echo "<tr><th>Name</th><th>Species</th><th>Age</th></tr>";
    
    while ($row = $result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['name'] . "</td>";
        echo "<td>" . $row['species'] . "</td>";
        echo "<td>" . $row['age'] . "</td>";
        echo "</tr>";
    }
    
    echo "</table>";
} else {
    echo "No records found.";
}

// Close the database connection
$mysqli->close();

?>
