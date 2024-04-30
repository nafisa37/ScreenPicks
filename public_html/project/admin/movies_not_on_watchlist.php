<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../../partials/nav.php");
//na569, 4.24.24
 if (!has_role("Admin")) {
     flash("You don't have permission to view this page", "warning");
     die(header("Location: $BASE_PATH" . "/home.php"));
 }

$genre = isset($_GET['genre']) ? $_GET['genre'] : '';
$title = isset($_GET['title']) ? $_GET['title'] : '';
$released = isset($_GET['released']) ? $_GET['released'] : '';
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10; // Default to 10
$limit = max(1, min($limit, 100));

$query = "SELECT id, title AS Title, genre as Genre, released as Released, synopsis as Synopsis FROM `Movies`
            WHERE id NOT IN (SELECT movie_id FROM UserMovies)";
$params = [];
if (!empty($genre)) {
    $query .= " AND genre LIKE :genre";
    $params[':genre'] = "%$genre%";
}
if (!empty($title)) {
    $query .= " AND title LIKE :title";
    $params[':title'] = "%$title%";
}
if (!empty($released)) {
    $query .= " AND released LIKE :released";
    $params[':released'] = "%$released%";
}
$query .= " ORDER BY created DESC LIMIT $limit";

$db = getDB();
$stmt = $db->prepare($query);
try {
    $stmt->execute($params);
    $results = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching movies " . var_export($e, true));
    flash("Unhandled error occurred", "danger");
}
//na569, 4.30.24
?>

<script>
//js validation
    let limit = form.limit.value;
    let isValid = true;

    function validate() {
        var limit = document.getElementById('limit').value;
        if (limit < 1 || limit > 100 || isNaN(limit)) { // Check if limit is not between 1 and 100 or not a number
            flash("[Client] Limit must be a number between 1 and 100", "warning");
            isValid = false;
        }

        return isValid;
    }
</script>

<?php
//php validation
if (isset($_GET['limit'])) {
    $limit = intval($_GET['limit']);
    if ($limit < 1 || $limit > 100) {
        flash("Limit must be a number between 1 and 100", "warning");
    }
}
//na569, 4.24.24

//$query = "SELECT COUNT(*) AS total_count FROM UserMovies WHERE user_id = :user_id";
$query = "SELECT COUNT(*) AS total_count FROM Movies WHERE id NOT IN (SELECT DISTINCT movie_id FROM UserMovies )";
//$params = [":user_id" => get_user_id()];

// Execute the query to fetch the count
$db = getDB();
try {
    $stmt = $db->prepare($query);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $total_count = $row['total_count'];
} catch (PDOException $e) {
    error_log("Error fetching total count: " . $e->getMessage());
    flash("An error occurred", "danger");
    $total_count = 0;
}
?>
<!-- Search Form -->
<div style="text-align: center;">
<div class="container-fluid">
    <h2>Movies Not On A WatchList</h2>
        <h3> Total Number of Movies Not On Watchlists: <?php echo $total_count; ?></h3>
        <h4>Total Items On Page: <?php echo count($results); ?></h4>
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

<?php //na569, 4.24.24
$table = ["data" => $results, "title" => "Search Movies", "ignored_columns" => ["id"], "edit_url" => get_url("../edit_movie.php")];
?>
<?php if (empty($results)) : ?>
    <div>No results available.</div>
<?php else : ?>
    <div class="container-fluid">
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
                <?php foreach ($results as $row) : ?>
                    <tr>
                        <td><?php echo htmlspecialchars($row['Title']); ?></td>
                        <td><?php echo htmlspecialchars($row['Genre']); ?></td>
                        <td><?php echo htmlspecialchars($row['Released']); ?></td>
                        <td><?php echo htmlspecialchars($row['Synopsis']); ?></td>
                        <td>
                            <a href="../movie_details.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">View Details</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php endif; ?>

<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../../partials/flash.php");
?>
