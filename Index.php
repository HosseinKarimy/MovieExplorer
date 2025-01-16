<?php session_start();
require 'helper.php';
require 'database.php';
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
                <input type="text" placeholder="جستجو" class="search-bar">
                <button type="submit">جستجو</button>
            </div>
        </section>

        <!-- Recommended Movies Section -->

        <!-- Newest Movies Section -->
        <?php         
        $movies = fetchMoviesFromDb($db);
        echo CreateListOfMovieCard($movies,'جدیدترین‌ها');
        ?>

        <!-- Most Viewed Movies Section -->
        <?php         
        $movies = fetchMoviesFromDb($db , 'ViewCount ');
        echo CreateListOfMovieCard($movies,'پربازدیدترین ها');
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