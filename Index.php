<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Explorer</title>
    <link rel="stylesheet" href="assets/css/style.css">
    <link rel="stylesheet" href="assets/css/index.css">
</head>

<body>
    <!-- navbar -->
    <?php require 'PHP/navbar.php'
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
        <section class="item-cards-section">
            <h2>پیشنهاداتی مخصوص شما</h2>
            <div class="item-card-list">
                <!-- Each movie card -->
                <div class="item-card">
                    <a href="GodFatherPage.html" class="item-card-link">
                        <img src="assets\images\GodfatherPoster.png" alt="Related Movie 1">
                        <p class="movie-title">پدرخوانده</p>
                    </a>
                </div>
                <div class="item-card">
                    <a href="" class="item-card-link">
                        <img src="assets\images\GodfatherPart2Poster.png" alt="Related Movie 1">
                        <p class="movie-title">پدرخوانده: قسمت دوم</p>
                    </a>
                </div>
                <div class="item-card">
                    <a href="" class="item-card-link">
                        <img src="assets\images\TheShawshankRedemption.jpg" alt="Related Movie 2">
                        <p class="movie-title">رستگاری در شاوشنگ</p>
                    </a>
                </div>
                <div class="item-card">
                    <a href="" class="item-card-link">
                        <img src="assets\images\ForrestGump.jpg" alt="Related Movie 3">
                        <p class="movie-title">فارست گامپ</p>
                    </a>
                </div>
                <div class="item-card">
                    <a href="" class="item-card-link">
                        <img src="assets\images\ForrestGump.jpg" alt="Related Movie 3">
                        <p class="movie-title">فارست گامپ</p>
                    </a>
                </div>
            </div>
        </section>

        <!-- Newest Movies Section -->
        <section class="item-cards-section">
            <h2>جدیدترین‌ها</h2>
            <div class="item-card-list">
                <!-- Each movie card -->
                <div class="item-card">
                    <a href="movie-details.html" class="item-card-link">
                        <img src="assets\images\GodfatherPart2Poster.png" alt="Related Movie 1">
                        <p class="movie-title">پدرخوانده: قسمت دوم</p>
                    </a>
                </div>
                <div class="item-card">
                    <a href="" class="item-card-link">
                        <img src="assets\images\TheShawshankRedemption.jpg" alt="Related Movie 2">
                        <p class="movie-title">رستگاری در شاوشنگ</p>
                    </a>
                </div>
                <div class="item-card">
                    <a href="" class="item-card-link">
                        <img src="assets\images\ForrestGump.jpg" alt="Related Movie 3">
                        <p class="movie-title">فارست گامپ</p>
                    </a>
                </div>
                <div class="item-card">
                    <a href="" class="item-card-link">
                        <img src="assets\images\ForrestGump.jpg" alt="Related Movie 3">
                        <p class="movie-title">فارست گامپ</p>
                    </a>
                </div>
            </div>
        </section>

        <!-- Most Viewed Movies Section -->
        <section class="item-cards-section">
            <h2>پر بازدیدترین‌ها</h2>
            <section class="day">
                <h3>روز</h3>
                <div class="item-card-list">
                    <!-- Movie Cards here -->
                    <div class="item-card">
                        <a href="" class="item-card-link">
                            <img src="assets\images\ForrestGump.jpg" alt="Related Movie 3">
                            <p class="movie-title">فارست گامپ</p>
                        </a>
                    </div>
                    <div class="item-card">
                        <a href="" class="item-card-link">
                            <img src="assets\images\ForrestGump.jpg" alt="Related Movie 3">
                            <p class="movie-title">فارست گامپ</p>
                        </a>
                    </div>
                    <div class="item-card">
                        <a href="" class="item-card-link">
                            <img src="assets\images\ForrestGump.jpg" alt="Related Movie 3">
                            <p class="movie-title">فارست گامپ</p>
                        </a>

                    </div>
                </div>
            </section>
            <section class="week">
                <h3>هفته</h3>
                <div class="item-card-list">
                    <!-- Movie Cards here -->
                    <div class="item-card">
                        <a href="" class="item-card-link">
                            <img src="assets\images\ForrestGump.jpg" alt="Related Movie 3">
                            <p class="movie-title">فارست گامپ</p>
                        </a>
                    </div>
                    <div class="item-card">
                        <a href="" class="item-card-link">
                            <img src="assets\images\ForrestGump.jpg" alt="Related Movie 3">
                            <p class="movie-title">فارست گامپ</p>
                        </a>
                    </div>
                    <div class="item-card">
                        <a href="" class="item-card-link">
                            <img src="assets\images\ForrestGump.jpg" alt="Related Movie 3">
                            <p class="movie-title">فارست گامپ</p>
                        </a>

                    </div>
                </div>
            </section>
            <section class="month">
                <h3>ماه</h3>
                <div class="item-card-list">
                    <!-- Movie Cards here -->
                    <div class="item-card">
                        <a href="" class="item-card-link">
                            <img src="assets\images\ForrestGump.jpg" alt="Related Movie 3">
                            <p class="movie-title">فارست گامپ</p>
                        </a>
                    </div>
                    <div class="item-card">
                        <a href="" class="item-card-link">
                            <img src="assets\images\ForrestGump.jpg" alt="Related Movie 3">
                            <p class="movie-title">فارست گامپ</p>
                        </a>
                    </div>
                    <div class="item-card">
                        <a href="" class="item-card-link">
                            <img src="assets\images\ForrestGump.jpg" alt="Related Movie 3">
                            <p class="movie-title">فارست گامپ</p>
                        </a>

                    </div>
                </div>
            </section>
        </section>

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