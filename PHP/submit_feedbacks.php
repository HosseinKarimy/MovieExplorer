<?php
session_start();
require 'database.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'message' => 'ابتدا وارد شوید.']);
    exit;
}

$userId = $_SESSION['user_id'];
$movieId = $_POST['movie_id'] ?? null;
$type = $_POST['type'] ?? null; // نوع عملیات: comment یا rate

if (!$type) {
    echo json_encode(['success' => false, 'message' => 'اطلاعات ناقص است.']);
    exit;
}

if ($type === 'comment') {
    $comment = $_POST['comment'] ?? null;
    if (!$comment) {
        echo json_encode(['success' => false, 'message' => 'متن نظر نباید خالی باشد.']);
        exit;
    }
    $success = saveComment($db, $userId, $movieId, $comment);
    echo json_encode(['success' => $success, 'message' => $success ? 'نظر شما ثبت شد.' : 'خطایی رخ داد.']);
} elseif ($type === 'rate') {
    $rating = $_POST['rating'] ?? null;
    if (!$rating || $rating < 1 || $rating > 10) {
        echo json_encode(['success' => false, 'message' => 'امتیاز باید بین ۱ تا ۱۰ باشد.']);
        exit;
    }
    $success = saveRating($db, $userId, $movieId, $rating);
    echo json_encode(['success' => $success, 'message' => $success ? 'امتیاز شما ثبت شد.' : 'خطایی رخ داد.']);
}


function saveComment($db, $userId, $movieId, $comment) {
    $query = "INSERT INTO comments (UserId, MovieId, Comment) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("iis", $userId, $movieId, $comment);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}



function saveRating($db, $userId, $movieId, $rating) {
    $query = "INSERT INTO rates (UserId, MovieId, Rate) VALUES (?, ?, ?)";
    $stmt = $db->prepare($query);
    $stmt->bind_param("iii", $userId, $movieId, $rating);
    $result = $stmt->execute();
    $stmt->close();
    return $result;
}


if ($type === 'like') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['comment_id'])) {
        $userId = $_SESSION['user_id']; // فرض بر این است که شناسه کاربر در سشن ذخیره شده است
        $commentId = $_POST['comment_id'];

        $query = "INSERT INTO commentslikes (UserId, CommentId) VALUES (?, ?)";

        $stmt = $db->prepare($query);
        $stmt->bind_param("ii", $userId, $commentId);

        try {
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'لایک شما ثبت شد!']);
        } catch (Exception $e) {
            if ($db->errno === 1062) { // Duplicate entry
                echo json_encode(['success' => false, 'message' => 'شما قبلاً این کامنت را لایک کرده‌اید.']);
            } else {
                echo json_encode(['success' => false, 'message' => 'خطایی رخ داده است.']);
            }
        }
    }
}

if ($type === 'reply') {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['parent_id'], $_POST['comment'])) {
        $userId = $_SESSION['user_id'];
        $movieId = $_POST['movie_id'];
        $parentId = $_POST['parent_id'];
        $comment = $_POST['comment'];

        $query = "INSERT INTO comments (UserId, MovieId, Comment, ParrentId) VALUES (?, ?, ?, ?)";
        $stmt = $db->prepare($query);
        $stmt->bind_param("iisi", $userId, $movieId, $comment, $parentId);

        try {
            $stmt->execute();
            echo json_encode(['success' => true, 'message' => 'پاسخ شما ثبت شد!']);
        } catch (Exception $e) {
            echo json_encode(['success' => false, 'message' => 'خطایی رخ داده است.']);
        }
    }
}
