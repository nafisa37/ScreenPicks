<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../../partials/nav.php");

if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: $BASE_PATH" . "/home.php"));
}

// Retrieve search parameters from GET request
$genre = isset($_GET['genre']) ? $_GET['genre'] : '';
$title = isset($_GET['title']) ? $_GET['title'] : '';
$released = isset($_GET['released']) ? $_GET['released'] : '';
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10; // Default to 10 if not provided or invalid
// Ensure the limit is within the specified range
$limit = max(1, min($limit, 100));

// Construct dynamic SQL query based on search parameters
$query = "SELECT id, title AS Title, genre as Genre, released as Released, synopsis as Synopsis FROM `Movies` WHERE 1=1";
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

// Execute the query with parameters
$db = getDB();
$stmt = $db->prepare($query);
try {
    $stmt->execute($params);
    $results = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Error fetching movies " . var_export($e, true));
    flash("Unhandled error occurred", "danger");
}

?>

<!-- Search Form -->
<div style="text-align: center;">
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

<?php
$table = ["data" => $results, "title" => "Search Movies", "ignored_columns" => ["id"], "edit_url" => get_url("admin/edit_movie.php")];

$genre = isset($_GET['genre']) ? $_GET['genre'] : '';
$released = isset($_GET['released']) ? $_GET['released'] : '';
$title = isset($_GET['title']) ? $_GET['title'] : '';
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 10;

$_SESSION['filter'] = [
    'genre' => $genre,
    'released' => $released,
    'title' => $title,
    'limit' => $limit
];
?>
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
                        <a href="movie_details.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">View Details</a>
                        <a href="edit_movie.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Edit</a>
                        <a href="delete_movie.php?id=<?php echo $row['id']; ?>" class="btn btn-secondary">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../../partials/flash.php");
?>
