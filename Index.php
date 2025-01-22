<?php session_start();
require 'helper.php';
require 'database.php';
$islogin = isset($_SESSION['user_id']);
$userId = $_SESSION['user_id'] ?? null;
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
        <!-- Search Section -->
        <section class="search-section">
            <h1>پلتفرم جامع جستجوی فیلم</h1>
            <div class="search-box">
                <input
                    type="text"
                    placeholder="جستجو"
                    class="search-bar"
                    id="search-bar"
                    oninput="search(this.value)">
                <ul id="autocomplete-results" class="dropdown"></ul>
            </div>
        </section>

        <!-- Recommended Movies Section -->
        <?php
        if ($islogin) {
            $movies = fetchRecommendedMovies($db, $userId);
            echo CreateListOfMovieCard($movies, 'پیشنهادات');
        }
        ?>
        <!-- Newest Movies Section -->
        <?php
        $movies = fetchMoviesFromDb($db);
        echo CreateListOfMovieCard($movies, 'جدیدترین‌ها');
        ?>

        <!-- Most Viewed Movies Section -->
        <?php
        $movies = fetchMoviesFromDb($db, 'ViewCount');
        echo CreateListOfMovieCard($movies, 'پربازدیدترین ها');
        ?>

        <section class="faq-container">
            <h2>سوالات متداول</h2>
            <div class="faq-item">
                <button class="faq-question">امکانات وبسایت شما چیست؟</button>
                <div class="faq-answer">
                    <p>وبسایت ما امکاناتی مانند جستجوی پیشرفته فیلم، نمایش پربازدیدترین فیلم‌ها، و امکان ثبت نظرات کاربران را ارائه می‌دهد.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">چگونه از امکانات وبسایت استفاده کنیم؟</button>
                <div class="faq-answer">
                    <p>برای استفاده از امکانات وبسایت، کافی است ثبت‌نام کنید و سپس فیلم‌های موردعلاقه خود را جستجو یا مشاهده کنید.</p>
                </div>
            </div>
            <div class="faq-item">
                <button class="faq-question">چه تفاوتی با سایر وبسایت‌ها دارید؟</button>
                <div class="faq-answer">
                    <p>وبسایت ما با ارائه اطلاعات جامع، رابط کاربری آسان و طراحی زیبا، تجربه‌ای منحصربه‌فرد به کاربران ارائه می‌دهد.</p>
                </div>
            </div>
        </section>
        <script src="assets/js/index.js"></script>
        <script src="assets/js/theme.js"></script>
    </main>
</body>

</html>

<?php
function fetchRecommendedMovies($db, $userid)
{

    $query = "select mo.id As id, mo.Name As title , me.URL as image ,mo.ViewCount FROM 
    movies mo JOIN genremovie gm on gm.MovieId = mo.id join usergenre ug on ug.GenreId = gm.GenreId JOIN media me on me.MovieId=mo.id
    where ug.UserId = $userid
    GROUP by mo.id
    ORDER by mo.ViewCount DESC";


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