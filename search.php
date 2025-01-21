<?php
require 'database.php';

// Get the search query
$value = isset($_GET['q']) ? trim($_GET['q']) : '';

if ($value === '') {
    echo json_encode([]);
    exit;
}

$query = "SELECT id, name 
    FROM (
        SELECT id, name FROM movies 
        UNION 
        SELECT id, name FROM artist
    ) AS combined 
    WHERE name LIKE '%$value%'
    LIMIT 10
";

$stmt = $db->prepare($query);
$stmt->execute();
$result = $stmt->get_result();

$movies = [];
while ($row = $result->fetch_assoc()) {
   $movies[] = $row;
}

$stmt->close();

echo json_encode($movies);
?>
