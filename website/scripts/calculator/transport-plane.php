<?php

require_once "../../config.php";
require_once "../../functions.php";

$country_src = isset($_GET["country_src"]) ? $_GET["country_src"] : null;
$city_src = isset($_GET["city_src"]) ? $_GET["city_src"] : null;
$country_dst = isset($_GET["country_dst"]) ? $_GET["country_dst"] : null;
$city_dst = isset($_GET["city_dst"]) ? $_GET["city_dst"] : null;
$departure_date = isset($_GET["departure_date"]) ? $_GET["departure_date"] : null;
$return_date = isset($_GET["return_date"]) ? $_GET["return_date"] : null;
$passengers = isset($_GET["passengers"]) ? intval($_GET["passengers"]) : 1;

exec("./plane_cli.py --departure $city_src --arrival $city_dst -p $passengers -dd '$departure_date' -rd '$return_date'", $output);
$output = json_decode($output[0], True);


function orthodromeDistance($departure, $arrival) {
    // returns the distance between two points on the earth in km
    $lat1 = $departure['lat'];
    $lon1 = $departure['lon'];
    $lat2 = $arrival['lat'];
    $lon2 = $arrival['lon'];

    $lat1 = deg2rad($lat1);
    $lon1 = deg2rad($lon1);
    $lat2 = deg2rad($lat2);
    $lon2 = deg2rad($lon2);

    $dlat = $lat2 - $lat1;
    $dlon = $lon2 - $lon1;

    $a = sin($dlat / 2) * sin($dlat / 2) + cos($lat1) * cos($lat2) * sin($dlon / 2) * sin($dlon / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    $earth_radius = 6371;

    $distance = $earth_radius * $c;
    return sprintf("%0.2f km", $distance);
}
?>

<div class="container-scores border-GR">
    <div class="title-calc">
        <img src="assets/icons/plane.svg">
        <p>Avion</p>
    </div>

    <div class="stats-calc">
        <div>
            <h3>Empreinte Carbone</h3>
            <p><?=$output['emissions']?></p>
        </div>
        <div class="trait-small"></div>
        <div>
            <h3>Prix du trajet</h3>
            <p><?=$output['price']?></p>
        </div>
        <div class="trait-small"></div>
        <div>
            <h3>Distance parcourue</h3>
            <p><?= orthodromeDistance(getCityCoordinates($country_src, $city_src), getCityCoordinates($country_dst, $city_dst))?></p>
        </div>
        <div class="trait-small"></div>
        <div>
            <h3>Durée trajet</h3>
            <p><?=$output['duration']?></p>
        </div>
        <div class="trait-small"></div>
        <div>
            <h3>Nombre d'escales</h3>
            <p><?=$output['stops']?></p>
        </div>
    </div>
</div>