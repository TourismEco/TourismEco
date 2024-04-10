<?php
$country_src = isset($_GET["country_src"]) ? $_GET["country_src"] : null;
$city_src = isset($_GET["city_src"]) ? $_GET["city_src"] : null;
$country_dst = isset($_GET["country_dst"]) ? $_GET["country_dst"] : null;
$city_dst = isset($_GET["city_dst"]) ? $_GET["city_dst"] : null;
$departure_date = isset($_GET["departure_date"]) ? $_GET["departure_date"] : null;
$arrival_date = isset($_GET["arrival_date"]) ? $_GET["arrival_date"] : null;
$passengers = isset($_GET["passengers"]) ? $_GET["passengers"] : null;

// $departure_time = strtotime($departure_date) - time();
// $return_time = strtotime($arrival_date) - time();

exec("../../.venv/bin/trainline_cli.py -d $city_src -a $city_dst -n 1d", $output);
$output = json_decode($output[0], True);
var_dump($output);

?>

<div class="container-scores border-GR">
    <div class="title-calc">
        <img src="assets/icons/train.svg">
        <p>Train</p>
    </div>

    <div class="stats-calc">
        <div>
            <h3>Empreinte Carbone</h3>
            <p>//</p>
        </div>
        <div class="trait-small"></div>
        <div>
            <h3>Prix du trajet</h3>
            <p><?=$output['price']?></p>
        </div>
        <div class="trait-small"></div>
        <div>
            <h3>Distance parcourue</h3>
            <p>//</p>
        </div>
        <div class="trait-small"></div>
        <div>
            <h3>Durée trajet</h3>
            <p><?=$output['duration']?></p>
        </div>
        <div class="trait-small"></div>
        <div>
            <h3>Nombre de segments</h3>
            <p><?=$output['number_of_segments']?></p>
        </div>
    </div>
</div>