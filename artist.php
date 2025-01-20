<?php 
session_start();
require 'database.php';
require 'helper.php';

// Fetch movie details
$artistId = $_GET['id']; // Get movie ID from URL
$userId = $_SESSION['user_id'] ?? null;
$media = fetchMediaFromDb($db , 'artistid' , $artistId);
$artist = fetchArtistByIdFromDb($db , $artistId)
?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Explorer - Movie Page</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/movie.css">
    <link rel="stylesheet" href="/assets/css/gallery.css">
</head>

<body>
    <!-- navbar -->
    <?php require 'navbar.php' ?>

    <!-- Main Content -->
    <main>

    <section class="details">
        <div class="details-information">
            <h2><?php echo htmlspecialchars($artist['Name']); ?></h2>
            <?php if ($islogin): ?>
                    <div class="follow-button">
                        <button id="followToggle" class="follow-icon" onclick="submitFollow(<?php echo htmlspecialchars($artistId); ?>)">
                            <span>⭐</span>
                            <?php echo isFollowed($db, $artistId, $userId) ? "دنبال شده" : "دنبال کردن"; ?>
                        </button>
                    </div>
                <?php endif; ?>
            <p><strong>تاریخ تولد:</strong> <?php echo htmlspecialchars($artist['Birthdate']); ?></p>
            <p><strong>ملیت:</strong> <?php echo htmlspecialchars($artist['Nationality']); ?></p>
            <p><strong>بیوگرافی:</strong> <?php echo nl2br(htmlspecialchars($artist['Bio'])); ?></p>
            <p><strong>جزئیات:</strong> <?php echo htmlspecialchars($artist['Details']); ?></p>
        </div>
        <div class="details-image">
            <img src="assets/images/<?php echo htmlspecialchars($artist['Id']); ?>.jpg" alt="<?php echo htmlspecialchars($artist['Name']); ?> Image">
        </div>
    </section>

        <!-- Gallery Section -->
        <?php echo createGallerySection($media); ?>

        <?php
            $movies = fetchRelatedMoviesForArtist($db , $artistId);
            echo CreateListOfMovieCard($movies, 'فیلم های مرتبط');
        ?>
     


    </main>

    <script src="/assets/js/movie.js"></script>
    <script src="/assets/js/theme.js"></script>
    <script src="/assets/js/artist.js"></script>
</body>

</html>


<?php

function fetchRelatedMoviesForArtist($db , $artistId)
{
$query = "select mo.Id AS id, 
     mo.Name AS title, 
     me.URL AS image
    FROM movies mo
    JOIN acts a on a.MovieId = mo.id 
    Left JOIN media me ON me.MovieId = mo.Id AND me.Type = 'poster'
    where a.ActorId = $artistId
    GROUP by mo.id
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

function isFollowed($db, $artistId, $userId)
{
    $query = "
        SELECT COUNT(*)
        FROM 
        follows m
        WHERE 
        m.UserId = ? AND m.ArtistId = ?
    ";

    $stmt = $db->prepare($query);
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $db->error);
    }

    $stmt->bind_param('ii', $userId, $artistId);
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute statement: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $row = $result->fetch_row();
    $count = $row[0];

    return $count == 1;
}

?>
