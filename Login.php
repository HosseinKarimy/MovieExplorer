<?php
session_start(); // Start the session
require_once 'database.php'; // Include database connection

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Logic to get data from the database and check for password
    $query = "SELECT id, username, password, profile_image FROM users WHERE username = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        $userId = $user['id'];
        $hashedPasswordFromDb = $user['password'];
        $profileImage = $user['profile_image'];

        // Verify the password
        if (password_verify($password, $hashedPasswordFromDb)) {
            // Create a session and store user information
            $_SESSION['user_id'] = $userId;
            $_SESSION['username'] = $username;
            $_SESSION['profile_image'] = $profileImage;

            // Redirect to the index page
            header("Location: /index.php");
            exit;
        } else {
            echo "Error: Incorrect password.";
        }
    } else {
        echo "Error: User not found.";
    }

    $stmt->close();
}
?>
