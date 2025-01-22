<?php session_start();
require 'helper.php';
require 'database.php';

$islogin = isset($_SESSION['user_id']);
if ($islogin) {
    $userId = $_SESSION['user_id'];
} else {
    header("Location: /index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fa">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Movie Explorer</title>
    <link rel="stylesheet" href="/assets/css/signup.css">
    <link rel="stylesheet" href="/assets/css/style.css">
    <link rel="stylesheet" href="/assets/css/index.css">   
</head>

<body>
    <!-- navbar -->
    <?php require 'navbar.php'
    ?>

    <!-- Main Content -->
    <main>
        <!-- Related Actors and Movies Section -->
        <section class="item-cards-section">
            <!-- related actors Section -->
            <?php
            $artist = fetchFollowedArtistsFromDb($db, $userId);
            echo CreateListOfArtistCard($artist, 'بازیگران دنبال شده');
            ?>

            <!-- Related Movies Section -->
            <?php
            $movies = fetchMarkedMoviesFromDb($db, $userId);
            // $movies = fetchRelatedMovies($db);
            echo CreateListOfMovieCard($movies, 'فیلم های نشان شده');
            ?>
        </section>

        <section class="registration-section">
            <h2>اطلاعات کاربری</h2>
            <?php
            // Fetch user information from the database
            $user = fetchUserInfo($db, $userId);

                // Fetch user genres from the database
                $query = "SELECT GenreId FROM usergenre WHERE UserId = ?";
                $stmt = $db->prepare($query);
                $stmt->bind_param('i', $userId);
                $stmt->execute();
                $result = $stmt->get_result();
                $userGenres = [];
                while ($row = $result->fetch_assoc()) {
                    $userGenres[] = $row['GenreId'];
                }
                $stmt->close();

                // Fetch all genres from the database
                $query = "SELECT Id, Name FROM genres";
                $stmt = $db->prepare($query);
                $stmt->execute();
                $result = $stmt->get_result();
                $allGenres = [];
                while ($row = $result->fetch_assoc()) {
                    $allGenres[] = $row;
                }
                $stmt->close();
    


            if ($user) {
                echo '
        <form class="registration-form" action="/update_user.php" method="POST" enctype="multipart/form-data">
            <!-- Hidden Field for User ID -->
            <input type="hidden" name="id" value="' . htmlspecialchars($user['Id']) . '">

            <!-- Username Field -->
            <label for="username">نام کاربری:</label>
            <input type="text" id="username" name="username" value="' . htmlspecialchars($user['Username']) . '" required>

            <!-- Name Field -->
            <label for="name">نام :</label>
            <input type="text" id="name" name="name" value="' . htmlspecialchars($user['Name']) . '" required>

            <!-- Email Field -->
            <label for="email">ایمیل:</label>
            <input type="email" id="email" name="email" value="' . htmlspecialchars($user['Email']) . '" required>

            <!-- Password Field -->
            <label for="password">رمز عبور (اختیاری):</label>
            <input type="password" id="password" name="password" placeholder="********">

            <!-- Profile Image Upload -->
            <label for="profile_image">تصویر پروفایل:</label>
            <input type="file" id="profile_image" name="profile_image" accept="image/*">
            ';
                if (!empty($user['profile_image'])) {
                    echo '<img src="' . htmlspecialchars($user['profile_image']) . '" alt="تصویر پروفایل" class="profile-preview">';
                }
                echo '                
                <!-- Genre Selection Field -->
                <label for="genres">ژانرهای مورد علاقه:</label>
                <div class="genre-selection">';
                foreach ($allGenres as $genre) {
                    echo '<input type="checkbox" id="genre' . htmlspecialchars($genre['Id']) . '" name="genres[]" value="' . htmlspecialchars($genre['Id']) . '" ' . (in_array($genre['Id'], $userGenres) ? 'checked' : '') . '>';
                    echo '<label for="genre' . htmlspecialchars($genre['Id']) . '">' . htmlspecialchars($genre['Name']) . '</label>';
                }
                echo '</div>';
                echo  '<!-- Submit Button -->
            <button type="submit">بروزرسانی اطلاعات</button>
        </form>';
            } else {                
                echo '<p>اطلاعات کاربری یافت نشد.</p>';
            }
            ?>
        </section>


        <script src="assets/js/index.js"></script>
        <script src="assets/js/theme.js"></script>
    </main>
</body>

</html>

<?php

function fetchMarkedMoviesFromDb($db, $userId)
{
    $query = "SELECT 
    mo.Id AS id, 
    mo.Name AS title, 
    me.URL AS image
   FROM 
    movies mo
   LEFT JOIN 
    media me ON me.MovieId = mo.Id AND me.Type = 'poster'
   LEFT JOIN 
   	marks ma ON mo.id = ma.MovieId
    where ma.UserId = $userId    
   GROUP BY 
    mo.Id
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

function fetchFollowedArtistsFromDb($db, $userId)
{
    $query = "SELECT 
     ar.Id AS id,
     ar.Name AS name,
     m.URL AS image
     FROM
     artist ar 
     JOIN 
     media m on m.ArtistId = ar.Id
     LEFT JOIN 
   	 follows fo ON ar.id = fo.ArtistId
     where fo.UserId = $userId 
     GROUP BY
     ar.Id
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

// Helper method to create a single Artist card
function CreateArtistCard($name, $id, $image)
{
    return '<div class="item-card">
                <a href="' . htmlspecialchars('artist.php?id=' . $id) . '" class="item-card-link">
                    <img src="' . htmlspecialchars('/' . ltrim($image, '/')) . '" alt="' . htmlspecialchars($name) . '">
                    <p class="actor-name">' . htmlspecialchars($name) . '</p>
                </a>
            </div>';
}

// Helper method to create the list of Artist cards
function CreateListOfArtistCard($Artists, $header)
{
    $artistCardsHtml = '';

    // Loop through each artist and generate a artist card
    foreach ($Artists as $artist) {
        $artistCardsHtml .= CreateArtistCard($artist['name'], $artist['id'], $artist['image']);
    }

    // Return the full section with movie cards
    return '<section class="item-cards-section">
                <h2>' . htmlspecialchars($header) . '</h2>
                <div class="item-card-list">
                    ' . $artistCardsHtml . '
                </div>
            </section>';
}


function fetchUserInfo($db, $userId)
{
    $query = "SELECT Id, Name, Email, Username, profile_image FROM users WHERE Id = ?";
    $stmt = $db->prepare($query);
    $stmt->bind_param('i', $userId);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
    return $user;
}



?>