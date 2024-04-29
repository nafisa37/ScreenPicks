<?php

require_once(__DIR__ . "/../../../partials/nav.php");
is_logged_in(true);
?>

<div style="text-align: center;">
<div class="container-fluid">
    <h2>All Watchlists</h2>

    <?php
    // Get all users
    $db = getDB();
    $query = "SELECT id, username FROM Users";
    $stmt = $db->prepare($query);
    $stmt->execute();
    $users = $stmt->fetchAll(PDO::FETCH_ASSOC);
    ?>

    <?php foreach ($users as $user) : ?>
        <?php
        $user_id = $user['id'];

        // Get watchlist count for the user that's signed in
        $query = "SELECT COUNT(*) AS total_count FROM UserMovies WHERE user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->execute([":user_id" => $user_id]);
        $total_count = $stmt->fetch(PDO::FETCH_ASSOC)['total_count'];

        // Get watchlist movies for the user that's signed in
        $query = "SELECT m.id, m.title AS Title, m.genre AS Genre, m.released AS Released, m.synopsis AS Synopsis
                  FROM UserMovies um
                  JOIN Movies m ON um.movie_id = m.id
                  WHERE um.user_id = :user_id";
        $stmt = $db->prepare($query);
        $stmt->execute([":user_id" => $user_id]);
        $watchlist = $stmt->fetchAll(PDO::FETCH_ASSOC);
        ?>

        <?php if (!empty($watchlist)) : ?>
            <h3><?php echo htmlspecialchars($user['username']); ?>'s Watchlist (Total Items: <?php echo $total_count; ?>)</h3>

            <table class="table">
                <thead>
                    <tr>
                        <th>Username</th>
                        <th>Title</th>
                        <th>Genre</th>
                        <th>Released</th>
                        <th>Synopsis</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($watchlist as $movie) : ?>
                        <tr>
                            <td>
                            <a href="../profile.php" class="btn btn-secondary"><?php echo htmlspecialchars($user['username']); ?></a>
                            </td>
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
                </tbody>
            </table>
        <?php endif; ?>
    <?php endforeach; ?>
</div>

<?php require_once(__DIR__ . "/../../../partials/flash.php"); ?>
