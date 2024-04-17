<?php
require_once "../../config.php";
require_once "../../functions.php";

$country_src = isset($_GET["country_src"]) ? $_GET["country_src"] : null;
$city_src = isset($_GET["city_src"]) ? $_GET["city_src"] : null;
$country_dst = isset($_GET["country_dst"]) ? $_GET["country_dst"] : null;
$city_dst = isset($_GET["city_dst"]) ? $_GET["city_dst"] : null;
$departure_date = isset($_GET["departure_date"]) ? $_GET["departure_date"] : null;
$passengers = isset($_GET["passengers"]) ? $_GET["passengers"] : null;


class Train {

    function _fetchDistance($origin, $destination)
    {
        $url = distanceMatrixRequestBuilder($origin, $destination, "transit", "train");
        echo "<script>console.log('".$url."')</script>";
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    function getEmissions($origin, $destination)
    {
//      TGV : 0,002 kg CO₂e/km ;
//      Intercités : 0,006 kg CO₂e/km ;
//      Transilien : 0,007 kg CO₂e/km ;
//      TER : 0,03 kg CO₂e/km.
        $travel = [
            "distance" => null, // in m
            "duration" => null, // in seconds
            "emissions" => null, // in kg CO2e  
            "price" => null, // in €
        ];
        $mapsResult = $this->_fetchDistance($origin, $destination);
        if ($mapsResult["rows"][0]["elements"][0]["status"] == "ZERO_RESULTS") { // or NOT_FOUND
            // itineraries not found
            $travel = null;
        } else {
            $travel["distance"] = $mapsResult["rows"][0]["elements"][0]["distance"]["value"];
            $travel["duration"] = $mapsResult["rows"][0]["elements"][0]["duration"]["value"];
            $travel["emissions"] = sprintf("%2.2f", $travel["distance"]/1000 * 0.003);
        }
        return $travel;
    }
}

// echo "../../.venv/bin/trainline_cli.py -d '$city_src' -a '$city_dst' -dd '$departure_date 08:00'";
exec("../../.venv/bin/trainline_cli.py -d '$city_src' -a '$city_dst' -dd '$departure_date 08:00'", $output);
$output = $output ? json_decode($output[0], True) : null;


$train = new Train();
if ($output) {
    $origin = ["city" => $city_src, "country" => $country_src];
    $destination = ["city" => $city_dst, "country" => $country_dst];
    $res = $train->getEmissions($origin, $destination);
    $output['emissions'] = $res['emissions'];
    $output['distance'] = $res['distance'];

    $cb = function ($fn) {
        return $fn;
    };

    echo <<< HTML
        <div class="container-scores border-GR">
            <div class="title-calc">
                <img src="assets/icons/train.svg">
                <p>Train</p>
            </div>

            <div class="stats-calc">
                <div>
                    <h3>Empreinte Carbone</h3>
                    <p> {$cb($output["emissions"]*$passengers)} kg CO2e</p>
                </div>
                <div class="trait-small"></div>
                <div>
                    <h3>Prix du trajet</h3>
                    <p>$output[price] €</p>
                </div>
                <div class="trait-small"></div>
                <div>
                    <h3>Distance parcourue</h3>
                    <p>{$cb(sprintf("%0.2f", $output["distance"]/1000))} km</p>
                </div>
                <div class="trait-small"></div>
                <div>
                    <h3>Durée trajet</h3>
                    <p>$output[duration]</p>
                </div>
                <div class="trait-small"></div>
                <div>
                    <h3>Nombre de correspondances</h3>
                    <p>{$cb($output["number_of_segments"]-1 > 0 ? $output["number_of_segments"]-1 : "Aucune")}</p>
                </div>
            </div>
        </div>
HTML;
} else {
    echo <<<HTML
    <div class="container-scores border-GR">
        <div class="title-calc">
            <img src="assets/icons/train.svg">
            <p>Train</p>
        </div>

        <div class="stats-calc">
            Aucun itinéraire trouvé
        </div>
    </div>
    HTML;
    }

?>

