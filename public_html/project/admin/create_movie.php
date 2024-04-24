<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../../partials/nav.php");
//require_once(__DIR__ . "/../../../lib/movie_api.php");


if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: $BASE_PATH" . "/home.php"));
}
?>

<?php

//TODO handle movie fetch
if (isset($_POST["action"])) {
    $action = $_POST["action"];
    $movie =  strtoupper(se($_POST, "title", "", false));
    $quote = [];
    $uniqueMovie = [];
    if ($movie) {
        if ($action === "fetch") {
            $result = fetch_movie($movie);
            error_log("Data from API" . var_export($result, true));
            if ($result) {
                foreach ($result as $movie) {
                    $title = $movie['title'];
                    if (!in_array($title, $uniqueMovie)) {
                        $quote = $movie;
                        //$quote["is_api"] = 0; // Set is_api to 0 when fetching from API
                        $uniqueMovie[] = $title; // Add the movie title to the list of unique titles
                    }
                }
            }
        } else if ($action === "create") {
            foreach ($_POST as $k => $v) {
                if (!in_array($k, ["title", "genre", "released", "synopsis"])) {
                    unset($_POST[$k]);
                }
                $quote = $_POST;
                $quote["is_api"] = 0;
                error_log("Cleaned up POST: " . var_export($quote, true));
            }
        }
    } else {
        flash("You must provide a movie", "warning");
    }

    //insert data
    $db = getDB();
    $query = "INSERT INTO `Movies` ";
    $columns = [];
    $params = [];
    //per record
    foreach ($quote as $k => $v) {
        array_push($columns, "`$k`");
        $params[":$k"] = $v;
    }
    $query .= "(" . join(",", $columns) . ")";
    $query .= "VALUES (" . join(",", array_keys($params)) . ")";
    error_log("Query: " . $query);
    error_log("Params: " . var_export($params, true));
    try {
        $stmt = $db->prepare($query);
        $stmt->execute($params);
        flash("Inserted record " . $db->lastInsertId(), "success");
    } catch (PDOException $e) {
        if ($e->errorInfo[1] === 1062) {
            flash("A movie with the same title already exists", "warning");
        }
        error_log("Something broke with the query" . var_export($e, true));
        flash("An error occurred", "danger");
    }
}

//TODO handle manual create movie
?>


<div class="container-fluid">
    <h3>Fetch or Create Movie</h3>
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a class="nav-link bg-success" href="#" onclick="switchTab('fetch')">Create</a>
        </li>
        <li class="nav-item">
            <a class="nav-link bg-success" href="#" onclick="switchTab('create')">Fetch</a>
        </li>
    </ul>
    <div id="fetch" class="tab-target">
        <form method="POST">
            <?php render_input(["type" => "search", "name" => "title", "placeholder" => "Search Movie Title", "rules" => ["required" => "required"]]); ?>
            <?php render_input(["type" => "hidden", "name" => "action", "value" => "fetch"]); ?>
            <?php render_button(["text" => "Search", "type" => "submit",]); ?>
        </form>
    </div>
    <div id="create" style="display: none;" class="tab-target">
        <form onsubmit="return validate(this)" method="POST">

            <?php render_input(["type" => "text", "name" => "title", "placeholder" => "Movie Title", "label" => "Enter a Movie Title", "rules" => ["required" => "required"]]); ?>
            <?php render_input(["type" => "text", "name" => "genre", "placeholder" => "Genre", "label" => "Enter a Genre", "rules" => ["required" => "required"]]); ?>
            <?php render_input(["type" => "number", "name" => "released", "placeholder" => "Release Date", "label" => "Enter a Year", "rules" => ["required" => "required"]]); ?>
            <?php render_input(["type" => "text", "name" => "synopsis", "placeholder" => "Synopsis", "label" => "Enter a Synopsis", "rules" => ["required" => "required"]]); ?>

            <?php render_input(["type" => "hidden", "name" => "action", "value" => "create"]); ?>
            <?php render_button(["text" => "Search", "type" => "submit", "text" => "Create"]); ?>
        </form>
    </div>
</div>
<script>
    function switchTab(tab) {
        let target = document.getElementById(tab);
        if (target) {
            let eles = document.getElementsByClassName("tab-target");
            for (let ele of eles) {
                ele.style.display = (ele.id === tab) ? "none" : "block";
            }
        }
    }
</script>

<script>
    function validate(form) {
        //console.log("hello");

        let title = form.title.value;
        let genre = form.genre.value;
        let released = form.released.value;
        let synopsis = form.synopsis.value;
        let isValid = true;

        // Regex to match four digits for the release year
        let validYear = /^\d{4}$/;

        if (title.length < 1) {
            flash("[Client] Title cannot be empty", "warning");
            isValid = false;
        }
        if (genre.length < 1) {
            flash("[Client] Genre cannot be empty", "warning");
            isValid = false;
        }
        if (released.length < 1) {
            flash("[Client] Release year cannot be empty", "warning");
            isValid = false;
        }
        if (!validYear.test(released)) {
            flash("[Client] Release year must be a four-digit number", "warning");
            isValid = false;
        }
        if (synopsis.length < 1) {
            flash("[Client] Synopsis cannot be empty", "warning");
            isValid = false;
        }

        return isValid;
    }
</script>
<?php
if (isset($_POST["save"])) {
    $title = se($_POST, "title", null, false);
    $genre = se($_POST, "genre", null, false);
    $released = se($_POST, "released", null, false);
    $synopsis = se($_POST, "synopsis", null, false);
    $hasError = false;

    if (empty($title)) {
        flash("Title is required", "danger");
        $hasError = true;
    }

    if (empty($genre)) {
        flash("Genre is required", "danger");
        $hasError = true;
    }

    if (empty($released) || !is_numeric($released) || strlen($released) !== 4) {
        flash("Invalid release year", "danger");
        $hasError = true;
    }

    if (empty($synopsis)) {
        flash("Synopsis is required", "danger");
        $hasError = true;
    }

    if (!$hasError) {
        $db = getDB();
        $stmt = $db->prepare("INSERT INTO Movies (title, genre, released, synopsis) VALUES (:title, :genre, :released, :synopsis)");
        try {
            $stmt->execute([":title" => $title, ":genre" => $genre, ":released" => $released, ":synopsis" => $synopsis]);
            flash("Successfully recorded!", "success");
        } catch (PDOException $e) {
            //users_check_duplicate($e->errorInfo);
        }
    }
}

?>

<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../../partials/flash.php");
?>