<?php
$today = date("l");
echo "Current day of the week: $today";

if ($today == "Monday" || $today == "Wednesday" || $today == "Thursday") { 
    // Go to University 
    echo "<p>Go to university.</p>";
} else { 
    // Stay in bed
    echo "<p>Stay in bed</p>";
}
?>
