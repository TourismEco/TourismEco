<?php

require_once "../../functions.php";

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
$departure_date = isset($_GET["departure_date"])
    ? $_GET["departure_date"]
    : null;
$passengers = isset($_GET["passengers"]) ? $_GET["passengers"] : null;
$is_round_trip = isset($_GET["round_trip"]) ? $_GET["round_trip"] : false;
?>

<div  id="calculateur-right-section" style="grid-row: unset;">
    <div hx-get="scripts/calculator/transport-car.php" hx-trigger="load" hx-vals=<?= json_encode(
        $_GET
    ) ?>>
        <div class="container-scores border-GR">
            <div class="title-calc">
                <img src="assets/icons/car.svg">
                <p>Voiture</p>
            </div>

            <div class="stats-calc">
            <img alt="Chargement du résultat... Cela peut prendre du temps..." class="htmx-indicator" width="150" src="assets/icons/load.gif"/>
            </div>
        </div>

    </div>
    <div hx-get="scripts/calculator/transport-train.php" hx-trigger="load" hx-vals=<?= json_encode(
        $_GET
    ) ?>>
        <div class="container-scores border-GR">
            <div class="title-calc">
                <img src="assets/icons/train.svg">
                <p>Train</p>
            </div>

            <div class="stats-calc">
            <img alt="Chargement du résultat... Cela peut prendre du temps..." class="htmx-indicator" width="150" src="assets/icons/load.gif"/>
            </div>
        </div>
    </div>
    <div hx-get="scripts/calculator/transport-plane.php" hx-trigger="load" hx-vals=<?= json_encode(
        $_GET
    ) ?>>
        <div class="container-scores border-GR">
            <div class="title-calc">
                <img src="assets/icons/plane.svg">
                <p>Avion</p>
            </div>

            <div class="stats-calc">
            <img alt="Chargement du résultat... Cela peut prendre du temps..." class="htmx-indicator" width="150" src="assets/icons/load.gif"/>
            </div>
        </div>
    </div>
</div>
