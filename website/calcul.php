<?php

require_once "functions.php";

// CSRF protection
// if (!isset($_GET["csrf"]) || $_GET["csrf"] !== $_SESSION["csrf"]) {
//     die("CSRF token validation failed");
// }

// get the data from the GET request
$_GET = json_decode($_GET["data"], true);
$country_src = isset($_GET["country_src"]) ? $_GET["country_src"] : null;
$city_src = isset($_GET["city_src"]) ? $_GET["city_src"] : null;
$country_dst = isset($_GET["country_dst"]) ? $_GET["country_dst"] : null;
$city_dst = isset($_GET["city_dst"]) ? $_GET["city_dst"] : null;
$departure_date = isset($_GET["departure_date"]) ? $_GET["departure_date"] : null;
$arrival_date = isset($_GET["arrival_date"]) ? $_GET["arrival_date"] : null;
$passengers = isset($_GET["passengers"]) ? $_GET["passengers"] : null;


// default VALUES but need to implement the possibility to choose a certain car

var_dump($_GET);
echo "<br>";

$origin = getCityCoordinates($country_src, $city_src);
$destination = getCityCoordinates($country_dst, $city_dst);
// TO-DO: change the parameters of the constructors to match the database

$car = null;
$plane = null;
$train = null;
?>
<script>console.log(<?=json_encode($_GET)?>)</script>
<div class="right-section" id="calculateur-right-section">
    <div hx-get="scripts/calculator/transport-car.php" hx-trigger="load" hx-vals=<?= json_encode($_GET)?>></div>
    <div hx-get="scripts/calculator/transport-train.php" hx-trigger="load" hx-vals=<?= json_encode($_GET)?>></div>
    <div hx-get="scripts/calculator/transport-plane.php" hx-trigger="load" hx-vals=<?= json_encode($_GET)?>></div>
</div>