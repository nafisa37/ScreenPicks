<?php
require_once(__DIR__ . "/../../../partials/nav.php");
is_logged_in(true);
?>
<?php

if (isset($_GET["id"])) {

    $movie_id = $_GET["id"];

    $db = getDB();
    $stmt = $db->prepare("SELECT title, genre, released, synopsis FROM Movies WHERE id = :id");
    $stmt->execute([":id" => $movie_id]);
    $movie = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($movie) {
        $title = $movie["title"];
        $genre = $movie["genre"];
        $released = $movie["released"];
        $synopsis = $movie["synopsis"];
    } else {
        flash("Movie not found", "danger");
    }
}
?>
<script>
    function validate(form) {
        //console.log("hello");

        let released = form.released.value;
        let isValid = true;

        // Regex to match four digits for the release year
        let validYear = /^\d{4}$/;

        if (!validYear.test(released)) {
            flash("[Client] Release year must be a four-digit number", "warning");
            isValid = false;
        }

        return isValid;
    }
</script>

<?php //na569, 4.24.24
if (isset($_POST["save"])) {
    $title = se($_POST, "title", null, false);
    $genre = se($_POST, "genre", null, false);
    $released = se($_POST, "released", null, false);
    $synopsis = se($_POST, "synopsis", null, false);
    $hasError = false;

    if (!preg_match('/^\d{4}$/', $released)) {
        flash("Release year must be a four-digit number", "warning");
        $hasError = true;
    }

    if (!$hasError) {
        $params = [
            ":title" => $title,
            ":genre" => $genre,
            ":released" => $released,
            ":synopsis" => $synopsis,
            ":id" => $movie_id
        ];

        $db = getDB();
        $stmt = $db->prepare("UPDATE Movies SET title = :title, genre = :genre, released = :released, synopsis = :synopsis WHERE id = :id");

        try {
            $stmt->execute($params);
            flash("Movie information updated", "success");
        } catch (PDOException $e) {
            flash("An error occurred while updating movie information", "danger");
        }
    }
}
?>
<div class="container-fluid">
    <form method="POST" onsubmit="return validate(this);">
        <?php render_input(["type" => "text", "id" => "title", "name" => "title", "label" => "Title", "value" => $title, "rules" => ["required" => true]]); ?>
        <?php render_input(["type" => "text", "id" => "genre", "name" => "genre", "label" => "Genre", "value" => $genre, "rules" => ["required" => true]]); ?>
        <?php render_input(["type" => "number", "id" => "released", "name" => "released", "label" => "Release Year", "value" => $released, "rules" => ["required" => true]]); ?>
        <?php render_input(["type" => "text", "id" => "synopsis", "name" => "synopsis", "label" => "Synopsis", "value" => $synopsis, "rules" => ["required" => true]]); ?>
        <?php render_input(["type" => "hidden", "name" => "save"]); ?>
        <?php render_button(["text" => "Update Movie", "type" => "submit"]); ?>
    </form>
</div>

<?php
require_once(__DIR__ . "/../../../partials/flash.php");
?>