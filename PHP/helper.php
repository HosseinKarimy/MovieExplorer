<?php
// Helper method to create a single movie card
function CreateMovieCard($title, $id, $image)
{
    return '<div class="item-card">
                <a href="' . htmlspecialchars('PHP/movie.php?id=' . $id) . '" class="item-card-link">
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

function fetchMoviesFromDb($db,$orderBy = 'Date')
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
function CreateArtistCard($name, $role , $id, $image)
{
    return '<div class="item-card">
                <a href="' . htmlspecialchars('artist.php?id=' . $id) . '" class="item-card-link">
                    <img src="' . htmlspecialchars($image) . '" alt="' . htmlspecialchars($name) . '">
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
        $artistCardsHtml .= CreateArtistCard($artist['name'],$artist['role'], $artist['id'], $artist['image']);
    }

    // Return the full section with movie cards
    return '<section class="item-cards-section">
                <h2>' . htmlspecialchars($header) . '</h2>
                <div class="item-card-list">
                    ' . $artistCardsHtml . '
                </div>
            </section>';
}

function fetchArtistsFromDb($db,$orderBy = 'name' , $movieId)
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