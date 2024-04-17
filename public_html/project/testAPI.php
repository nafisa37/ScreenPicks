<?php
require(__DIR__ . "/../../partials/nav.php");

$result = [];
if (isset($_GET["movie"])) {
    //function=GLOBAL_QUOTE&movie=MSFT&datatype=json
    $data = ["function" => "MOVIW_DETAILS", "title" => $_GET["movie"], "datatype" => "json"];
    //$endpoint = "https://ott-details.p.rapidapi.com/advancedsearch";
    $endpoint = "https://ott-details.p.rapidapi.com/search";
    //$endpoint = "https://ott-details.p.rapidapi.com/advancedsearch?start_year=1970&end_year=2020&min_imdb=6&max_imdb=7.8&genre=action&language=english&type=movie&sort=latest&page=1";
    $isRapidAPI = true;
    $rapidAPIHost = "ott-details.p.rapidapi.com";
    $result = get($endpoint, "MOVIE_API_KEY", $data, $isRapidAPI, $rapidAPIHost);
    //example of cached data to save the quotas, don't forget to comment out the get() if using the cached data for testing
//      $result = ["status" => 200, "response" => '{
//     "array": {
//         "genre": "Action",
//         "title": "Mortal Kombat",
//         "released": "1995",
//         "synopsis": "Three unknowing martial artists are summoned to a mysterious island to compete in a tournament whose outcome will decide the f
// ate of the world.",
//     }
// }'];
    error_log("Response: " . var_export($result, true));
    if (se($result, "status", 400, false) == 200 && isset($result["response"])) {
        $result = json_decode($result["response"], true);
        if (isset($result["movie_results"])){
            $result = $result["movie_results"];
        }
    } else {
        $result = [];
    }

$result = $result["results"];
//var_dump($result);
foreach ($result as $index => $movie) {
    foreach ($movie as $key => $value) {
        if (!in_array($key, ['genre', 'title', 'synopsis', 'released'])) {
            unset($result[$index][$key]);
        } 
        elseif ($key === 'genre' && is_array($value)) {
            $result[$index][$key] = $value[0];
        }
        if (!isset($result[$index]['synopsis'])) {
        $result[$index]['synopsis'] = '';
    }
    }
}

//var_dump($result);
$db = getDB();
$opts = ["debug" => true, "update_duplicate" => false, "columns_to_update"=>[]];
var_dump($result);
$query = insert("Movies", $result, $opts);
var_export($query);
}

// $query = "INSERT INTO `MOVIES` ";
// $columns = [];
// $params = [];
// foreach ($result as $k=>$v){
//     //array_push($columns,"`$k`");
//     $params[":$k"] = $v;
// }
// $query .= "(" . join(",", $columns) . ")";
// $query .= "VALUES (" . join(",", array_keys($params)) . ")";
// // var_export($query);
// print_r($query);
// try{
//     $stmt = $db->prepare($query);
//     $stmt->execute($params);
//     //$params = $stmt->fetchAll();
//     flash("Inserted record", "success");
// }
// catch(PDOException $e){
//     error_log("Something broke with the query" . var_export($e,true));
// }

?>
<div class="container-fluid">
    <h1>Movie Info</h1>
    <p>Remember, we typically won't be frequently calling live data from our API, this is merely a quick sample. We'll want to cache data in our DB to save on API quota.</p>
    <form>
        <div>
            <label>Movie</label>
            <input name="movie" />
            <input type="submit" value="Fetch Movie" />
        </div>
    </form>
    <div class="row ">
        <?php if (isset($result)) : ?>
            <?php foreach ($result as $movie) : ?>
                <pre>
                    <?php var_export($movie);?>
                </pre>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>
<?php
require(__DIR__ . "/../../partials/flash.php");