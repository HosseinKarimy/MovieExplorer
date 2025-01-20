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
        $filePath = $row['image'];
        // استخراج قسمت آخر URL
        $text = basename($filePath);
        // بررسی وجود فایل و تولید تصویر در صورت نیاز
        checkAndGenerateImage($text, $filePath);
    
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
