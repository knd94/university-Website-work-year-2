<?php
// Database connection settings
$hostname = "";
$username = "";
$password = "";
$database = "";

// Create a new MySQLi connection
$mysqli = new mysqli($hostname, $username, $password, $database);

if ($mysqli -> connect_errno) {
  echo "Failed to connect to MySQL: " . $mysqli -> connect_error;
  exit();
}

// Build custom SQL query
$sql = "SELECT name, species, age FROM pet";

// Add search criteria, if provided
if($_POST['searchName'] != "")
  $sql.= " WHERE name LIKE '%" . $_POST['searchName'] . "%'";
  
// Run SQL query
$res = mysqli_query($mysqli, $sql);

// How many rows were returned?
$num_pets = mysqli_num_rows($res);

if($num_pets == 0)
  print("<p>No pet with that name, sorry...</p>");
else {
  print("<p>We found $num_pets pet(s) matching thay name...</p>");
  
  // Loop through resultset and display each field's value
  while($row = mysqli_fetch_assoc($res)) {
    echo $row['name']. " - ". $row['species'] ."<br>";
  }
}

?>