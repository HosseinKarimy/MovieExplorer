<?php 
require 'database.php';
require 'helper.php';

// Fetch movie details
$movieId = $_GET['id']; // Get movie ID from URL
$movieDetails = getMovieDetails($db, $movieId);
$genres = getMovieGenres($db, $movieId);
$posters = getMoviePosters($db, $movieId);
$artist = fetchArtistsFromDb($db);
?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Explorer - Movie Page</title>
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/movie.css">
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
        <section class="gallery-section">
            <div class="gallery-show">
                <!-- Placeholder for selected -->
                <img id="mainImage" src="assets/images/GodFatherImage1.jpg" alt="Gallery Main Image">
            </div>
            <div class="gallery-thumbnails">
                <!-- Thumbnail images for the gallery -->
                <img src="assets/images/GodFatherImage1.jpg" alt="Thumbnail">
                <img src="assets/images/GodFatherImage2.jpg" alt="Thumbnail">
                <img src="assets/images/GodFatherImage3.jpg" alt="Thumbnail">
                <img src="assets/images/GodFatherImage4.jpg" alt="Thumbnail">
                <img src="assets/images/GodFatherImage5.jpg" alt="Thumbnail">
            </div>
            <script src="assets/js/gallery.js"></script>
        </section>

        <!-- Related Actors and Movies Section -->
        <section class="item-cards-section">
            <!-- related actors Section -->
            <?php         
            echo CreateListOfArtistCard($artist,'بازیگران مرتبط');
            ?>
                
            <!-- Related Movies Section -->
            <section class="related-movies">
                <h3>فیلم‌های مرتبط</h3>
                <div class="item-card-list">
                    <div class="item-card">
                        <a href="" class="item-card-link">
                            <img src="assets/images/GodfatherPart2Poster.png" alt="Related Movie 1">
                            <p class="movie-title">پدرخوانده: قسمت دوم</p>
                        </a>
                    </div>
                    <div class="item-card">
                        <a href="" class="item-card-link">
                            <img src="assets/images/TheShawshankRedemption.jpg" alt="Related Movie 2">
                            <p class="movie-title">رستگاری در شاوشنگ</p>
                        </a>
                    </div>
                    <div class="item-card">
                        <a href="" class="item-card-link">
                            <img src="assets/images/ForrestGump.jpg" alt="Related Movie 3">
                            <p class="movie-title">فارست گامپ</p>
                        </a>
                    </div>
                </div>
            </section>
        </section>

        <!-- User Command and Rating Section -->
        <section class="comment-section">
            <h2>ثبت نظر شما</h2>
            <section>
                <form id="review-form" class="review-form">
                    <div class="form-group">
                        <label for="name">نام:</label>
                        <input type="text" id="name" name="name" placeholder="نام خود را وارد کنید">
                        <small class="error-message"></small>
                    </div>

                    <div class="form-group">
                        <label for="email">ایمیل:</label>
                        <input type="text" id="email" name="email" placeholder="ایمیل خود را وارد کنید">
                        <small class="error-message"></small>
                    </div>

                    <div class="form-group">
                        <label for="comment">نظر:</label>
                        <textarea id="comment" name="comment" rows="5" placeholder="نظر خود را بنویسید"
                            ></textarea>
                            <small class="error-message"></small> <small class="error-message"></small>
                    </div>
                    <button type="submit">ارسال نظر</button>
                    <p id="success-message" class="success-message"></p>
                </form>
            </section>

            <section>
                <form class="review-form">
                    <div class="form-group">
                        <label for="rating">امتیاز:</label>
                        <input type="number" id="rating" name="rating" min="1" max="10" placeholder="امتیاز (1 تا 10)"
                            required>
                    </div>
                    <button type="submit">ثبت امتیاز</button>
                </form>                
            </section>
        </section>


        <section class="review-section">
            <div class="review">
                <div class="review-detail">
                    <div class="name">علیرضا - 12 فروردین:</div>
                    <div class="rating">
                        <span>8/10</span>
                    </div>

                    <div class="like-count">
                        <span for="like-count">32</span>
                        <span for="like-count">❤️</span>
                    </div>
                </div>

                <div class="text">بد نبود</div>

                <div class="review-reply">
                    <div class="review-detail">
                        <div class="name">محمد - 15 فروردین:</div>
                        <div class="like-count">
                            <span for="like-count">3</span>
                            <span for="like-count">❤️</span>
                        </div>
                    </div>
                    <div class="text">بد نبود یا عالی بود؟</div>
                </div>
                <div class="review-reply">
                    <div class="review-detail">
                        <div class="name">نام کاربر - تاریخ</div>
                        <div class="like-count">
                            <span for="like-count">0</span>
                            <span for="like-count">❤️</span>
                        </div>
                    </div>
                    <div class="text">نظر شما...</div>
                </div>
            </div>

            <div class="review">
                <div class="review-detail">
                    <div class="name">نام کاربر - تاریخ</div>
                    <div class="rating">
                        <span> 4/10</span>
                    </div>
                    <div class="like-count">
                        <span for="like-count">32</span>
                        <span for="like-count">❤️</span>
                    </div>
                </div>

                <div class="text">نظر شما...</div>

                <div class="review-reply">
                    <div class="review-detail">
                        <div class="name">نام کاربر - تاریخ</div>
                        <div class="like-count">
                            <span for="like-count">32</span>
                            <span for="like-count">❤️</span>
                        </div>
                    </div>
                    <div class="text">نظر شما...</div>
                </div>
                <div class="review-reply">
                    <div class="review-detail">
                        <div class="name">نام کاربر - تاریخ</div>
                        <div class="like-count">
                            <span for="like-count">32</span>
                            <span for="like-count">❤️</span>
                        </div>
                    </div>
                    <div class="text">نظر شما...</div>
                </div>
                <div class="review-reply">
                    <div class="review-detail">
                        <div class="name">نام کاربر - تاریخ</div>
                        <div class="like-count">
                            <span for="like-count">32</span>
                            <span for="like-count">❤️</span>
                        </div>
                    </div>
                    <div class="text">نظر شما...</div>
                </div>
            </div>

        </section>



    </main>

    <script src="/assets/js/movie.js"></script>
    <script src="/assets/js/theme.js"></script>
</body>

</html>
