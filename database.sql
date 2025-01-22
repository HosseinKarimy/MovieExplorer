-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Jan 22, 2025 at 04:14 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `movieexplorer`
--

DELIMITER $$
--
-- Procedures
--
CREATE DEFINER=`root`@`localhost` PROCEDURE `ToggleFollow` (IN `userId` INT, IN `artistId` INT)   BEGIN
   IF EXISTS (SELECT 1 FROM follows WHERE UserId = userId AND ArtistId= artistId) THEN
        -- اگر رکورد وجود دارد، حذف شود
        DELETE FROM follows WHERE UserId = userId AND ArtistId = artistId;
        SELECT 'removed' AS status;
    ELSE
        -- اگر رکورد وجود ندارد، اضافه شود
        INSERT INTO follows(UserId, ArtistId) VALUES (userId, artistId);
        SELECT 'added' AS status;
    END IF;
END$$

CREATE DEFINER=`root`@`localhost` PROCEDURE `ToggleMark` (IN `userId` INT, IN `movieId` INT)   BEGIN
    IF EXISTS (SELECT 1 FROM marks WHERE UserId = userId AND MovieId = movieId) THEN
        -- اگر رکورد وجود دارد، حذف شود
        DELETE FROM marks WHERE UserId = userId AND MovieId = movieId;
        SELECT 'removed' AS status;
    ELSE
        -- اگر رکورد وجود ندارد، اضافه شود
        INSERT INTO marks (UserId, MovieId) VALUES (userId, movieId);
        SELECT 'added' AS status;
    END IF;
END$$

DELIMITER ;

-- --------------------------------------------------------

--
-- Table structure for table `acts`
--

CREATE TABLE `acts` (
  `MovieId` int(11) NOT NULL,
  `ActorId` int(11) NOT NULL,
  `Role` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `acts`
--

INSERT INTO `acts` (`MovieId`, `ActorId`, `Role`) VALUES
(1, 3, 'Don Vito Corleone'),
(1, 4, 'Michael Corleone'),
(1, 5, 'Sonny Corleone'),
(1, 6, 'Tom Hagen'),
(1, 7, 'Kay Adams'),
(2, 9, 'Andy Dufresne'),
(2, 10, 'Ellis Boyd \'Red\' Redding'),
(2, 11, 'Warden Norton'),
(2, 12, 'Heywood'),
(2, 13, 'Captain Hadley'),
(3, 3, 'Don Vito Corleone'),
(3, 4, 'Michael Corleone'),
(3, 5, 'Sonny Corleone'),
(3, 15, 'Young Vito Corleone'),
(3, 16, 'Connie Corleone'),
(3, 17, 'Hyman Roth'),
(4, 20, 'Bruce Wayne / Batman'),
(4, 21, 'Joker'),
(4, 22, 'Harvey Dent / Two-Face'),
(4, 23, 'Alfred Pennyworth'),
(4, 24, 'Commissioner Gordon'),
(4, 25, 'Rachel Dawes'),
(4, 26, 'Lucius Fox'),
(4, 27, 'Dr. Jonathan Crane / Scarecrow'),
(4, 28, 'Sal Maroni'),
(4, 29, 'Lau'),
(5, 50, 'Juror 8'),
(5, 51, 'Juror 3'),
(6, 54, 'Oskar Schindler'),
(6, 55, 'Amon Goeth'),
(7, 58, 'Vincent Vega'),
(8, 59, 'Blondie'),
(9, 61, 'Tyler Durden'),
(10, 63, 'Dom Cobb'),
(11, 65, 'Neo'),
(12, 68, 'Cooper');

-- --------------------------------------------------------

--
-- Table structure for table `artist`
--

CREATE TABLE `artist` (
  `Id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `IsWriter` tinyint(1) DEFAULT NULL,
  `IsActor` tinyint(1) DEFAULT NULL,
  `IsDirector` tinyint(1) DEFAULT NULL,
  `Bio` text DEFAULT NULL,
  `Birthdate` varchar(255) DEFAULT NULL,
  `Details` varchar(255) DEFAULT NULL,
  `Nationality` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `artist`
--

INSERT INTO `artist` (`Id`, `Name`, `IsWriter`, `IsActor`, `IsDirector`, `Bio`, `Birthdate`, `Details`, `Nationality`) VALUES
(1, 'Francis Ford Coppola', 1, 0, 1, 'Francis Ford Coppola is an American filmmaker known for directing The Godfather trilogy, Apocalypse Now, and several other critically acclaimed films.', '1939-04-07', 'Won multiple Academy Awards; Known for The Godfather Trilogy.', 'American'),
(2, 'Mario Puzo', 1, 0, 0, 'Mario Puzo was an American author and screenwriter, best known for writing the novel The Godfather and co-writing its film adaptation with Francis Ford Coppola.', '1920-10-15', 'Author of The Godfather novel; Academy Award-winning screenwriter.', 'American'),
(3, 'Marlon Brando', 0, 1, 0, 'Marlon Brando played Don Vito Corleone in The Godfather.', '1924-04-03', NULL, 'American'),
(4, 'Al Pacino', 0, 1, 0, 'Al Pacino played Michael Corleone in The Godfather.', '1940-04-25', NULL, 'American'),
(5, 'James Caan', 0, 1, 0, 'James Caan played Sonny Corleone in The Godfather.', '1940-03-26', NULL, 'American'),
(6, 'Robert Duvall', 0, 1, 0, 'Robert Duvall played Tom Hagen in The Godfather.', '1931-01-05', NULL, 'American'),
(7, 'Diane Keaton', 0, 1, 0, 'Diane Keaton played Kay Adams in The Godfather.', '1946-01-05', NULL, 'American'),
(8, 'Frank Darabont', 1, 0, 1, 'نویسنده و کارگردانی معروف که بیشتر به خاطر ساخت فیلم‌های درام تحسین‌شده شناخته می‌شود.', '28 ژانویه 1959', NULL, 'آمریکایی'),
(9, 'Tim Robbins', 0, 1, 0, 'بازیگر برجسته آمریکایی که برای ایفای نقش‌های دراماتیک شناخته می‌شود.', '16 اکتبر 1958', NULL, 'آمریکایی'),
(10, 'Morgan Freeman', 0, 1, 0, 'بازیگر مشهور آمریکایی که به خاطر صدای جذاب و نقش‌های عمیق شناخته می‌شود.', '1 ژوئن 1937', NULL, 'آمریکایی'),
(11, 'Bob Gunton', 0, 1, 0, 'بازیگر آمریکایی که برای ایفای نقش شخصیت‌های جدی شناخته می‌شود.', '15 نوامبر 1945', NULL, 'آمریکایی'),
(12, 'William Sadler', 0, 1, 0, 'بازیگر آمریکایی که در نقش‌های درام و کمدی ظاهر شده است.', '13 آوریل 1950', NULL, 'آمریکایی'),
(13, 'Clancy Brown', 0, 1, 0, 'بازیگر آمریکایی که بیشتر به خاطر صدای خاص و نقش‌های شرورانه شناخته می‌شود.', '5 ژانویه 1959', NULL, 'آمریکایی'),
(14, 'Stephen King', 1, 0, 0, 'نویسنده‌ای مشهور که به خاطر آثار درخشان خود در ژانرهای وحشت و درام شناخته می‌شود.', '21 سپتامبر 1947', NULL, 'آمریکایی'),
(15, 'Robert De Niro', 0, 1, 0, 'بازیگر مشهور آمریکایی که به خاطر ایفای نقش‌های متفاوت و متنوع شناخته می‌شود.', '1943-08-17', NULL, 'آمریکایی'),
(16, 'Talia Shire', 0, 1, 0, 'بازیگر آمریکایی که به خاطر نقش‌آفرینی در سری فیلم‌های پدرخوانده و راکی شناخته می‌شود.', '1946-04-25', NULL, 'آمریکایی'),
(17, 'Lee Strasberg', 0, 1, 0, 'بازیگر و مربی بازیگری که در نقش‌های ماندگار زیادی حضور داشته است.', '1901-11-17', NULL, 'آمریکایی'),
(18, 'Francis Ford Coppola', 1, 0, 1, 'کارگردان و نویسنده سری فیلم‌های پدرخوانده.', '1939-04-07', NULL, 'آمریکایی'),
(19, 'Mario Puzo', 1, 0, 0, 'نویسنده رمان پدرخوانده.', '1920-10-15', NULL, 'آمریکایی'),
(20, 'Christian Bale', 0, 1, 0, 'Played the role of Bruce Wayne / Batman in The Dark Knight trilogy.', '1974-01-30', NULL, 'British'),
(21, 'Heath Ledger', 0, 1, 0, 'Portrayed the iconic Joker in The Dark Knight.', '1979-04-04', NULL, 'Australian'),
(22, 'Aaron Eckhart', 0, 1, 0, 'Played Harvey Dent / Two-Face in The Dark Knight.', '1968-03-12', NULL, 'American'),
(23, 'Michael Caine', 0, 1, 0, 'Played Alfred Pennyworth in The Dark Knight trilogy.', '1933-03-14', NULL, 'British'),
(24, 'Gary Oldman', 0, 1, 0, 'Played Commissioner Gordon in The Dark Knight trilogy.', '1958-03-21', NULL, 'British'),
(25, 'Maggie Gyllenhaal', 0, 1, 0, 'Portrayed Rachel Dawes in The Dark Knight.', '1977-11-16', NULL, 'American'),
(26, 'Morgan Freeman', 0, 1, 0, 'Played Lucius Fox in The Dark Knight trilogy.', '1937-06-01', NULL, 'American'),
(27, 'Cillian Murphy', 0, 1, 0, 'Played Dr. Jonathan Crane / Scarecrow in The Dark Knight trilogy.', '1976-05-25', NULL, 'Irish'),
(28, 'Eric Roberts', 0, 1, 0, 'Played Sal Maroni in The Dark Knight.', '1956-04-18', NULL, 'American'),
(29, 'Chin Han', 0, 1, 0, 'Played Lau in The Dark Knight.', '1969-11-27', NULL, 'Singaporean'),
(30, 'Christopher Nolan', 1, 0, 1, 'Director and writer known for creating The Dark Knight trilogy.', '1970-07-30', NULL, 'British'),
(31, 'Jonathan Nolan', 1, 0, 0, 'Writer of The Dark Knight, brother of Christopher Nolan.', '1976-06-06', NULL, 'British'),
(50, 'Henry Fonda', 0, 1, 0, 'Famous for his performance in \"12 Angry Men\".', '1905-05-16', NULL, 'American'),
(51, 'Lee J. Cobb', 0, 1, 0, 'Known for playing Juror 3 in \"12 Angry Men\".', '1911-12-08', NULL, 'American'),
(52, 'Sidney Lumet', 1, 0, 1, 'Director of \"12 Angry Men\".', '1924-06-25', NULL, 'American'),
(53, 'Reginald Rose', 1, 0, 0, 'Writer of \"12 Angry Men\".', '1920-12-10', NULL, 'American'),
(54, 'Liam Neeson', 0, 1, 0, 'Starred as Oskar Schindler in \"Schindler\'s List\".', '1952-06-07', NULL, 'Irish'),
(55, 'Ralph Fiennes', 0, 1, 0, 'Played Amon Goeth in \"Schindler\'s List\".', '1962-12-22', NULL, 'British'),
(56, 'Steven Spielberg', 1, 0, 1, 'Director of \"Schindler\'s List\".', '1946-12-18', NULL, 'American'),
(57, 'Quentin Tarantino', 1, 0, 1, 'Director and writer of \"Pulp Fiction\".', '1963-03-27', NULL, 'American'),
(58, 'John Travolta', 0, 1, 0, 'Starred as Vincent Vega in \"Pulp Fiction\".', '1954-02-18', NULL, 'American'),
(59, 'Clint Eastwood', 0, 1, 0, 'Played Blondie in \"The Good, the Bad and the Ugly\".', '1930-05-31', NULL, 'American'),
(60, 'Sergio Leone', 1, 0, 1, 'Director of \"The Good, the Bad and the Ugly\".', '1929-01-03', NULL, 'Italian'),
(61, 'Brad Pitt', 0, 1, 0, 'Played Tyler Durden in \"Fight Club\".', '1963-12-18', NULL, 'American'),
(62, 'David Fincher', 1, 0, 1, 'Director of \"Fight Club\".', '1962-08-28', NULL, 'American'),
(63, 'Leonardo DiCaprio', 0, 1, 0, 'Starred as Dom Cobb in \"Inception\".', '1974-11-11', NULL, 'American'),
(64, 'Christopher Nolan', 1, 0, 1, 'Director of \"Inception\" and \"Interstellar\".', '1970-07-30', NULL, 'British-American'),
(65, 'Keanu Reeves', 0, 1, 0, 'Played Neo in \"The Matrix\".', '1964-09-02', NULL, 'Canadian'),
(66, 'Lana Wachowski', 1, 0, 1, 'Co-director of \"The Matrix\".', '1965-06-21', NULL, 'American'),
(67, 'Andy Wachowski', 1, 0, 1, 'Co-director of \"The Matrix\".', '1967-12-29', NULL, 'American'),
(68, 'Matthew McConaughey', 0, 1, 0, 'Starred as Cooper in \"Interstellar\".', '1969-11-04', NULL, 'American');

-- --------------------------------------------------------

--
-- Table structure for table `comments`
--

CREATE TABLE `comments` (
  `Id` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `MovieId` int(11) NOT NULL,
  `Comment` varchar(255) NOT NULL,
  `ParrentId` int(11) DEFAULT NULL,
  `CreatedAt` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `comments`
--

INSERT INTO `comments` (`Id`, `UserId`, `MovieId`, `Comment`, `ParrentId`, `CreatedAt`) VALUES
(1, 11, 2, 'etste', NULL, '2025-01-12 20:39:53'),
(2, 5, 2, 'dsfgdsgfdsgfds', 1, '2025-01-12 21:06:49'),
(3, 5, 2, 'ewrqwrwfds', 1, '2025-01-12 21:06:55'),
(4, 11, 2, 'werewr', 1, '2025-01-12 22:35:23'),
(5, 11, 2, 'test', NULL, '2025-01-15 00:26:25'),
(6, 11, 2, 'teste3', NULL, '2025-01-15 00:47:18'),
(7, 11, 2, 'werewr', 5, '2025-01-15 00:47:24'),
(8, 11, 3, 'sdfdfsgfdsg', NULL, '2025-01-21 10:07:57'),
(9, 11, 3, 'werwrwr', 8, '2025-01-21 10:08:07');

-- --------------------------------------------------------

--
-- Table structure for table `commentslikes`
--

CREATE TABLE `commentslikes` (
  `CommentId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `commentslikes`
--

INSERT INTO `commentslikes` (`CommentId`, `UserId`) VALUES
(1, 11),
(3, 11),
(5, 11),
(8, 11);

-- --------------------------------------------------------

--
-- Table structure for table `follows`
--

CREATE TABLE `follows` (
  `UserId` int(11) NOT NULL,
  `ArtistId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `follows`
--

INSERT INTO `follows` (`UserId`, `ArtistId`) VALUES
(11, 17);

-- --------------------------------------------------------

--
-- Table structure for table `genremovie`
--

CREATE TABLE `genremovie` (
  `MovieId` int(11) NOT NULL,
  `GenreId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genremovie`
--

INSERT INTO `genremovie` (`MovieId`, `GenreId`) VALUES
(1, 3),
(1, 7),
(2, 3),
(2, 7),
(3, 3),
(3, 7),
(4, 3),
(4, 7),
(5, 3),
(5, 7),
(6, 3),
(6, 7),
(7, 2),
(7, 7),
(8, 1),
(8, 7),
(9, 3),
(9, 7),
(10, 1),
(10, 7),
(11, 6),
(11, 7),
(12, 3),
(12, 6);

-- --------------------------------------------------------

--
-- Table structure for table `genres`
--

CREATE TABLE `genres` (
  `Id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `genres`
--

INSERT INTO `genres` (`Id`, `Name`) VALUES
(1, 'اکشن'),
(2, 'کمدی'),
(3, 'درام'),
(4, 'ترسناک'),
(5, 'عاشقانه'),
(6, 'علمی تخیلی'),
(7, 'هیجان انگیز');

-- --------------------------------------------------------

--
-- Table structure for table `marks`
--

CREATE TABLE `marks` (
  `UserId` int(11) NOT NULL,
  `MovieId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `marks`
--

INSERT INTO `marks` (`UserId`, `MovieId`) VALUES
(11, 3);

-- --------------------------------------------------------

--
-- Table structure for table `media`
--

CREATE TABLE `media` (
  `Id` int(11) NOT NULL,
  `Type` enum('poster','image','video') NOT NULL,
  `URL` varchar(255) NOT NULL,
  `ArtistId` int(11) DEFAULT NULL,
  `MovieId` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `media`
--

INSERT INTO `media` (`Id`, `Type`, `URL`, `ArtistId`, `MovieId`) VALUES
(1, 'image', 'assets/images/marlon_brando_1.jpg', 3, NULL),
(2, 'image', 'assets/images/marlon_brando_2.jpg', 3, NULL),
(3, 'image', 'assets/images/marlon_brando_3.jpg', 3, NULL),
(4, 'image', 'assets/images/marlon_brando_4.jpg', 3, NULL),
(5, 'image', 'assets/images/marlon_brando_5.jpg', 3, NULL),
(6, 'image', 'assets/images/al_pacino_1.jpg', 4, NULL),
(7, 'image', 'assets/images/al_pacino_2.jpg', 4, NULL),
(8, 'image', 'assets/images/al_pacino_3.jpg', 4, NULL),
(9, 'image', 'assets/images/al_pacino_4.jpg', 4, NULL),
(10, 'image', 'assets/images/al_pacino_5.jpg', 4, NULL),
(11, 'image', 'assets/images/james_caan_1.jpg', 5, NULL),
(12, 'image', 'assets/images/james_caan_2.jpg', 5, NULL),
(13, 'image', 'assets/images/james_caan_3.jpg', 5, NULL),
(14, 'image', 'assets/images/james_caan_4.jpg', 5, NULL),
(15, 'image', 'assets/images/james_caan_5.jpg', 5, NULL),
(16, 'image', 'assets/images/robert_duvall_1.jpg', 6, NULL),
(17, 'image', 'assets/images/robert_duvall_2.jpg', 6, NULL),
(18, 'image', 'assets/images/robert_duvall_3.jpg', 6, NULL),
(19, 'image', 'assets/images/robert_duvall_4.jpg', 6, NULL),
(20, 'image', 'assets/images/robert_duvall_5.jpg', 6, NULL),
(21, 'image', 'assets/images/diane_keaton_1.jpg', 7, NULL),
(22, 'image', 'assets/images/diane_keaton_2.jpg', 7, NULL),
(23, 'image', 'assets/images/diane_keaton_3.jpg', 7, NULL),
(24, 'image', 'assets/images/diane_keaton_4.jpg', 7, NULL),
(25, 'image', 'assets/images/diane_keaton_5.jpg', 7, NULL),
(26, 'poster', 'assets/posters/godfather_poster_1.jpg', NULL, 1),
(27, 'poster', 'assets/posters/godfather_poster_2.jpg', NULL, 1),
(28, 'poster', 'assets/posters/godfather_poster_3.jpg', NULL, 1),
(29, 'video', 'assets/videos/godfather_teaser.mp4', NULL, 1),
(30, 'poster', 'assets/posters/shawshank_poster_1.jpg', NULL, 2),
(31, 'poster', 'assets/posters/shawshank_poster_2.jpg', NULL, 2),
(32, 'poster', 'assets/posters/shawshank_poster_3.jpg', NULL, 2),
(33, 'video', 'assets/videos/shawshank_teaser.mp4', NULL, 2),
(34, 'image', 'assets/images/tim_robbins_1.jpg', 9, NULL),
(35, 'image', 'assets/images/tim_robbins_2.jpg', 9, NULL),
(36, 'image', 'assets/images/tim_robbins_3.jpg', 9, NULL),
(37, 'image', 'assets/images/tim_robbins_4.jpg', 9, NULL),
(38, 'image', 'assets/images/tim_robbins_5.jpg', 9, NULL),
(39, 'image', 'assets/images/morgan_freeman_1.jpg', 10, NULL),
(40, 'image', 'assets/images/morgan_freeman_2.jpg', 10, NULL),
(41, 'image', 'assets/images/morgan_freeman_3.jpg', 10, NULL),
(42, 'image', 'assets/images/morgan_freeman_4.jpg', 10, NULL),
(43, 'image', 'assets/images/morgan_freeman_5.jpg', 10, NULL),
(44, 'image', 'assets/images/bob_gunton_1.jpg', 11, NULL),
(45, 'image', 'assets/images/bob_gunton_2.jpg', 11, NULL),
(46, 'image', 'assets/images/bob_gunton_3.jpg', 11, NULL),
(47, 'image', 'assets/images/bob_gunton_4.jpg', 11, NULL),
(48, 'image', 'assets/images/bob_gunton_5.jpg', 11, NULL),
(49, 'image', 'assets/images/william_sadler_1.jpg', 12, NULL),
(50, 'image', 'assets/images/william_sadler_2.jpg', 12, NULL),
(51, 'image', 'assets/images/william_sadler_3.jpg', 12, NULL),
(52, 'image', 'assets/images/william_sadler_4.jpg', 12, NULL),
(53, 'image', 'assets/images/william_sadler_5.jpg', 12, NULL),
(54, 'image', 'assets/images/clancy_brown_1.jpg', 13, NULL),
(55, 'image', 'assets/images/clancy_brown_2.jpg', 13, NULL),
(56, 'image', 'assets/images/clancy_brown_3.jpg', 13, NULL),
(57, 'image', 'assets/images/clancy_brown_4.jpg', 13, NULL),
(58, 'image', 'assets/images/clancy_brown_5.jpg', 13, NULL),
(59, 'poster', 'assets/posters/godfather2_poster_1.jpg', NULL, 3),
(60, 'poster', 'assets/posters/godfather2_poster_2.jpg', NULL, 3),
(61, 'image', 'assets/images/al_pacino_gf2_1.jpg', 4, NULL),
(62, 'image', 'assets/images/al_pacino_gf2_2.jpg', 4, NULL),
(63, 'image', 'assets/images/robert_duvall_gf2_1.jpg', 6, NULL),
(64, 'image', 'assets/images/robert_duvall_gf2_2.jpg', 6, NULL),
(65, 'image', 'assets/images/diane_keaton_gf2_1.jpg', 7, NULL),
(66, 'image', 'assets/images/diane_keaton_gf2_2.jpg', 7, NULL),
(67, 'image', 'assets/images/robert_de_niro_gf2_1.jpg', 15, NULL),
(68, 'image', 'assets/images/robert_de_niro_gf2_2.jpg', 15, NULL),
(69, 'image', 'assets/images/john_cazale_gf2_1.jpg', 16, NULL),
(70, 'image', 'assets/images/john_cazale_gf2_2.jpg', 16, NULL),
(71, 'image', 'assets/images/talia_shire_gf2_1.jpg', 17, NULL),
(72, 'image', 'assets/images/talia_shire_gf2_2.jpg', 17, NULL),
(73, 'image', 'assets/images/lee_strasberg_gf2_1.jpg', 18, NULL),
(74, 'image', 'assets/images/lee_strasberg_gf2_2.jpg', 18, NULL),
(75, 'image', 'assets/images/michael_v_gazzo_gf2_1.jpg', 19, NULL),
(76, 'image', 'assets/images/michael_v_gazzo_gf2_2.jpg', 19, NULL),
(77, 'image', 'assets/images/godfather2_scene_1.jpg', NULL, 3),
(78, 'image', 'assets/images/godfather2_scene_2.jpg', NULL, 3),
(79, 'image', 'assets/images/godfather2_scene_3.jpg', NULL, 3),
(80, 'image', 'assets/images/godfather2_scene_4.jpg', NULL, 3),
(81, 'image', 'assets/images/godfather2_scene_5.jpg', NULL, 3),
(82, 'image', 'assets/images/henry_fonda.jpg', 50, NULL),
(83, 'image', 'assets/images/lee_j_cobb.jpg', 51, NULL),
(84, 'image', 'assets/images/sidney_lumet.jpg', 52, NULL),
(85, 'image', 'assets/images/reginald_rose.jpg', 53, NULL),
(86, 'image', 'assets/images/liam_neeson.jpg', 54, NULL),
(87, 'image', 'assets/images/ralph_fiennes.jpg', 55, NULL),
(88, 'image', 'assets/images/steven_spielberg.jpg', 56, NULL),
(89, 'image', 'assets/images/quentin_tarantino.jpg', 57, NULL),
(90, 'image', 'assets/images/john_travolta.jpg', 58, NULL),
(91, 'image', 'assets/images/clint_eastwood.jpg', 59, NULL),
(92, 'image', 'assets/images/sergio_leone.jpg', 60, NULL),
(93, 'image', 'assets/images/brad_pitt.jpg', 61, NULL),
(94, 'image', 'assets/images/david_fincher.jpg', 62, NULL),
(95, 'image', 'assets/images/leonardo_dicaprio.jpg', 63, NULL),
(96, 'image', 'assets/images/christopher_nolan.jpg', 64, NULL),
(97, 'image', 'assets/images/keanu_reeves.jpg', 65, NULL),
(98, 'image', 'assets/images/lana_wachowski.jpg', 66, NULL),
(99, 'image', 'assets/images/andy_wachowski.jpg', 67, NULL),
(100, 'image', 'assets/images/matthew_mcconaughey.jpg', 68, NULL),
(101, 'poster', 'assets/images/12_angry_men_poster1.jpg', NULL, 4),
(102, 'poster', 'assets/images/12_angry_men_poster2.jpg', NULL, 4),
(103, 'poster', 'assets/images/schindlers_list_poster1.jpg', NULL, 5),
(104, 'poster', 'assets/images/schindlers_list_poster2.jpg', NULL, 5),
(105, 'poster', 'assets/images/pulp_fiction_poster1.jpg', NULL, 6),
(106, 'poster', 'assets/images/pulp_fiction_poster2.jpg', NULL, 6),
(107, 'poster', 'assets/images/good_bad_ugly_poster1.jpg', NULL, 7),
(108, 'poster', 'assets/images/good_bad_ugly_poster2.jpg', NULL, 7),
(109, 'poster', 'assets/images/fight_club_poster1.jpg', NULL, 8),
(110, 'poster', 'assets/images/fight_club_poster2.jpg', NULL, 8),
(111, 'poster', 'assets/images/inception_poster1.jpg', NULL, 9),
(112, 'poster', 'assets/images/inception_poster2.jpg', NULL, 9),
(113, 'poster', 'assets/images/matrix_poster1.jpg', NULL, 10),
(114, 'poster', 'assets/images/matrix_poster2.jpg', NULL, 10),
(115, 'poster', 'assets/images/interstellar_poster1.jpg', NULL, 11),
(116, 'poster', 'assets/images/interstellar_poster2.jpg', NULL, 11),
(117, 'poster', 'assets/images/interstellar_poster1.jpg', NULL, 12),
(118, 'poster', 'assets/images/interstellar_poster2.jpg', NULL, 12),
(119, 'image', 'assets/images/francis_ford_coppola.jpg', 1, NULL),
(120, 'image', 'assets/images/mario_puzo.jpg', 2, NULL),
(121, 'image', 'assets/images/frank_darabont.jpg', 8, NULL),
(122, 'image', 'assets/images/stephen_king.jpg', 14, NULL),
(123, 'image', 'assets/images/christian_bale.jpg', 20, NULL),
(124, 'image', 'assets/images/heath_ledger.jpg', 21, NULL),
(125, 'image', 'assets/images/aaron_eckhart.jpg', 22, NULL),
(126, 'image', 'assets/images/michael_caine.jpg', 23, NULL),
(127, 'image', 'assets/images/gary_oldman.jpg', 24, NULL),
(128, 'image', 'assets/images/maggie_gyllenhaal.jpg', 25, NULL),
(129, 'image', 'assets/images/morgan_freeman.jpg', 26, NULL),
(130, 'image', 'assets/images/cillian_murphy.jpg', 27, NULL),
(131, 'image', 'assets/images/eric_roberts.jpg', 28, NULL),
(132, 'image', 'assets/images/chin_han.jpg', 29, NULL),
(133, 'image', 'assets/images/christopher_nolan.jpg', 30, NULL),
(134, 'image', 'assets/images/jonathan_nolan.jpg', 31, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `movies`
--

CREATE TABLE `movies` (
  `id` int(11) NOT NULL,
  `Name` varchar(255) NOT NULL,
  `Date` varchar(50) DEFAULT NULL,
  `Duration` int(11) DEFAULT NULL COMMENT 'minutes',
  `Languages` varchar(255) DEFAULT NULL,
  `Summary` text DEFAULT NULL,
  `DirectorId` int(11) NOT NULL,
  `WriterId` int(11) NOT NULL,
  `ViewCount` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `movies`
--

INSERT INTO `movies` (`id`, `Name`, `Date`, `Duration`, `Languages`, `Summary`, `DirectorId`, `WriterId`, `ViewCount`) VALUES
(1, 'The Godfather', '1972-03-24', 175, 'English, Italian, Latin', 'The aging patriarch of an organized crime dynasty transfers control of his clandestine empire to his reluctant son.', 1, 2, 0),
(2, 'رستگاری در شاوشنگ', '1994', 142, 'English', 'مردی بی‌گناه به زندان فرستاده می‌شود و در طول سال‌ها با قدرت امید و دوستی به آزادی معنوی می‌رسد.', 8, 14, 4),
(3, 'The Godfather: Part II', '1974-12-20', 202, 'English, Italian, Spanish', 'این فیلم داستان‌های پیش و پس از وقایع پدرخوانده را روایت می‌کند، از جمله جوانی ویتو کورلئونه و ادامه زندگی مایکل کورلئونه.', 18, 19, 3),
(4, 'The Dark Knight', '2008-07-18', 152, 'English', 'When the menace known as the Joker emerges, Batman must accept one of the greatest psychological and physical tests to fight injustice.', 30, 31, 4),
(5, '12 Angry Men', '1957-04-10', 96, 'English', 'A jury deliberates the guilt or innocence of a defendant on the basis of reasonable doubt.', 52, 53, 0),
(6, 'Schindler\'s List', '1993-12-15', 195, 'English, German, Polish', 'The story of Oskar Schindler who saved over a thousand Polish Jews during the Holocaust.', 56, 56, 0),
(7, 'Pulp Fiction', '1994-10-14', 154, 'English', 'The lives of two mob hitmen, a boxer, and others intertwine in a series of vignettes.', 57, 57, 1),
(8, 'The Good, the Bad and the Ugly', '1966-12-23', 178, 'Italian, English', 'Three men hunt for a buried treasure during the Civil War.', 60, 60, 0),
(9, 'Fight Club', '1999-10-15', 139, 'English', 'An insomniac office worker and a soap salesman form an underground fight club.', 62, 62, 1),
(10, 'Inception', '2010-07-16', 148, 'English', 'A thief who steals secrets through dream-sharing technology is tasked with planting an idea.', 64, 64, 15),
(11, 'The Matrix', '1999-03-31', 136, 'English', 'A computer hacker learns about the true nature of his reality and his role in the war against its controllers.', 66, 67, 0),
(12, 'Interstellar', '2014-11-07', 169, 'English', 'A team of explorers travels through a wormhole in space in search of a new home for humanity.', 64, 64, 30);

-- --------------------------------------------------------

--
-- Table structure for table `rates`
--

CREATE TABLE `rates` (
  `MovieId` int(11) NOT NULL,
  `UserId` int(11) NOT NULL,
  `Rate` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `rates`
--

INSERT INTO `rates` (`MovieId`, `UserId`, `Rate`) VALUES
(12, 5, 7),
(2, 11, 5),
(3, 11, 7),
(12, 11, 6);

-- --------------------------------------------------------

--
-- Table structure for table `usergenre`
--

CREATE TABLE `usergenre` (
  `UserId` int(11) NOT NULL,
  `GenreId` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `usergenre`
--

INSERT INTO `usergenre` (`UserId`, `GenreId`) VALUES
(5, 1),
(5, 2),
(5, 7),
(6, 1),
(6, 2),
(7, 2),
(7, 6),
(7, 7),
(14, 1),
(14, 2),
(14, 6);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `Id` int(11) NOT NULL,
  `Name` varchar(50) NOT NULL,
  `Email` varchar(255) NOT NULL,
  `Username` varchar(255) NOT NULL,
  `Password` varchar(255) NOT NULL,
  `profile_image` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`Id`, `Name`, `Email`, `Username`, `Password`, `profile_image`) VALUES
(5, 'حسین', 'hossein@gmail.com', 'hossein', '$2y$10$UdO6aEToH6obSRutJ.1sxeewUa0f37SyLqss5bm2.8U0XMMgmENkq', 'uploads/profile_images/6790492ec66b9_Screenshot 2025-01-22 045544.png'),
(6, 'حسین', 'hossein@gmail.com', 'hosseins', '$2y$10$aK5dA35ZTOkKcWB9FCsBiO2X5OeYEqbxKDLCscRzUAAzQd9Nkzjq.', NULL),
(7, 'حسین', 'hossein@gmail.com', 'hosseinwer', '$2y$10$pIw0l1eG56nYS3Hq2gOMLOyZCqVF5IzuGRgMMsDgMP1nthsf5iHru', NULL),
(11, 'user', 'user@gmali.com', 'user', '$2y$10$PE4/1sVM9yY6pVMExIMv/.tgnZ0SBhhTVsJVsyNWy0IHTmZpX0wFq', 'uploads/profile_images/profile_677f65bc45fd63.17867906.png'),
(14, 'eee', 'seresr@gmail.com', 'test', '$2y$10$7bkU1DXohzQ3tfq9SpwnJuq48M/kbNPn4pHfo8kjLoezSrYvaDPPW', 'uploads/profile_images/67904a110ffdc_Screenshot 2025-01-22 045544.png');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `acts`
--
ALTER TABLE `acts`
  ADD PRIMARY KEY (`MovieId`,`ActorId`),
  ADD KEY `aa` (`ActorId`);

--
-- Indexes for table `artist`
--
ALTER TABLE `artist`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `comments`
--
ALTER TABLE `comments`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `mmmmm` (`MovieId`),
  ADD KEY `uuuuuu` (`UserId`),
  ADD KEY `p` (`ParrentId`);

--
-- Indexes for table `commentslikes`
--
ALTER TABLE `commentslikes`
  ADD PRIMARY KEY (`CommentId`,`UserId`),
  ADD KEY `werewqr` (`UserId`);

--
-- Indexes for table `follows`
--
ALTER TABLE `follows`
  ADD PRIMARY KEY (`UserId`,`ArtistId`),
  ADD KEY `a` (`ArtistId`);

--
-- Indexes for table `genremovie`
--
ALTER TABLE `genremovie`
  ADD PRIMARY KEY (`MovieId`,`GenreId`),
  ADD KEY `ggg` (`GenreId`);

--
-- Indexes for table `genres`
--
ALTER TABLE `genres`
  ADD PRIMARY KEY (`Id`);

--
-- Indexes for table `marks`
--
ALTER TABLE `marks`
  ADD PRIMARY KEY (`UserId`,`MovieId`),
  ADD KEY `mm` (`MovieId`);

--
-- Indexes for table `media`
--
ALTER TABLE `media`
  ADD PRIMARY KEY (`Id`),
  ADD KEY `aaaaaa` (`ArtistId`),
  ADD KEY `mmmmmm` (`MovieId`);

--
-- Indexes for table `movies`
--
ALTER TABLE `movies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `dd` (`DirectorId`),
  ADD KEY `w` (`WriterId`);

--
-- Indexes for table `rates`
--
ALTER TABLE `rates`
  ADD PRIMARY KEY (`UserId`,`MovieId`),
  ADD KEY `m` (`MovieId`);

--
-- Indexes for table `usergenre`
--
ALTER TABLE `usergenre`
  ADD PRIMARY KEY (`UserId`,`GenreId`),
  ADD KEY `g` (`GenreId`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`Id`),
  ADD UNIQUE KEY `Username` (`Username`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `artist`
--
ALTER TABLE `artist`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=69;

--
-- AUTO_INCREMENT for table `comments`
--
ALTER TABLE `comments`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT for table `genres`
--
ALTER TABLE `genres`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `media`
--
ALTER TABLE `media`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=135;

--
-- AUTO_INCREMENT for table `movies`
--
ALTER TABLE `movies`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `Id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `acts`
--
ALTER TABLE `acts`
  ADD CONSTRAINT `aa` FOREIGN KEY (`ActorId`) REFERENCES `artist` (`Id`),
  ADD CONSTRAINT `mmmm` FOREIGN KEY (`MovieId`) REFERENCES `movies` (`id`);

--
-- Constraints for table `comments`
--
ALTER TABLE `comments`
  ADD CONSTRAINT `mmmmm` FOREIGN KEY (`MovieId`) REFERENCES `movies` (`id`),
  ADD CONSTRAINT `p` FOREIGN KEY (`ParrentId`) REFERENCES `comments` (`Id`),
  ADD CONSTRAINT `uuuuuu` FOREIGN KEY (`UserId`) REFERENCES `users` (`Id`);

--
-- Constraints for table `commentslikes`
--
ALTER TABLE `commentslikes`
  ADD CONSTRAINT `erwr` FOREIGN KEY (`CommentId`) REFERENCES `comments` (`Id`),
  ADD CONSTRAINT `werewqr` FOREIGN KEY (`UserId`) REFERENCES `users` (`Id`);

--
-- Constraints for table `follows`
--
ALTER TABLE `follows`
  ADD CONSTRAINT `a` FOREIGN KEY (`ArtistId`) REFERENCES `artist` (`Id`),
  ADD CONSTRAINT `uuuu` FOREIGN KEY (`UserId`) REFERENCES `users` (`Id`);

--
-- Constraints for table `genremovie`
--
ALTER TABLE `genremovie`
  ADD CONSTRAINT `ggg` FOREIGN KEY (`GenreId`) REFERENCES `genres` (`Id`),
  ADD CONSTRAINT `mmm` FOREIGN KEY (`MovieId`) REFERENCES `movies` (`id`);

--
-- Constraints for table `marks`
--
ALTER TABLE `marks`
  ADD CONSTRAINT `mm` FOREIGN KEY (`MovieId`) REFERENCES `movies` (`id`),
  ADD CONSTRAINT `uuu` FOREIGN KEY (`UserId`) REFERENCES `users` (`Id`);

--
-- Constraints for table `media`
--
ALTER TABLE `media`
  ADD CONSTRAINT `aaaaaa` FOREIGN KEY (`ArtistId`) REFERENCES `artist` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `mmmmmm` FOREIGN KEY (`MovieId`) REFERENCES `movies` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `movies`
--
ALTER TABLE `movies`
  ADD CONSTRAINT `dd` FOREIGN KEY (`DirectorId`) REFERENCES `artist` (`Id`),
  ADD CONSTRAINT `w` FOREIGN KEY (`WriterId`) REFERENCES `artist` (`Id`);

--
-- Constraints for table `rates`
--
ALTER TABLE `rates`
  ADD CONSTRAINT `m` FOREIGN KEY (`MovieId`) REFERENCES `movies` (`id`),
  ADD CONSTRAINT `uu` FOREIGN KEY (`UserId`) REFERENCES `users` (`Id`);

--
-- Constraints for table `usergenre`
--
ALTER TABLE `usergenre`
  ADD CONSTRAINT `g` FOREIGN KEY (`GenreId`) REFERENCES `genres` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `u` FOREIGN KEY (`UserId`) REFERENCES `users` (`Id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
