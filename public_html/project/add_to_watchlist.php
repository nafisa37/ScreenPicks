<?php

session_start();
require(__DIR__ . "/../../lib/functions.php");
// if (!has_role("Admin")) {
//     flash("You don't have permission to view this page", "warning");
//     die(header("Location: $BASE_PATH" . "/home.php"));
// }

$id = se($_GET, "id", -1, false);
if ($id < 1) {
    flash("Invalid id passed to add to watchlist", "danger");
    die(header("Location: " . get_url("list_movies.php")));
}

// if (isset($_SESSION['user_id'])) {
//     $user_id = $_SESSION['user_id'];
// }

$db = getDB();
$query = "INSERT INTO `UserMovies` (`user_id`, `movie_id`) VALUES (:user_id, :movie_id)";
try {
    $stmt = $db->prepare($query);
    $stmt->execute([
        ":user_id" => get_user_id(),
        ":movie_id" => $id
    ]);
    flash("Added movie to watchlist", "success");
} catch (Exception $e) {
    error_log("Error adding movie $id to watchlist: " . var_export($e, true));
    flash("Error adding movie to watchlist", "danger");
}

if (isset($_SERVER['HTTP_REFERER'])) {
    $previousPage = $_SERVER['HTTP_REFERER'];
} else {
    $previousPage = get_url("admin/list_movies.php");
}

if (!empty($_SESSION['filter'])) {
    $filterParams = http_build_query($_SESSION['filter']);
    if (strpos($previousPage, '?') !== false) {
        $separator = '&';
    } else {
        $separator = '?';
    }
    $previousPage .= $separator . $filterParams;
}

die(header("Location: " . $previousPage));
