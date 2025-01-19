<?php session_start();
require 'helper.php';
require 'database.php';

$islogin = isset($_SESSION['user_id']);
if($islogin)
{
    $userId = $_SESSION['user_id'];
}
else
{
    header("Location: /index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Explorer</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/index.css">
</head>

<body>
    <!-- navbar -->
    <?php require 'navbar.php'
    ?>

    <!-- Main Content -->
    <main>
        <!-- Related Actors and Movies Section -->
        <section class="item-cards-section">
            <!-- related actors Section -->
            <?php
            $artist = fetchFollowedArtistsFromDb($db , $userId);
            echo CreateListOfArtistCard($artist, 'بازیگران مرتبط');
            ?>

            <!-- Related Movies Section -->
            <?php
            $movies = fetchMarkedMoviesFromDb($db , $userId);
            // $movies = fetchRelatedMovies($db);
            echo CreateListOfMovieCard($movies, 'فیلم های مرتبط');
            ?>
        </section>

        <script src="assets/js/index.js"></script>
        <script src="assets/js/theme.js"></script>
    </main>
</body>

</html>

<?php

function fetchMarkedMoviesFromDb($db , $userId)
{
    $query = "SELECT 
    mo.Id AS id, 
    mo.Name AS title, 
    me.URL AS image
   FROM 
    movies mo
   LEFT JOIN 
    media me ON me.MovieId = mo.Id AND me.Type = 'poster'
   LEFT JOIN 
   	marks ma ON mo.id = ma.MovieId
    where ma.UserId = $userId    
   GROUP BY 
    mo.Id
   ";

   $stmt = $db->prepare($query);
   $stmt->execute();
   $result = $stmt->get_result();

   $movies = [];
   while ($row = $result->fetch_assoc()) {
       $movies[] = $row;
       $filePath = $row['image'];
       // استخراج قسمت آخر URL
       $text = basename($filePath);
       // بررسی وجود فایل و تولید تصویر در صورت نیاز
       checkAndGenerateImage($text, $filePath);
   
   }

   $stmt->close();

   return $movies;
}

function fetchFollowedArtistsFromDb($db , $userId)
{
    $query = "SELECT 
     ar.Id AS id,
     ar.Name AS name,
     m.URL AS image
     FROM
     artist ar 
     JOIN 
     media m on m.ArtistId = ar.Id
     LEFT JOIN 
   	 follows fo ON ar.id = fo.ArtistId
     where fo.UserId = $userId 
     GROUP BY
     ar.Id
    ";

    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $movies = [];
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row;
        $filePath = $row['image'];
        // استخراج قسمت آخر URL
        $text = basename($filePath);
        // بررسی وجود فایل و تولید تصویر در صورت نیاز
        checkAndGenerateImage($text, $filePath);
    }

    $stmt->close();

    return $movies;
}

?>