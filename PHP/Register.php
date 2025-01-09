<?php
session_start(); // Start the session
require_once 'database.php'; // Include database connection

/**
 * Add a user to the database.
 * @param string $username
 * @param string $name
 * @param string $email
 * @param string $hashedPassword
 * @return int|false The user ID if successful, false otherwise.
 */
function addUserToDb($username, $name, $email, $hashedPassword, $profileImage) {
    global $db;

    $query = "INSERT INTO users (username, name, email, password, profile_image) VALUES (?, ?, ?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("sssss", $username, $name, $email, $hashedPassword, $profileImage);

    if ($stmt->execute()) {
        $userId = $stmt->insert_id; // Get the auto-increment ID of the newly inserted user
        $stmt->close();
        return $userId;
    } else {
        $stmt->close();
        return false;
    }
}


/**
 * Add genres associated with a user to the user_genre table.
 * @param int $userId
 * @param array $genres
 */
function addUserGenres($userId, $genres) {
    global $db;

    $query = "INSERT INTO usergenre (userid, genreid) VALUES (?, ?)";
    $stmt = $db->prepare($query);

    foreach ($genres as $genreId) {
        echo $genreId;
        $stmt->bind_param("ii", $userId, $genreId);
        $stmt->execute();
    }

    $stmt->close();
}

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $username = $_POST['username'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $genres = isset($_POST['genres']) ? $_POST['genres'] : []; // Array of genre IDs

    // Hash the password for security
    $hashedPassword = password_hash($password, PASSWORD_BCRYPT);


    // Handle profile image upload
    $profileImage = null;
    if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'uploads/profile_images/'; // Directory to store images
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0777, true); // Create the directory if it doesn't exist
        }

        $fileTmpPath = $_FILES['profile_image']['tmp_name'];
        $fileName = basename($_FILES['profile_image']['name']);
        $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
        $newFileName = uniqid('profile_', true) . '.' . $fileExtension;

        $destPath = $uploadDir . $newFileName;

        if (move_uploaded_file($fileTmpPath, $destPath)) {
            $profileImage = $destPath; // Store the file path in the database
        } else {
            echo "Error: Failed to upload the profile image.";
            exit;
        }
    }

    // Add the user to the database
    $userId = addUserToDb($username, $name, $email, $hashedPassword, $profileImage);

    if ($userId) {
        // Insert selected genres into the user_genre table
        if (!empty($genres)) {
            addUserGenres($userId, $genres);
        }

        // Create a session and store the user ID
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['profile_image'] = $profileImage;

        // Redirect to the index page
        header("Location: /index.php");
        exit;
    } else {
        echo "Error: Could not register the user.";
    }
}
?>
