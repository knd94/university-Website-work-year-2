<?php
// Define a constant for your birth year
define('my_year', 1998); // Replace 1990 with your birth year

// Initialize a variable to keep track of generated years
$generated_year = null;

// Loop until a randomly generated year matches your birth year
while ($generated_year !== my_year) {
    // Generate a random year between 1900 and 1999
    $generated_year = rand(1900, 1999);
    
    // Display the generated year
    echo $generated_year . '<br>';
}

// Display a message when your birth year is generated
echo "This is when you were born!";
?>
