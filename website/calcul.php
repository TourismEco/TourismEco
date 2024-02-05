<?php

require_once "functions.php";
require_once "transport.php";

// template POST from calculateur.php
// {
//     "country_src": (string) Name of the country of departure,
//     "city_src": (string) Name of the city of departure,
//     "country_dst": (string) Name of the country of arrival,
//     "city_dst": (string) Name of the city of arrival,
//     "mode": (string) mode of transport,
//     "car_model": (string) model of the car, /!\ RANDOM ATM
//     "duration": (string) duration of the trip, /!\ DOESNT WORK ATM
//     "passengers": (int) number of passengers
// }

// template response
// {
//     "distance": (int) distance in meters,
//     "duration": (int) duration in seconds,
//     "carbon_footprint": (int) carbon footprint in grams,
//     "fuel_consumption": (int) fuel consumption in liters,
//     "travel_cost": (float) cost of the travel in euros,
//     "duration": (string) duration of the trip,
//     "trip_cost": (float) cost of the trip
// }

// API TravelModes: DRIVING, WALKING, BICYCLING, TRANSIT
// API TransitModes: BUS, SUBWAY, TRAIN, TRAM, RAIL

// CSRF protection
if (!isset($_POST["csrf"]) || $_POST["csrf"] !== $_SESSION["csrf"]) {
    die("CSRF token validation failed");
}

// get the data from the POST request
$country_src = isset($_POST["country_src"]) ? $_POST["country_src"] : null;
$city_src = isset($_POST["city_src"]) ? $_POST["city_src"] : null;
$country_dst = isset($_POST["country_dst"]) ? $_POST["country_dst"] : null;
$city_dst = isset($_POST["city_dst"]) ? $_POST["city_dst"] : null;
$mode = isset($_POST["mode"]) ? $_POST["mode"] : null;
$model = isset($_POST["model"]) ? $_POST["model"] : null;
$duration = isset($_POST["duration"]) ? $_POST["duration"] : null;
$passengers = isset($_POST["passengers"]) ? $_POST["passengers"] : null;

// get the coordinates (latitude and longitude) of a city
function getCityCoordinates($country, $city): array{
    $conn = getDB();
    // TO-DO: change to lat and lon when the database is updated
    $sql = "SELECT lat, lon FROM villes, pays WHERE id_pays = ? AND id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$country, $city]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function getAirportCoordinates($airport) {
    $conn = getDB();
    $sql = "SELECT lat, lon FROM airports WHERE id_aeroport = ?";
    $stmt = $conn->prepare($sql);
    $stmt->execute([$airport]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result;
}

function getCoordinates($country, $city, $mode) {
    if ($mode == "PLANE") {
        return getCityCoordinates($country, $city);
    } else {
        return getAirportCoordinates($country, $city);
    }
}

// main
$origin = getCoordinates($country_src, $city_src, $mode);
$destination = getCoordinates($country_dst, $city_dst, $mode);
$transport;
// TO-DO: SQL query to fetch the transport data from the DB
// TO-DO: change the parameters of the constructors to match the database
if ($mode == "PLANE") {
    $transport = new Plane("A81", "KEROSENE", 4.23, 3);
} else if ($mode == "TRAIN") {
    $transport = new Train();
} else if ($mode == "DRIVING") {
    $transport = new Car("BERLINE", "GASOLINE", 6.5, 2.5);
}
$travel = $transport->getTravel($origin, $destination);
?>

<div class="right-section">
    <div class="result">
        <div class="result-header">
            <div class="result-header-left">
                <p class="result-header-left-title">Résultats</p>
                <p class="result-header-left-subtitle">Vos résultats pour un trajet de <?=$city_src . ", " . $country_src?> à <?=$city_dst . ", " . $country_dst?></p>
            </div>
            <div class="result-header-right">
                <div class="result-header-right-mode">
                    <img src="assets/img/plane.svg">
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
                    <p class="result-content-left-item-title">Distance</p>
                    <p class="result-content-left-item-value"><?=$travel["distance"]?> m</p>
                </div>
                <div class="result-content-left-item">
                    <p class="result-content-left-item-title">Durée</p>
                    <p class="result-content-left-item-value"><?=$travel["duration"]?> secondes</p>
                </div>
                <div class="result-content-left-item">
                    <p class="result-content-left-item-title">Coût du trajet par passager</p>
                    <p class="result-content-left-item-value"><?=$travel["travelCost"]?> €</p>
                </div>
            </div>
            <div class="result-content-right">
                <div class="result-content-right-item">
                    <p class="result-content-right-item-title">Consommation par passager</p>
                    <p class="result-content-right-item-value"><?=$travel["fuelConsumption"]?>L</p>
                </div>
                <div class="result-content-right-item">
                    <p class="result-content-right-item-title">Empreinte carbone par passager</p>
                    <p class="result-content-right-item-value"><?=$travel["carbonFootprint"]?> kg</p>
                </div>
</div>