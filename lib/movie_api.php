<?php

$result = [];

function fetch_movie($movie)
{
    $data = ["function" => "MOVIE_DETAILS", "movie" => $movie, "datatype" => "json"];
    $endpoint = "https://ott-details.p.rapidapi.com/search";
    $isRapidAPI = true;
    $rapidAPIHost = "ott-details.p.rapidapi.com";
    $result = get($endpoint, "MOVIE_API_KEY", $data, $isRapidAPI, $rapidAPIHost);

    error_log("Response: " . var_export($result, true));
    if (se($result, "status", 400, false) == 200 && isset($result["response"])) {
        $result = json_decode($result["response"], true);
        if (isset($result["results"])){
            $result = $result["results"];
        }
    } else {
        $result = [];
    }

    $result = $result["results"];
    var_dump($result);
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

    return $result;
}