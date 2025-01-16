<?php
session_start();

require 'database.php';
require 'helper.php';

// Fetch movie details
$movieId = $_GET['id']; // Get movie ID from URL
$movieDetails = getMovieDetails($db, $movieId);
$genres = getMovieGenres($db, $movieId);
$posters = getMoviePosters($db, $movieId);
$artist = fetchArtistsFromDb($db, 'name', $movieId);
$media = fetchMediaFromDb($db, 'movieid', $movieId);
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
        <!-- Movie Details Section -->
        <section class="details">
            <div class="details-information">
                <h2><?php echo htmlspecialchars($movieDetails['title']); ?></h2>
                <div class="follow-button">
                    <button id="followToggle" class="follow-icon">
                        <span>⭐</span> نشان کردن
                    </button>
                </div>

                <p><strong>ژانر:</strong> <?php echo htmlspecialchars(implode(', ', $genres)); ?></p>
                <p><strong>تاریخ انتشار:</strong> <?php echo htmlspecialchars($movieDetails['release_date']); ?></p>
                <p><strong>مدت زمان:</strong> <?php echo htmlspecialchars($movieDetails['duration']) . ' دقیقه'; ?></p>
                <p><strong>زبان:</strong> <?php echo htmlspecialchars($movieDetails['languages']); ?></p>
                <p><strong>کارگردان:</strong> <?php echo htmlspecialchars($movieDetails['director_name']); ?></p>
                <p><strong>نویسنده:</strong> <?php echo htmlspecialchars($movieDetails['writer_name']); ?></p>
                <p><strong>خلاصه داستان:</strong> <?php echo htmlspecialchars($movieDetails['summary']); ?></p>
            </div>
            <div class="slideshow-container">
                <?php foreach ($posters as $poster): ?>
                    <div class="slide fade">
                        <img src="<?php echo htmlspecialchars($poster); ?>" alt="<?php echo htmlspecialchars($movieDetails['title']); ?>">
                    </div>
                <?php endforeach; ?>
            </div>
        </section>


        <!-- Gallery Section -->
        <?php echo createGallerySection($media); ?>

        <!-- Related Actors and Movies Section -->
        <section class="item-cards-section">
            <!-- related actors Section -->
            <?php
            echo CreateListOfArtistCard($artist, 'بازیگران مرتبط');
            ?>

            <!-- Related Movies Section -->
            <?php
            $movies = fetchMoviesFromDb($db);
            // $movies = fetchRelatedMovies($db);
            echo CreateListOfMovieCard($movies, 'فیلم های مرتبط');
            ?>
        </section>

        <!-- User Command and Rating Section -->
        <section class="comment-section">
            <?php if (isset($_SESSION['user_id'])): ?>
                <h2>ثبت نظر شما</h2>
                <section>
                    <form id="review-form" class="review-form" onsubmit="submitComment(<?= $movieId ?>); return false;">
                        <div class="form-group">
                            <label for="comment">نظر:</label>
                            <textarea id="comment" name="comment" rows="5" placeholder="نظر خود را بنویسید"></textarea>
                            <small class="error-message"></small>
                        </div>
                        <button type="submit">ارسال نظر</button>
                        <p id="success-message" class="success-message"></p>
                    </form>
                </section>

                <section>
                    <form class="review-form" onsubmit="submitRate(<?= $movieId ?>); return false;">
                        <div class="form-group">
                            <label for="rating">امتیاز:</label>
                            <input type="number" id="rating" name="rating" min="1" max="10" placeholder="امتیاز (1 تا 10)" required>
                        </div>
                        <button type="submit">ثبت امتیاز</button>
                    </form>
                </section>
            <?php else: ?>
                <section>
                    برای ثبت نظر ابتدا باید به سایت <a href="/StaticPages/login.html">وارد شوید</a>
                </section>
            <?php endif; ?>
        </section>

        <section class="review-section">
            <?php
            displayComments($db, $movieId);
            ?>
        </section>
        
    </main>

    <script src="/assets/js/movie.js"></script>
    <script src="/assets/js/theme.js"></script>
</body>

</html>