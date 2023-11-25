<?php

function registerUser($username, $password, $conn) {
    $hashed_password = password_hash($password, PASSWORD_DEFAULT);
    $sql = "INSERT INTO users (username, password, hashed_password) VALUES ('$username', '$password', '$hashed_password')";

    if ($conn->query($sql) === TRUE) {
        $userId = $conn->insert_id;

        // Set user_id in the session after successful registration
        $_SESSION['user_id'] = $userId;

        return $userId; // Return the user ID after successful registration
    } else {
        return false; // Return false if registration fails
    }
}
?>
