<?php

session_start();
require(__DIR__ . "/../../lib/functions.php");
// if (!has_role("Admin")) {
//     flash("You don't have permission to view this page", "warning");
//     die(header("Location: $BASE_PATH" . "/home.php"));
// }
//na569, 4/24/24
$id = se($_GET, "id", -1, false);
if ($id < 1) {
    flash("Invalid id passed to delete", "danger");
    die(header("Location: " . get_url("../watchlist.php")));
}

$db = getDB();
$query = "DELETE FROM `UserMovies` WHERE movie_id = :id";
try {
    $stmt = $db->prepare($query);
    $stmt->execute([":id" => $id]);
    flash("Deleted record with id $id", "success");
} catch (Exception $e) {
    error_log("Error deleting movie $id" . var_export($e, true));
    flash("Error deleting record", "danger");
}

redirect("watchlist.php");

// if (isset($_SERVER['HTTP_REFERER'])) {
//     $previousPage = $_SERVER['HTTP_REFERER'];
// } else {
//     $previousPage = get_url("admin/watchlist.php");
// }

// if (!empty($_SESSION['filter'])) {
//     $filterParams = http_build_query($_SESSION['filter']);
//     if (strpos($previousPage, '?') !== false) {
//         $separator = '&';
//     } else {
//         $separator = '?';
//     }
//     $previousPage .= $separator . $filterParams;
// }

// die(header("Location: " . $previousPage));
// //die(header("Location: " . get_url("admin/list_movies.php")));