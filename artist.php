<?php 
require 'database.php';
require 'helper.php';

// Fetch movie details
$artistId = $_GET['id']; // Get movie ID from URL
$media = fetchMediaFromDb($db , 'artistid' , $artistId);
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
        

        <!-- Gallery Section -->
        <?php echo createGallerySection($media); ?>
     


    </main>

    <script src="/assets/js/movie.js"></script>
    <script src="/assets/js/theme.js"></script>
</body>

</html>
