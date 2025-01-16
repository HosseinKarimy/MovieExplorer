<?php
// Helper method to create a single movie card
function CreateMovieCard($title, $id, $image)
{
    return '<div class="item-card">
                <a href="' . htmlspecialchars('movie.php?id=' . $id) . '" class="item-card-link">
                    <img src="' . htmlspecialchars('/' . ltrim($image, '/')) . '" alt="' . htmlspecialchars($title) . '">
                    <p class="movie-title">' . htmlspecialchars($title) . '</p>
                </a>
            </div>';
}

// Helper method to create the list of movie cards
function CreateListOfMovieCard($movies, $header)
{
    $movieCardsHtml = '';

    // Loop through each movie and generate a movie card
    foreach ($movies as $movie) {
        $movieCardsHtml .= CreateMovieCard($movie['title'], $movie['id'], $movie['image']);
    }

    // Return the full section with movie cards
    return '<section class="item-cards-section">
                <h2>' . htmlspecialchars($header) . '</h2>
                <div class="item-card-list">
                    ' . $movieCardsHtml . '
                </div>
            </section>';
}

function fetchMoviesFromDb($db, $orderBy = 'Date')
{
    $query = "SELECT 
     mo.Id AS id, 
     mo.Name AS title, 
     me.URL AS image
    FROM 
     movies mo
    LEFT JOIN 
     media me ON me.MovieId = mo.Id AND me.Type = 'poster'
    GROUP BY 
     mo.Id
    ORDER BY 
     mo.$orderBy DESC;
    ";

    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();

    $movies = [];
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row;
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
    }

    $stmt->close();

    return $movies;
}


function fetchMediaFromDb($db, $target = 'MovieId', $targetid)
{

    $query = "
    SELECT
     m.URL AS url,
      m.Type AS type
     FROM
      media m 
     WHERE m.$target = $targetid AND m.Type <> 'poster';
    ";

    $stmt = $db->prepare($query);
    $stmt->execute();
    $result = $stmt->get_result();
       
    $media = [];
    while ($row = $result->fetch_assoc()) {
        $media[] = $row;
    }

    foreach ($media as $item) {
        $filePath = $item['url'];

        // استخراج قسمت آخر URL
        $text = basename($filePath);

        // بررسی وجود فایل و تولید تصویر در صورت نیاز
        checkAndGenerateImage($text, $filePath);
    }

    $stmt->close();

    return $media;
}

function createGallerySection($media)
{
    // شروع بخش گالری
    $galleryHtml = '<section class="gallery-section">';

    // بخش نمایش اصلی
    $galleryHtml .= '<div class="gallery-show">';
    if (!empty($media)) {
        // نمایش اولین مدیا به عنوان تصویر یا ویدیو اصلی
        $firstMedia = $media[0];
        $galleryHtml .= generateMediaHtml($firstMedia, 'mainImage', 'Gallery Main Media');
    } else {
        // حالت جایگزین در صورت نبود مدیا
        $galleryHtml .= '<div>No media available</div>';
    }
    $galleryHtml .= '</div>';

    // بخش تصاویر کوچک
    $galleryHtml .= '<div class="gallery-thumbnails">';
    foreach ($media as $mediaItem) {
        $galleryHtml .= generateMediaHtml($mediaItem, '', 'Thumbnail');
    }
    $galleryHtml .= '</div>';

    // اسکریپت مرتبط
    $galleryHtml .= '<script src="assets/js/gallery.js"></script>';

    // پایان بخش گالری
    $galleryHtml .= '</section>';

    return $galleryHtml;
}

function generateMediaHtml($media, $id = '', $alt = '')
{
    $html = '';
    $idAttribute = $id ? ' id="' . htmlspecialchars($id) . '"' : '';
    $filePath = '/' . ltrim($media['url'], '/');

    if ($media['type'] === 'image') {
        $html = '<img' . $idAttribute . ' src="' . htmlspecialchars($filePath) . '" alt="' . htmlspecialchars($alt) . '">';
    } elseif ($media['type'] === 'video') {
        $html = '
        <video' . $idAttribute . ' controls>
            <source src="' . htmlspecialchars($filePath) . '" type="video/mp4">
            Your browser does not support the video tag.
        </video>';
    }

    return $html;
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
         echo '<button class="reply-button" onclick="showReplyForm(' . $comment['Id'] .','. $movieId . ')">پاسخ</button>';
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

function checkAndGenerateImage($text, $filePath)
{
    // بررسی وجود فایل
    if (!file_exists($filePath)) {
        // ساخت پوشه اگر وجود ندارد
        $directory = dirname($filePath);
        if (!is_dir($directory)) {
            mkdir($directory, 0777, true);
        }

        // تولید تصویر
        generateImage($text, $filePath);
        echo "Image generated: $filePath\n"; // پیغام برای دیباگ
    }
}


function generateImage($text, $filePath)
{
    $width = 300;
    $height = 300;
    $image = imagecreatetruecolor($width, $height);

    // تولید رنگ تصادفی برای پس‌زمینه
    $backgroundColor = imagecolorallocate(
        $image,
        rand(100, 255), // قرمز
        rand(100, 255), // سبز
        rand(100, 255)  // آبی
    );
    imagefilledrectangle($image, 0, 0, $width, $height, $backgroundColor);

    // متن
    $textColor = imagecolorallocate($image, 50, 50, 50); // رنگ متن
    $fontSize = 5; // اندازه متن
    $x = ($width / 2) - (strlen($text) * imagefontwidth($fontSize) / 2);
    $y = ($height / 2) - (imagefontheight($fontSize) / 2);
    imagestring($image, $fontSize, $x, $y, $text, $textColor);

    // ذخیره تصویر
    $directory = dirname($filePath);
    if (!is_dir($directory)) {
        mkdir($directory, 0777, true);
    }
    imagejpeg($image, $filePath);
    imagedestroy($image);

    echo "Image generated: $filePath\n"; // پیغام برای دیباگ
}
