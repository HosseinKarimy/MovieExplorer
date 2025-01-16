<?php
// Database connection details
$servername = "localhost";
$username = "hossein";
$password = "1234";
$dbname = "movieexplorer";

// Create database connection
$db = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}
