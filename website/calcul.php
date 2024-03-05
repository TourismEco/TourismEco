<?php

require_once "functions.php";
require_once "transport.php";

// CSRF protection
// if (!isset($_GET["csrf"]) || $_GET["csrf"] !== $_SESSION["csrf"]) {
//     die("CSRF token validation failed");
// }

// get the data from the POST request
$country_src = isset($_GET["country_src"]) ? $_GET["country_src"] : null;
$city_src = isset($_GET["city_src"]) ? $_GET["city_src"] : null;
$country_dst = isset($_GET["country_dst"]) ? $_GET["country_dst"] : null;
$city_dst = isset($_GET["city_dst"]) ? $_GET["city_dst"] : null;
$airport_src = isset($_GET["airport_src"]) ? $_GET["airport_src"] : null;
$airport_dst = isset($_GET["airport_dst"]) ? $_GET["airport_dst"] : null;
$mode = isset($_GET["mode"]) ? $_GET["mode"] : null;
$model = isset($_GET["model"]) ? $_GET["model"] : null;
$duration = isset($_GET["duration"]) ? $_GET["duration"] : null;
$passengers = isset($_GET["passengers"]) ? $_GET["passengers"] : null;

// main
// default VALUES but need to implement the possibility to choose a certain car
switch ($mode) {
    case "PLANE":
        // TO-DO: implement a function to check if there is a direct route from the origin to the destination
        $transport = new Plane("A81", "KEROSENE", 4.23, 3);
        break;
    case "TRAIN":
        $transport = new Train();
        break;
    case "DRIVING":
        $transport = new Car("BERLINE", "GAZOLE", 6, $passengers);
        break;
}

$origin = getCoordinates($mode, $country_src, $city_src, $airport_src);
$destination = getCoordinates($mode, $country_dst, $city_dst, $airport_dst);
$transport;
// TO-DO: SQL query to fetch the transport data from the DB
// TO-DO: change the parameters of the constructors to match the database


$travel = $transport->getTravel($origin, $destination);
?>

<div class="right-section" id="calculateur-right-section">
    <div class="result">
        <div class="result-header">
            <div class="result-header-left">
                <h2 class="result-header-left-title">Résultats</h2>
                <p class="result-header-left-subtitle">Vos résultats pour un trajet de <?=$city_src . ", " . $country_src?> à <?=$city_dst . ", " . $country_dst?></p>
            </div>
            <div class="result-header-right">
                <div class="result-header-right-mode">
                    <!-- <img src="assets/img/plane.svg"> -->
                    <p class="result-header-right-mode-text"><?=$mode?></p>
                </div>
                <!-- <div class="result-header-right-mode">
                    <img src="assets/img/car.svg">
                    <p class="result-header-right-mode-text">Voiture</p>
                </div>
                <div class="result-header-right-mode">
                    <img src="assets/img/train.svg">
                    <p class="result-header-right-mode-text">Train</p>
                </div> -->
            </div>
        </div>
        <div class="result-content">
            <div class="result-content-left">
                <div class="result-content-left-item">
                    <h3 class="result-content-left-item-title">Distance</h3>
                    <p class="result-content-left-item-value"><?=$travel["distance"]/1000?> km</p>
                </div>
                <div class="result-content-left-item">
                    <h3 class="result-content-left-item-title">Durée</h3>
                    <p class="result-content-left-item-value"><?=formatTime($travel["duration"])?></p>
                </div>
                <div class="result-content-left-item">
                    <h3 class="result-content-left-item-title">Coût du trajet par passager</h3>
                    <p class="result-content-left-item-value"><?=$travel["travelCost"]?> €</p>
                </div>
            </div>
            <div class="result-content-right">
                <div class="result-content-right-item">
                    <h3 class="result-content-right-item-title">Consommation de carburant</h3>
                    <p class="result-content-right-item-value"><?=$travel["fuelConsumption"]?>L</p>
                </div>
                <div class="result-content-right-item">
                    <h3 class="result-content-right-item-title">Empreinte carbone par passager</h3>
                    <p class="result-content-right-item-value"><?=$travel["carbonFootprint"]?> kg</p>
                </div>
            </div>
        </div>
    </div>
</div>