<?php
require_once(__DIR__ . "/../../partials/nav.php");
require_once(__DIR__ . "/../../lib/functions.php");

// Check user's role
// if (!has_role("Admin")) {
//     flash("You don't have permission to view this page", "warning");
//     die(header("Location: $BASE_PATH" . "/home.php"));
// }

$user_id = get_user_id();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["clear_watchlist"])) {
    $db = getDB();
    $query = "DELETE FROM UserMovies WHERE user_id = :user_id";
    $stmt = $db->prepare($query);
    try {
        $stmt->execute([":user_id" => $user_id]);
        flash("Watchlist cleared successfully", "success");
        // Redirect to refresh the page
        die(header("Location: " . get_url("admin/watchlist.php")));
        exit;
    } catch (PDOException $e) {
        error_log("Error clearing watchlist: " . $e->getMessage());
        flash("An error occurred while clearing watchlist", "danger");
    }
}

$genre = isset($_GET['genre']) ? $_GET['genre'] : '';
$title = isset($_GET['title']) ? $_GET['title'] : '';
$released = isset($_GET['released']) ? $_GET['released'] : '';
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10; // Default to 10
$limit = max(1, min($limit, 100));

$query = "SELECT m.id, m.title AS Title, m.genre AS Genre, m.released AS Released, m.synopsis AS Synopsis
          FROM UserMovies um
          JOIN Movies m ON um.movie_id = m.id
          WHERE um.user_id = :user_id";
$params = [":user_id" => $user_id];
if (!empty($genre)) {
    $query .= " AND m.genre LIKE :genre";
    $params[':genre'] = "%$genre%";
}
if (!empty($title)) {
    $query .= " AND m.title LIKE :title";
    $params[':title'] = "%$title%";
}
if (!empty($released)) {
    $query .= " AND m.released LIKE :released";
    $params[':released'] = "%$released%";
}
$query .= " ORDER BY m.created DESC LIMIT $limit";

// Execute the query
$db = getDB();
$stmt = $db->prepare($query);
try {
    $stmt->execute($params);
    $results = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching movies: " . $e->getMessage());
    flash("An error occurred while fetching movies", "danger");
}

$query = "SELECT COUNT(*) AS total_count FROM UserMovies WHERE user_id = :user_id";
$params = [":user_id" => $user_id];

// Execute the query to fetch the count
$db = getDB();
try {
    $stmt = $db->prepare($query);
    $stmt->execute($params);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_count = $row['total_count'];
} catch (PDOException $e) {
    error_log("Error fetching total count: " . $e->getMessage());
    flash("An error occurred while fetching total count", "danger");
    $total_count = 0;
}

?>

<div style="text-align: center;">
<div class="container-fluid">
    <h2>My Watchlist (Total Items: <?php echo $total_count; ?>)</h2>
    <form method="GET">
        <div>
            <label for="genre">Genre:</label>
            <input type="text" id="genre" name="genre" value="<?php echo htmlspecialchars($genre); ?>">
        </div>
        <div>
            <label for="released">Release Date:</label>
            <input type="text" id="released" name="released" value="<?php echo htmlspecialchars($released); ?>">
        </div>
        <div>
            <label for="title">Title:</label>
            <input type="text" id="title" name="title" value="<?php echo htmlspecialchars($title); ?>">
        </div>
        <div>
            <label for="limit">Limit:</label>
            <input type="number" id="limit" name="limit" value="<?php echo $limit; ?>" min="1" max="100">
        </div>
        <button type="submit">Search</button>
    </form>

    <table class="table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Genre</th>
                <th>Released</th>
                <th>Synopsis</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (empty($results)) : ?>
                <tr>
                    <td colspan="5">No results available.</td>
                </tr>
            <?php else : ?>
                <?php foreach ($results as $movie) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($movie['Title']); ?></td>
                        <td><?php echo htmlspecialchars($movie['Genre']); ?></td>
                        <td><?php echo htmlspecialchars($movie['Released']); ?></td>
                        <td><?php echo htmlspecialchars($movie['Synopsis']); ?></td>
                        <td>
                            <a href="movie_details.php?id=<?php echo $movie['id']; ?>" class="btn btn-secondary">View Details</a>
                            <a href="delete_association.php?id=<?php echo $movie['id']; ?>" class="btn btn-secondary">Delete from Watchlist</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

    <form method="post">
        <button type="submit" name="clear_watchlist" class="btn btn-secondary">Clear Watchlist</button>
    </form>

<?php require_once(__DIR__ . "/../../partials/flash.php"); ?>
