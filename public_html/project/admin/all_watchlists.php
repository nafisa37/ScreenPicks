<?php
require_once(__DIR__ . "/../../../partials/nav.php");
require_once(__DIR__ . "/../../../lib/functions.php");

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["remove_associations"])) {
    $username = isset($_GET['username']) ? $_GET['username'] : '';

    $db = getDB();
    $query = "DELETE FROM UserMovies WHERE user_id IN (SELECT id FROM Users WHERE username = :username)";
    $stmt = $db->prepare($query);
    try {
        $stmt->execute([':username' => $username]);
        flash("Associations for user '$username' cleared successfully", "success");
        die(header("Location: " . get_url("watchlist.php")));
        exit;
    } catch (PDOException $e) {
        error_log("Error removing associations for user '$username': " . $e->getMessage());
        flash("An error occurred while removing associations", "danger");
    }
}


$username = isset($_GET['username']) ? $_GET['username'] : '';
$genre = isset($_GET['genre']) ? $_GET['genre'] : '';
$title = isset($_GET['title']) ? $_GET['title'] : '';
$released = isset($_GET['released']) ? $_GET['released'] : '';
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10; // Default to 10
$limit = max(1, min($limit, 100));

$query = "SELECT m.id, m.title AS Title, m.genre AS Genre, m.released AS Released, m.synopsis AS Synopsis,
                 COUNT(DISTINCT um.user_id) AS Watchlist_Count, u.username AS Username
          FROM UserMovies um
          JOIN Movies m ON um.movie_id = m.id
          JOIN Users u ON um.user_id = u.id
          WHERE 1=1";
$params = [];
if (!empty($username)) {
    $query .= " AND u.username LIKE :username";
    $params[':username'] = "%$username%";
}
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
$query .= " GROUP BY m.id, m.title, m.genre, m.released, m.synopsis, u.id, u.username
            ORDER BY m.created DESC LIMIT $limit";

// Execute the query, na569, 4.30.24
$db = getDB();
$stmt = $db->prepare($query);
try {
    $stmt->execute($params);
    $results = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching movies: " . $e->getMessage());
    flash("An error occurred while fetching movies", "danger");
}

$totalItemsQuery = "SELECT COUNT(*) AS total_items FROM UserMovies";
$stmt = $db->prepare($totalItemsQuery);
$stmt->execute();
$row = $stmt->fetch(PDO::FETCH_ASSOC);
$totalItemsCount = $row['total_items'];

?>

<div style="text-align: center;">
    <div class="container-fluid">
        <h2>All Watchlists</h2>
        <h3> Total Number of Movies On Watchlists: <?php echo $totalItemsCount; ?></h3>
        <h4>Total Items On Page: <?php echo count($results); ?></h4>
        <form method="GET">
            <div>
                <label for="username">Username:</label>
                <input type="text" id="username" name="username" value="<?php echo htmlspecialchars($username); ?>">
            </div>
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
                    <th>Username</th>
                    <th>Users Watching This Movie</th>
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
                        <td colspan="7">No results available.</td>
                    </tr>
                <?php else : ?>
                    <?php foreach ($results as $movie) : ?>
                        <tr>
                            <td>
                                <a href="../profile.php" class="btn btn-secondary"><?php echo htmlspecialchars($movie['Username']); ?></a>
                            </td>
                            <td><?php echo $movie['Watchlist_Count']; ?></td>
                            <td><?php echo htmlspecialchars($movie['Title']); ?></td>
                            <td><?php echo htmlspecialchars($movie['Genre']); ?></td>
                            <td><?php echo htmlspecialchars($movie['Released']); ?></td>
                            <td><?php echo htmlspecialchars($movie['Synopsis']); ?></td>
                            <td>
                                <a href="../movie_details.php?id=<?php echo $movie['id']; ?>" class="btn btn-secondary">View Details</a>
                                <a href="../delete_association.php?id=<?php echo $movie['id']; ?>" class="btn btn-secondary">Delete from Watchlist</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <form method="post">
        <button type="submit" name="remove_associations" class="btn btn-secondary">Clear Associations</button>
    </form>

    <?php require_once(__DIR__ . "/../../../partials/flash.php"); ?>
