<?php
// Helper method to create a single movie card
function CreateMovieCard($title, $id, $image)
{
    return '<div class="item-card">
                <a href="' . htmlspecialchars('movie-details.php?id=' . $id) . '" class="item-card-link">
                    <img src="' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($title) . '">
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

/**
 * Fetch movies from the database.
 * @param mysqli $db The database connection.
 * @param int $limit The number of movies to fetch. Default is 10.
 * @return array The list of movies.
 */
function fetchMoviesFromDb($db)
{
    $query = "SELECT 
     mo.Id AS id, 
     mo.Name AS title, 
     me.URL AS image
    FROM 
     movies mo
    LEFT JOIN 
     media me ON me.MovieId = mo.Id AND me.Type = 'poster'
    GROUP BY mo.Id
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
