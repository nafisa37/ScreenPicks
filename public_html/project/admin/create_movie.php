<?php
//note we need to go up 1 more directory
require(__DIR__ . "/../../../partials/nav.php");

if (!has_role("Admin")) {
    flash("You don't have permission to view this page", "warning");
    die(header("Location: $BASE_PATH" . "/home.php"));
}
?>

<?php

//TODO handle movie fetch
if (isset($_POST["action"])) {
    $action = $_POST["action"];
    $movie =  strtoupper(se($_POST, "movie", "", false));
    $quote = [];
    if ($movie) {
        if ($action === "fetch") {
            $result = fetch_quote($movie);
            error_log("Data from API" . var_export($result, true));
            if ($result) {
                $quote = $result;
            }
        } else if ($action === "create") {
            foreach ($_POST as $k => $v) {
                if (!in_array($k, ["title", "genre", "released", "synopsis"])) {
                    unset($_POST[$k]);
                }
                $quote = $_POST;
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
            <a class="nav-link bg-success" href="#" onclick="switchTab('create')">Fetch</a>
        </li>
        <li class="nav-item">
            <a class="nav-link bg-success" href="#" onclick="switchTab('fetch')">Create</a>
        </li>
    </ul>
    <div id="fetch" class="tab-target">
        <form method="POST">
            <?php render_input(["type" => "search", "name" => "movie", "placeholder" => "Search Movie Title", "rules" => ["required" => "required"]]); ?>
            <?php render_input(["type" => "hidden", "name" => "action", "value" => "fetch"]); ?>
            <?php render_button(["text" => "Search", "type" => "submit",]); ?>
        </form>
    </div>
    <div id="create" style="display: none;" class="tab-target">
        <form method="POST">

            <?php render_input(["type" => "text", "name" => "movie", "placeholder" => "Movie Title", "label" => "Enter a Movie Title", "rules" => ["required" => "required"]]); ?>
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

<?php
//note we need to go up 1 more directory
require_once(__DIR__ . "/../../../partials/flash.php");
?>