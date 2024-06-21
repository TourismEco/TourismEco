<?php

require_once "../../config.php";
require_once "../../functions.php";

$country_src = isset($_GET["country_src"]) ? $_GET["country_src"] : null;
$city_src = isset($_GET["city_src"]) ? $_GET["city_src"] : null;
$country_dst = isset($_GET["country_dst"]) ? $_GET["country_dst"] : null;
$city_dst = isset($_GET["city_dst"]) ? $_GET["city_dst"] : null;
$departure_date = isset($_GET["departure_date"])
    ? $_GET["departure_date"]
    : null;
$passengers = isset($_GET["passengers"]) ? intval($_GET["passengers"]) : 1;

putenv("PATH=/var/www/html/php/venv/bin:/usr/lib/anaconda3/bin:/usr/lib/anaconda3/condabin:/usr/local/sbin:/usr/local/bin:/usr/sbin:/usr/bin:/sbin:/bin:/usr/games:/usr/local/games:/snap/bin");
$command = "/var/www/html/php/venv/bin/google-flights --origin '$city_src' --destination '$city_dst' -p '$passengers' -dd '$departure_date' 2>&1";
exec($command, $output, $result_code);

$output = $output ? json_decode($output[0], true) : null;

$best_flight = $output["meilleurs_vols"][0];

function orthodromeDistance($departure_iata, $arrival_iata)
{
    $departure = getAirportCoordinates($departure_iata);
    $arrival = getAirportCoordinates($arrival_iata);
    // returns the distance between two points on the earth in km
    $lat1 = $departure["lat"];
    $lon1 = $departure["lon"];
    $lat2 = $arrival["lat"];
    $lon2 = $arrival["lon"];

    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    $dlat = $lat2 - $lat1;
    $dlon = $lon2 - $lon1;

    $a =
        sin($dlat / 2) * sin($dlat / 2) +
        cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    $earth_radius = 6371;

    $distance = $earth_radius * $c;
    return sprintf("%0.2f km", $distance);
}

if ($output) {
    echo <<<HTML
<div class="container-scores border-GR">
    <div class="title-calc">
        <img src="assets/icons/plane.svg">
        <p>Avion</p>
    </div>

    <div class="stats-calc">
        <div>
            <h3>Empreinte Carbone</h3>
            <p>$best_flight[emissions]</p>
        </div>
        <div class="trait-small"></div>
        <div>
            <h3>Prix du trajet</h3>
            <p>$best_flight[price]</p>
        </div>
        <div class="trait-small"></div>
        <div>
            <h3>Distance parcourue</h3>
            <p>
HTML;
    $total_distance = 0;
    for ($i = 0; $i < sizeof($best_flight["airports"]) - 1; $i++) {
        $total_distance += intval(
            orthodromeDistance(
                $best_flight["airports"][$i],
                $best_flight["airports"][$i + 1]
            )
        );
    }
    echo <<<HTML
            $total_distance km</p>
        </div>
        <div class="trait-small"></div>
        <div>
            <h3>Durée trajet</h3>
            <p>$best_flight[duration]</p>
        </div>
        <div class="trait-small"></div>
        <div>
            <h3>Nombre d'escales</h3>
            <p>$best_flight[stops]</p>
        </div>
    </div>
</div>
HTML;
} else {
    echo <<<HTML
<div class="container-scores border-GR">
    <div class="title-calc">
        <img src="assets/icons/plane.svg">
        <p>Avion</p>
    </div>

    <div class="stats-calc">
        Aucun itinéraire trouvé
    </div>
</div>
HTML;
}
