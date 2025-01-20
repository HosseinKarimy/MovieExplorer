<?php
session_start();

require 'database.php';
require 'helper.php';


$movieId = $_GET['id']; // Get movie ID from URL
$userId = $_SESSION['user_id'] ?? null;
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
                <?php if ($islogin): ?>
                    <div class="follow-button">
                        <button id="followToggle" class="follow-icon" onclick="submitMark(<?php echo htmlspecialchars($movieId); ?>)">
                            <span>⭐</span>
                            <?php echo isMarked($db, $movieId, $userId) ? "نشان شده" : "نشان کردن"; ?>
                        </button>
                    </div>
                <?php endif; ?>


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

<?php
function isMarked($db, $movieId, $userId)
{
    $query = "
        SELECT COUNT(*)
        FROM 
        marks m
        WHERE 
        m.UserId = ? AND m.MovieId = ?
    ";

    $stmt = $db->prepare($query);
    if (!$stmt) {
        throw new Exception("Failed to prepare statement: " . $db->error);
    }

    $stmt->bind_param('ii', $userId, $movieId);
    if (!$stmt->execute()) {
        throw new Exception("Failed to execute statement: " . $stmt->error);
    }

    $result = $stmt->get_result();
    $row = $result->fetch_row();
    $count = $row[0];

    return $count == 1;
}


// Helper method to create a single Artist card
function CreateArtistCard($name, $role, $id, $image)
{
    return '<div class="item-card">
                <a href="' . htmlspecialchars('artist.php?id=' . $id) . '" class="item-card-link">
                    <img src="' . htmlspecialchars('/' . ltrim($image, '/')) . '" alt="' . htmlspecialchars($name) . '">
                    <p class="actor-name">' . htmlspecialchars($name) . '</p>
                    <p class="actor-role">' . htmlspecialchars($role) . '</p>
                </a>
            </div>';
}

// Helper method to create the list of Artist cards
function CreateListOfArtistCard($Artists, $header)
{
    $artistCardsHtml = '';

    // Loop through each artist and generate a artist card
    foreach ($Artists as $artist) {
        $artistCardsHtml .= CreateArtistCard($artist['name'], $artist['role'], $artist['id'], $artist['image']);
    }

    // Return the full section with movie cards
    return '<section class="item-cards-section">
                <h2>' . htmlspecialchars($header) . '</h2>
                <div class="item-card-list">
                    ' . $artistCardsHtml . '
                </div>
            </section>';
}

function fetchArtistsFromDb($db, $orderBy = 'name', $movieId)
{
    $query = "SELECT 
     ar.Id AS id,
     ar.Name AS name,
     a.Role AS role,
     m.URL AS image
    FROM
     artist ar 
    JOIN
     acts a on a.ActorId = ar.Id AND a.MovieId = $movieId
    JOIN 
     media m on m.ArtistId = ar.Id
    GROUP BY
     ar.Id
    ORDER BY
     ar.$orderBy DESC;
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


function getMovieDetails($db, $movieId)
{
    $query = "
        SELECT 
            mo.Name AS title,
            mo.Date AS release_date,
            mo.Duration AS duration,
            mo.Languages AS languages,
            mo.Summary AS summary,
            dir.Name AS director_name,
            wrt.Name AS writer_name
        FROM 
            movies mo
        LEFT JOIN 
            artist dir ON mo.DirectorId = dir.Id
        LEFT JOIN 
            artist wrt ON mo.WriterId = wrt.Id
        WHERE 
            mo.Id = ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $movieId);
    $stmt->execute();
    $result = $stmt->get_result();
    return $result->fetch_assoc();
}


function getMovieGenres($db, $movieId)
{
    // Query to fetch movie genres
    $query = "
        SELECT 
            g.Name AS genre_name
        FROM 
            genremovie gm
        INNER JOIN 
            genres g ON gm.GenreId = g.Id
        WHERE 
            gm.MovieId = ?";

    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $movieId);
    $stmt->execute();
    $result = $stmt->get_result();

    $genres = [];
    while ($row = $result->fetch_assoc()) {
        $genres[] = $row['genre_name'];
    }
    return $genres;
}

function getMoviePosters($db, $movieId)
{
    // Query to fetch movie posters
    $query = "
        SELECT 
            URL 
        FROM 
            media 
        WHERE 
            MovieId = ? AND Type = 'poster'";

    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $movieId);
    $stmt->execute();
    $result = $stmt->get_result();

    $posters = [];
    while ($row = $result->fetch_assoc()) {
        $posters[] = $row['URL'];
    }
    return $posters;
}



function getCommentsByMovieId($db, $movieId, $parentId = null)
{
    $query = "SELECT c.Id, c.UserId, c.MovieId, c.Comment, (select count(*) from commentslikes cl where cl.commentid = c.id) As LikeCount, c.ParrentId, u.Username , c.CreatedAt
              FROM comments c
              INNER JOIN users u ON c.UserId = u.Id
              WHERE c.MovieId = ? AND c.ParrentId " . ($parentId ? "= ?" : "IS NULL") . "
              ORDER BY c.Id ASC";

    $stmt = $db->prepare($query);
    if ($parentId) {
        $stmt->bind_param('ii', $movieId, $parentId);
    } else {
        $stmt->bind_param('i', $movieId);
    }
    $stmt->execute();
    $result = $stmt->get_result();

    // تبدیل نتیجه به آرایه
    $comments = [];
    while ($row = $result->fetch_assoc()) {
        $comments[] = $row;
    }

    // آزادسازی منابع
    $stmt->close();
    return $comments;
}

function displayComments($db, $movieId, $parentId = null)
{
    $comments = getCommentsByMovieId($db, $movieId, $parentId); // دریافت کامنت‌ها از پایگاه داده

    if (empty($comments)) {
        return;
    }
    foreach ($comments as $comment) {
        if (!isset($parentId))
            echo '<div class="review">';
        if (isset($parentId))
            echo '<div class="review-reply">';
        // بخش اصلی کامنت
        echo '<div class="review-detail">';
        echo '<div class="name">' . htmlspecialchars($comment['Username']) . ' - ' . date('d F', strtotime($comment['CreatedAt'])) . ':</div>';
        echo '<div class="like-count">';
        echo '<span for="like-count">' . $comment['LikeCount'] . '</span>';
        echo '<button for="like-count" onclick="likeComment(' . $comment['Id'] . ')">❤️</button>';
        if (!isset($parentId))
            echo '<button class="reply-button" onclick="showReplyForm(' . $comment['Id'] . ',' . $movieId . ')">پاسخ</button>';
        echo '</div>';
        echo '</div>';
        echo '<div class="text">' . htmlspecialchars($comment['Comment']) . '</div>';
        echo '<div id="reply-' . $comment['Id'] . '"></div>'; // فرم پاسخ به اینجا اضافه خواهد شد
        if (isset($parentId))
            echo '</div>';

        // کامنت‌های مرتبط (پاسخ‌ها)
        displayComments($db, $movieId, $comment['Id']); // بازگشتی برای نمایش کامنت‌های فرزند

        if (!isset($parentId))
            echo '</div>'; // پایان کامنت
    }
}


?>