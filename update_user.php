<?php
require 'database.php';
session_start();

// Get user information
$id = $_POST['id'];
$username = $_POST['username'];
$name = $_POST['name'];
$email = $_POST['email'];
$password = !empty($_POST['password']) ? password_hash($_POST['password'], PASSWORD_BCRYPT) : null;
$genres = isset($_POST['genres']) ? $_POST['genres'] : [];

// Handle profile image upload (optional)
$profileImage = null;
if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] === UPLOAD_ERR_OK) {
    $uploadDir = 'uploads/profile_images/';
    $fileName = basename($_FILES['profile_image']['name']);
    $targetFile = $uploadDir . uniqid() . '_' . $fileName;
    if (move_uploaded_file($_FILES['profile_image']['tmp_name'], $targetFile)) {
        $profileImage = $targetFile;
    }
}

// Update user info
$query = "UPDATE users SET Username = ?, Name = ?, Email = ?";
$params = [$username, $name, $email];

if ($password) {
    $query .= ", Password = ?";
    $params[] = $password;
}
if ($profileImage) {
    $query .= ", profile_image = ?";
    $params[] = $profileImage;
}
$query .= " WHERE Id = ?";
$params[] = $id;

$stmt = $db->prepare($query);
$stmt->bind_param(str_repeat('s', count($params) - 1) . 'i', ...$params);
$stmt->execute();
$stmt->close();

// Update user genres
$query = "DELETE FROM usergenre WHERE UserId = ?";
$stmt = $db->prepare($query);
$stmt->bind_param('i', $id);
$stmt->execute();
$stmt->close();

if (!empty($genres)) {
    $query = "INSERT INTO usergenre (UserId, GenreId) VALUES (?, ?)";
    $stmt = $db->prepare($query);
    foreach ($genres as $genreId) {
        $stmt->bind_param('ii', $id, $genreId);
        $stmt->execute();
    }
    $stmt->close();
}

$_SESSION['username'] = $username;
$_SESSION['profile_image'] = $profileImage;


// Redirect
header('Location: /user.php');
exit;
