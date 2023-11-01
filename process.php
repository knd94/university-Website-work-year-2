<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if ($_FILES["fileToUpload"]["error"] == 0) {
        $file = $_FILES["fileToUpload"]["tmp_name"];
        $names = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        if ($names === false) {
            echo "Error reading the file.";
        } else {
            sort($names); // Sort the array alphabetically
            echo "<h2>Sorted Names:</h2>";
            echo "<ul>";
            foreach ($names as $name) {
                echo "<li>$name</li>";
            }
            echo "</ul>";
        }
    } else {
        echo "Error uploading the file.";
    }
}
?>
