<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../../partials/nav.php");

if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: $BASE_PATH" . "/home.php"));
}

// Attempt to apply associations
if (isset($_POST["users"]) && isset($_POST["movies"])) {
    $user_ids = $_POST["users"];
    $movie_ids = $_POST["movies"];
    if (empty($user_ids) || empty($movie_ids)) {
        flash("Both users and movies need to be selected", "warning");
    } else {
        $db = getDB();
        foreach ($user_ids as $uid) {
            foreach ($movie_ids as $mid) {
                // Check if movie in watchlist
                $stmt = $db->prepare("SELECT COUNT(*) FROM UserMovies WHERE user_id = :uid AND movie_id = :mid");
                $stmt->execute([":uid" => $uid, ":mid" => $mid]);
                $count = $stmt->fetchColumn();

                if ($count > 0) {
                    // If movie already in watchlist, delete it
                    $stmt = $db->prepare("DELETE FROM UserMovies WHERE user_id = :uid AND movie_id = :mid");
                    $stmt->execute([":uid" => $uid, ":mid" => $mid]);
                    flash("Movie removed from user's watchlist", "success");
                } else {
                    // If movie not in watchlist, insert it
                    $stmt = $db->prepare("INSERT INTO UserMovies (user_id, movie_id) VALUES (:uid, :mid)");
                    $stmt->execute([":uid" => $uid, ":mid" => $mid]);
                    flash("Movie added to user's watchlist", "success");
                }
            }
        }
    }
}


// Fetch active movies
$active_movies = [];
$db = getDB();
$stmt = $db->prepare("SELECT id, title FROM Movies LIMIT 25");
try {
    $stmt->execute();
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    if ($results) {
        $active_movies = $results;
    }
} catch (PDOException $e) {
    flash(var_export($e->errorInfo, true), "danger");
}

// Search for user by username
$users = [];
$username = "";
if (isset($_POST["username"])) {
    $username = se($_POST, "username", "", false);
    if (!empty($username)) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, username FROM Users WHERE username LIKE :username LIMIT 25");
        try {
            $stmt->execute([":username" => "%$username%"]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($results) {
                $users = $results;
            }
        } catch (PDOException $e) {
            flash(var_export($e->errorInfo, true), "danger");
        }
    } else {
        flash("Username must not be empty", "warning");
    }
}

//Search for movie by title
$movies = [];
$title = "";
if (isset($_POST["title"])) {
    $title = se($_POST, "title", "", false);
    if (!empty($title)) {
        $db = getDB();
        $stmt = $db->prepare("SELECT id, title FROM Movies WHERE title LIKE :title LIMIT 25");
        try {
            $stmt->execute([":title" => "%$title%"]);
            $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
            if ($results) {
                $movies = $results;
            }
        } catch (PDOException $e) {
            flash(var_export($e->errorInfo, true), "danger");
        }
    } else {
        flash("Movie title must not be empty", "warning");
    }
}
?>

<div class="container-fluid">
    <h1>Toggle WatchList Movies</h1>
    <form method="POST">
        <?php render_input(["type" => "search", "name" => "username", "placeholder" => "Username Search", "value" => $username]); ?>
        <?php render_input(["type" => "search", "name" => "title", "placeholder" => "Movie Title Search", "value" => $title]); ?>
        <?php render_button(["text" => "Search", "type" => "submit"]); ?>
    </form>
    <form method="POST">
        <?php if (isset($username) && !empty($username)) : ?>
            <input type="hidden" name="username" value="<?php se($username, false); ?>" />
        <?php endif; ?>
        <table class="table">
            <thead>
                <th>Users</th>
                <th>Movies to Add</th>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <table class="table">
                            <?php foreach ($users as $user) : ?>
                                <tr>
                                    <td>
                                        <?php render_input(["type" => "checkbox", "id" => "user_" . se($user, 'id', "", false), "name" => "users[]", "label" => se($user, "username", "", false), "value" => se($user, 'id', "", false)]); ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </table>
                    </td>
                    <td>
                        <?php foreach ($movies as $movie) : ?>
                            <div>
                                <?php render_input(["type" => "checkbox", "id" => "movie_" . se($movie, 'id', "", false), "name" => "movies[]", "label" => se($movie, "title", "", false), "value" => se($movie, 'id', "", false)]); ?>
                            </div>
                        <?php endforeach; ?>
                    </td>
                </tr>
            </tbody>
        </table>
        <?php render_button(["text" => "Add Movies to Watchlists", "type" => "submit", "color" => "secondary"]); ?>
    </form>
</div>

<?php require_once(__DIR__ . "/../../../partials/flash.php"); ?>
