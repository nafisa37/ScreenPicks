<?php

function fetch_movie($movie) //na569, 4.24.24
{
    $result = [];

    $data = ["function" => "MOVIE_DETAILS", "title" => $movie, "datatype" => "json"];

    $endpoint = "https://ott-details.p.rapidapi.com/search";
    $isRapidAPI = true;
    $rapidAPIHost = "ott-details.p.rapidapi.com";

    $result = get($endpoint, "MOVIE_API_KEY", $data, $isRapidAPI, $rapidAPIHost);

    error_log("Response: " . var_export($result, true));
    if (se($result, "status", 400, false) == 200 && isset($result["response"])) {
        $result = json_decode($result["response"], true);

        if (isset($result["movie_results"])) {
            $result = $result["movie_results"];
        }
    } else {
        $result = [];
    }

    if (isset($result["results"])) {
        $movies = $result["results"];

        foreach ($movies as $index => $movie) {
            foreach ($movie as $key => $value) {
                if (!in_array($key, ['genre', 'title', 'synopsis', 'released'])) {
                    unset($movies[$index][$key]);
                } elseif ($key === 'genre' && is_array($value)) {
                    $movies[$index][$key] = $value[0];
                }
                if (!isset($movies[$index]['synopsis'])) {
                    $movies[$index]['synopsis'] = '';
                }
            }
        }
        $result = $movies;
    } else {
        $result = [];
    }

    return $result;
}
