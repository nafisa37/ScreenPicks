<?php
require_once(__DIR__ . "/../../../partials/nav.php");
is_logged_in(true);
?>

<?php

//require(__DIR__ . "/../../../lib/db.php");

$movieId = isset($_GET['id']) ? intval($_GET['id']) : null;

if ($movieId !== null) {
    
    $db = getDB();//this is accessible via the db.php require above

    $stmt = $db->prepare("SELECT * FROM Movies WHERE id = :id");
    $stmt->bindParam(':id', $movieId);
    $stmt->execute();

    $movieDetails = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($movieDetails !== false) {

        echo '<div style="text-align: center;">';
        echo "<h2>{$movieDetails['title']}</h2>";
        echo "<p><strong>Genre:</strong> {$movieDetails['genre']} </p>";
        echo "<p><strong>Released:</strong> {$movieDetails['released']}</p>";
        echo "<p><strong>Synopsis:</strong> {$movieDetails['synopsis']}</p>";

        echo '<a href="edit_movie.php?id=' . $movieId . '" class="btn btn-secondary" style="margin-left: 10px;">Edit Movie</a>';
        echo '<a href="delete_movie.php?id=' . $movieId . '" class="btn btn-secondary" style="margin-left: 10px;">Delete Movie</a>';

        //put them all in div elements to space out the buttons vertically stacked

    } else {
        echo "<p><strong>Movie not found</p>";
    }
} else {
    echo "<p><strong>Invalid movie ID</p>";
}
?>