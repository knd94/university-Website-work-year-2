<?php
include('db.php');

if (isset($_GET['search_term'])) {
    $searchTerm = $_GET['search_term'];
    
    $sql = "SELECT DISTINCT title FROM events WHERE title LIKE '%$searchTerm%' LIMIT 5";
    
    $result = $conn->query($sql);

    if ($result) {
        while ($row = $result->fetch_assoc()) {
            echo '<p>' . $row['title'] . '</p>';
        }
    }
}
?>
