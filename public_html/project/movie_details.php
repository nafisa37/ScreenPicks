<?php
require_once(__DIR__ . "/../../partials/nav.php");
is_logged_in(true);

//require(__DIR__ . "/../../../lib/db.php");

//na569,4.24.24
$movieDetails = null;
$movieId = isset($_GET['id']) ? intval($_GET['id']) : null;

// Validate the movie ID
if ($movieId === null || $movieId <= 0) {
    flash("Invalid movie ID", "danger");
    header("Location: list_movies.php");
    exit();
} else {
    
    $db = getDB();
    $stmt = $db->prepare("SELECT * FROM Movies WHERE id = :id");
    $stmt->bindParam(':id', $movieId);
    $stmt->execute();

    $movieDetails = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($movieDetails === false) {
        flash("Movie not found", "danger");
        header("Location: list_movies.php");
        exit();
    }
}
//na569, 4.24.24
echo '<div style="text-align: center;">';
echo "<h2>{$movieDetails['title']}</h2>";
echo "<p><strong>Genre:</strong> {$movieDetails['genre']} </p>";
echo "<p><strong>Released:</strong> {$movieDetails['released']}</p>";
echo "<p><strong>Synopsis:</strong> {$movieDetails['synopsis']}</p>";

echo '<a href="edit_movie.php?id=' . $movieId . '" class="btn btn-secondary" style="margin-left: 10px;">Edit Movie</a>';
echo '<a href="delete_movie.php?id=' . $movieId . '" class="btn btn-secondary" style="margin-left: 10px;">Delete Movie</a>';
echo '</div>';
?>
