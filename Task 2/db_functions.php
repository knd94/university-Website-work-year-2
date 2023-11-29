<?php

// Check if the function is not already defined
if (!function_exists('getUserProfilePicture')) {
    function getUserProfilePicture($conn, $username) {
        $profilePicture = ''; // Default profile picture URL

        $sql = "SELECT profile_picture FROM users WHERE username = '$username'";
        $result = $conn->query($sql);

        if ($result) {
            $row = $result->fetch_assoc();
            if ($row) {
                $profilePicture = $row['profile_picture'];
            } else {
                // Debugging information
                echo "No rows found for user $username";
            }
            $result->free();  // Free the result set
        } else {
            // Handle query error
            echo "Error: " . $conn->error;
        }

        return $profilePicture;
    }
}

// Check if the function is not already defined
if (!function_exists('deleteEvent')) {
    function deleteEvent($conn, $event_id) {
        // Use prepared statement to prevent SQL injection
        $deleteSql = "DELETE FROM events WHERE id = ?";

        // Prepare the statement
        $stmt = $conn->prepare($deleteSql);

        // Bind the event ID parameter
        $stmt->bind_param("i", $event_id);

        // Execute the statement
        $stmt->execute();

        // Close the statement
        $stmt->close();
    }
}

// Check if the function is not already defined
if (!function_exists('deleteEventRegistrations')) {
    function deleteEventRegistrations($conn, $event_id) {
        // Use prepared statement to prevent SQL injection
        $deleteSql = "DELETE FROM event_registrations WHERE event_id = ?";

        // Prepare the statement
        $stmt = $conn->prepare($deleteSql);

        // Bind the event ID parameter
        $stmt->bind_param("i", $event_id);

        // Execute the statement
        $stmt->execute();

        // Close the statement
        $stmt->close();
    }
}
 // Check if the function is not already defined
if (!function_exists('getUserEvents')) {
function getUserEvents($conn, $username) {
    // Fetch events for a specific user
    $sql = "SELECT id, title, description, event_date, image_path FROM events WHERE username = '$username' ORDER BY event_date ASC";
    $result = $conn->query($sql);

    if ($result) {
        $events = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();  // Free the result set
        return $events;
    } else {
        // Handle query error
        echo "Error: " . $conn->error;
        return array();
    }
}
}

// Check if the function is not already defined
if (!function_exists('getNotifications')) {
function getNotifications($conn, $user_id) {
    // Fetch notifications for a specific user
    $sql = "SELECT id, message, created_at FROM notifications WHERE user_id = '$user_id' ORDER BY created_at DESC";
    $result = $conn->query($sql);

    if ($result) {
        $notifications = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();  // Free the result set
        return $notifications;
    } else {
        // Handle query error
        echo "Error: " . $conn->error;
        return array();
    }
}
}

// Check if the function is not already defined
if (!function_exists('insertNotification')) {
function insertNotification($conn, $user_id, $message, $event_id = null) {
    // Insert a new notification for the user
    $insertSql = "INSERT INTO notifications (user_id, message, event_id) VALUES (?, ?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($insertSql);

    // Bind the parameters
    $stmt->bind_param("iss", $user_id, $message, $event_id);

    // Execute the statement
    $result = $stmt->execute();

    // Close the statement
    $stmt->close();

    if (!$result) {
        // Handle query error
        echo "Error inserting notification: " . $conn->error;
    } else {
        echo "Notification inserted successfully!";
    }
}
}

// Check if the function is not already defined
if (!function_exists('getEventDetails')) {
function getEventDetails($conn, $event_id) {
    // Fetch details of a specific event
    $sql = "SELECT id, title FROM events WHERE id = '$event_id'";
    $result = $conn->query($sql);

    if ($result) {
        $eventDetails = $result->fetch_assoc();
        $result->free();  // Free the result set
        return $eventDetails;
    } else {
        // Handle query error
        echo "Error: " . $conn->error;
        return null;
    }
}
}

// Check if the function is not already defined
if (!function_exists('getUsersForEvent')) {
function getUsersForEvent($conn, $event_id) {
    // Fetch users associated with a specific event
    $sql = "SELECT user_id FROM event_registrations WHERE event_id = '$event_id'";
    $result = $conn->query($sql);

    if ($result) {
        $users = $result->fetch_all(MYSQLI_ASSOC);
        $result->free();  // Free the result set
        return $users;
    } else {
        // Handle query error
        echo "Error: " . $conn->error;
        return array();
    }
}
}

// Check if the function is not already defined
if (!function_exists('deleteEventAndNotify')) {
    function deleteEventAndNotify($conn, $event_id) {
        // Fetch the event details before deletion
        $eventDetails = getEventDetails($conn, $event_id);

        if ($eventDetails !== null) {
            // Delete event registrations first
            deleteEventRegistrations($conn, $event_id);

            // Use prepared statement to prevent SQL injection
            $deleteSql = "DELETE FROM events WHERE id = ?";

            // Prepare the statement
            $stmt = $conn->prepare($deleteSql);

            // Bind the event ID parameter
            $stmt->bind_param("i", $event_id);

            // Execute the statement
            $stmt->execute();

            // Close the statement
            $stmt->close();

            // Notify users about the event deletion
            $notificationMessage = 'Event "' . $eventDetails['title'] . '" has been canceled.';
            $usersToNotify = getUsersForEvent($conn, $event_id);

            foreach ($usersToNotify as $user) {
                insertNotification($conn, $user['user_id'], $notificationMessage, $event_id);
            }
        } else {
            echo "Error: Event details not found.";
        }
    }
}

// Check if the function is not already defined
if (!function_exists('registerForEvent')) {
function registerForEvent($conn, $event_id, $username) {
    // Use prepared statement to prevent SQL injection
    $insertSql = "INSERT INTO event_registrations (event_id, username) VALUES (?, ?)";

    // Prepare the statement
    $stmt = $conn->prepare($insertSql);

    // Bind the parameters
    $stmt->bind_param("is", $event_id, $username);

    // Execute the statement
    $result = $stmt->execute();

    // Close the statement
    $stmt->close();

    if (!$result) {
        // Handle query error
        echo "Error registering for event: " . $conn->error;
    } else {
        // Notify the event owner about the registration
        $notificationMessage = "User $username has registered for your event.";
        $eventDetails = getEventDetails($conn, $event_id);

        if ($eventDetails !== null) {
            insertNotification($conn, $eventDetails['user_id'], $notificationMessage, $event_id);
        }
    }
}
}

// Check if the function is not already defined
if (!function_exists('unregisterFromEvent')) {
function unregisterFromEvent($conn, $event_id, $username) {
    // Use prepared statement to prevent SQL injection
    $deleteSql = "DELETE FROM event_registrations WHERE event_id = ? AND username = ?";

    // Prepare the statement
    $stmt = $conn->prepare($deleteSql);

    // Bind the parameters
    $stmt->bind_param("is", $event_id, $username);

    // Execute the statement
    $result = $stmt->execute();

    // Close the statement
    $stmt->close();

    if (!$result) {
        // Handle query error
        echo "Error unregistering from event: " . $conn->error;
    } else {
        // Notify the event owner about the cancellation
        $notificationMessage = "User $username has unregistered from your event.";
        $eventDetails = getEventDetails($conn, $event_id);

        if ($eventDetails !== null) {
            insertNotification($conn, $eventDetails['user_id'], $notificationMessage, $event_id);
        }
    }
}
}

// Check if the function is not already defined
if (!function_exists('isUserRegistered')) {
function isUserRegistered($conn, $username, $event_id) {
    $sql = "SELECT id FROM event_registrations WHERE username = '$username' AND event_id = '$event_id'";
    $result = $conn->query($sql);

    if ($result) {
        return $result->num_rows > 0;
    } else {
        // Handle query error
        echo "Error: " . $conn->error;
        return false;
    }
}
}

// Check if the function is not already defined
if (!function_exists('getFriendRequests')) {
function getFriendRequests($conn, $user_id) {
    $sql = "SELECT id, sender_id, status FROM friend_requests WHERE receiver_id = ? AND status = 'pending'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $friendRequests = $result->fetch_all(MYSQLI_ASSOC);
    $stmt->close();
    return $friendRequests;
}
}

// Check if the function is not already defined
if (!function_exists('declineFriendRequest')) {
    function declineFriendRequest($conn, $request_id) {
        // Use prepared statement to prevent SQL injection
        $updateSql = "UPDATE friend_requests SET status = 'declined' WHERE id = ?";
        $stmt = $conn->prepare($updateSql);

        // Assuming 'id' is an integer, bind the parameter
        $stmt->bind_param("i", $request_id);

        // Execute the statement
        $result = $stmt->execute();

        // Close the statement
        $stmt->close();

        // Check for errors
        if (!$result) {
            // Handle query error
            echo "Error declining friend request: " . $conn->error;
        } else {
            echo "Friend request declined successfully!";
        }
    }
}

// Add a new function to create a chat room
if (!function_exists('createChatRoom')) {
    function createChatRoom($conn, $user1_id, $user2_id) {
        $insertSql = "INSERT INTO chat_rooms (user1_id, user2_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("ii", $user1_id, $user2_id);
        $stmt->execute();
        $chatRoomId = $stmt->insert_id;
        $stmt->close();

        return $chatRoomId;
    }
}

// Check if the function is not already defined
if (!function_exists('acceptFriendRequest')) {
    function acceptFriendRequest($conn, $request_id) {
        // Update friend request status to 'accepted'
        $updateSql = "UPDATE friend_requests SET status = 'accepted' WHERE id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $stmt->close();

        // Insert the friendship into the friends table
        insertFriendship($conn, $request_id);
    }
}

// Add a new function to insert friendship into the friends table
if (!function_exists('insertFriendship')) {
    function insertFriendship($conn, $request_id) {
        // Fetch sender_id and receiver_id from the friend_requests table
        $selectSql = "SELECT sender_id, receiver_id FROM friend_requests WHERE id = ?";
        $stmt = $conn->prepare($selectSql);
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $stmt->bind_result($sender_id, $receiver_id);
        $stmt->fetch();
        $stmt->close();

        // Insert friendship into the friends table
        $insertSql = "INSERT INTO friends (user1_id, user2_id) VALUES (?, ?)";
        $stmt = $conn->prepare($insertSql);
        $stmt->bind_param("ii", $sender_id, $receiver_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Add a new function to create a chat room for the accepted friend request
if (!function_exists('createChatRoomForFriendRequest')) {
    function createChatRoomForFriendRequest($conn, $request_id) {
        // Fetch sender_id and receiver_id from the friend_requests table
        $selectSql = "SELECT sender_id, receiver_id FROM friend_requests WHERE id = ?";
        $stmt = $conn->prepare($selectSql);
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $stmt->bind_result($sender_id, $receiver_id);
        $stmt->fetch();
        $stmt->close();

        // Create a chat room for the accepted friend request
        createChatRoom($conn, $sender_id, $receiver_id);
    }
}

// Add a new function to increment chat_room_id
if (!function_exists('incrementChatRoomId')) {
    function incrementChatRoomId($conn, $request_id) {
        // Fetch sender_id and receiver_id from the friend_requests table
        $selectSql = "SELECT sender_id, receiver_id FROM friend_requests WHERE id = ?";
        $stmt = $conn->prepare($selectSql);
        $stmt->bind_param("i", $request_id);
        $stmt->execute();
        $stmt->bind_result($sender_id, $receiver_id);
        $stmt->fetch();
        $stmt->close();

        // Increment chat_room_id for both sender and receiver in the users table
        incrementUserChatRoomId($conn, $sender_id);
        incrementUserChatRoomId($conn, $receiver_id);
    }
}

// Add a new function to increment chat_room_id for a user
if (!function_exists('incrementUserChatRoomId')) {
    function incrementUserChatRoomId($conn, $user_id) {
        // Increment chat_room_id for the specified user in the users table
        $updateSql = "UPDATE users SET chat_room_id = chat_room_id + 1 WHERE id = ?";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $stmt->close();
    }
}

// Check if the function is not already defined
if (!function_exists('notifyUsersAboutFriendRequest')) {
function notifyUsersAboutFriendRequest($conn, $request_id) {
    // Fetch information about the friend request, including sender and receiver
    $requestInfo = getFriendRequestInfo($conn, $request_id);

    if ($requestInfo) {
        $senderUsername = getUsernameById($conn, $requestInfo['sender_id']);
        $receiverUsername = getUsernameById($conn, $requestInfo['receiver_id']);

        // Notify the users about the accepted friend request
        $notificationMessageSender = "Your friend request to $receiverUsername has been accepted.";
        $notificationMessageReceiver = "$senderUsername has accepted your friend request.";

        insertNotification($conn, $requestInfo['sender_id'], $notificationMessageSender);
        insertNotification($conn, $requestInfo['receiver_id'], $notificationMessageReceiver);
    }
}
}

// Check if the function is not already defined
if (!function_exists('getFriendRequestInfo')) {
function getFriendRequestInfo($conn, $request_id) {
    $sql = "SELECT sender_id, receiver_id FROM friend_requests WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $request_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        return $result->fetch_assoc();
    } else {
        return null;
    }
}
}

// Check if the function is not already defined
if (!function_exists('getUserFriends')) {
    function getUserFriends($conn, $userId) {
        $friends = array();

        // Select user friends from the friends table
        $selectSql = "SELECT user2_id FROM friends WHERE user1_id = ? UNION SELECT user1_id FROM friends WHERE user2_id = ?";
        $stmt = $conn->prepare($selectSql);
        $stmt->bind_param("ii", $userId, $userId);
        $stmt->execute();
        $stmt->bind_result($friendId);

        while ($stmt->fetch()) {
            $friends[] = array('friend_id' => $friendId);
        }

        $stmt->close();

        return $friends;
    }
}

// Check if the function is not already defined
if (!function_exists('getChatRoomId')) {
    function getChatRoomId($conn, $user1_id, $user2_id) {
        // Fetch the chat room ID for the given pair of users
        $sql = "SELECT id FROM chat_rooms WHERE (user1_id = ? AND user2_id = ?) OR (user1_id = ? AND user2_id = ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiii", $user1_id, $user2_id, $user2_id, $user1_id);
        $stmt->execute();
        $stmt->bind_result($chatRoomId);
        $stmt->fetch();
        $stmt->close();

        return $chatRoomId;
    }
}

// Check if the function is not already defined
if (!function_exists('getUsernameById')) {
    function getUsernameById($conn, $user_id) {
        // Use prepared statement to prevent SQL injection
        $sql = "SELECT username FROM users WHERE id = ?";
        $stmt = $conn->prepare($sql);

        // Assuming 'id' is an integer, bind the parameter
        $stmt->bind_param("i", $user_id);

        // Execute the statement
        $stmt->execute();

        // Get the result
        $result = $stmt->get_result();

        // Fetch the username
        $username = $result->fetch_assoc()['username'];

        // Close the statement
        $stmt->close();

        return $username;
    }
}

?>
