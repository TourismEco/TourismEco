<?php
require_once "../../config.php";
require_once "../../functions.php";

class Car
{
    private $fuel;
    private $fuelConsumption; // in L/100km
    private $passengers;

    function __construct($fuel, $fuelConsumption, $passengers)
    {
        $this->fuel = $fuel;
        $this->passengers = $passengers;
        $this->fuelConsumption = $fuelConsumption; // in L/100km
    }

    function getFuelEmission()
    {
        // returns the CO2 emitted (upstream & combustion) by the fuel in kg CO2e/L
        // GR and GNR stand for "Gazole Routier" & "Gazole Non Routier"
        $val = null;
        switch ($this->fuel) {
            case "SP95":
                return 2.75;
                break;
            case "SP98":
                return 2.75;
                break;
            case "SP95-E10":
                return 2.75;
                break;
            case "B7":
                return 3.1;
                break;
            case "B10":
                return 3.04;
                break;
            case "E85":
                return 1.107;
                break;
            default:
                // random value
                $val = 2.9;
        }
        return $val;
    }

    public function getFuelPrice()
    {
        // return the price of the fuel in €/L
        // TO-DO: set the prices to the real ones
        switch ($this->fuel) {
            case "SP95":
                return 1.89;
                break;
            case "SP98":
                return 1.95;
                break;
            case "SP95-E10":
                return 1.85;
                break;
            case "B7":
                return 1.8;
                break;
            case "E85":
                return 0.8;
                break;
            case "GPL":
                return 0.8;
                break;
            case "Elec":
                return 0.2;
                break;
        }
        return 1.8;
    }


    function _fetchDistance($origin, $destination)
    {
        $url = distanceMatrixRequestBuilder($origin, $destination, "driving");
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    function getTravel($origin, $destination)
    {
        $travel = [
            "distance" => null, // in m
            "duration" => null, // in seconds
            "emissions" => null, // in kg CO2e
            "price" => null, // in €
            "fuelConsumption" => null // in L
        ];
        $mapsResult = $this->_fetchDistance($origin, $destination);
        if ($mapsResult["rows"][0]["elements"][0]["status"] == "ZERO_RESULTS") { // or NOT_FOUND
            // itineraries not found
            $travel = null;
        } else {
            $travel["distance"] = $mapsResult["rows"][0]["elements"][0]["distance"]["value"];
            $travel["duration"] = $mapsResult["rows"][0]["elements"][0]["duration"]["value"];
            $travel["fuelConsumption"] = ($travel["distance"] / 1000) * ($this->fuelConsumption * (1+$this->passengers*0.002) / 100);
            // getFuelEmission() returns the CO2 emission factor of the fuel in kg CO2e/L
            $travel["emissions"] = ($travel["fuelConsumption"] * $this->getFuelEmission());
            $travel["price"] = $travel["fuelConsumption"] * $this->getFuelPrice();
        }
        return $travel;
    }
}

$country_src = isset($_GET["country_src"]) ? $_GET["country_src"] : null;
$city_src = isset($_GET["city_src"]) ? $_GET["city_src"] : null;
$country_dst = isset($_GET["country_dst"]) ? $_GET["country_dst"] : null;
$city_dst = isset($_GET["city_dst"]) ? $_GET["city_dst"] : null;
$departure_date = isset($_GET["departure_date"]) ? $_GET["departure_date"] : null;
$return_date = isset($_GET["return_date"]) ? $_GET["return_date"] : null;
$passengers = isset($_GET["passengers"]) ? $_GET["passengers"] : null;

$origin = ["city" => $city_src, "country" => $country_src];
$destination = ["city" => $city_dst, "country" => $country_dst];

// Random default values !
$car = new Car("B7", 6.5, $passengers);
$travelData = $car->getTravel($origin, $destination);

$cb = function ($fn) {
    return $fn;
};

if ($travelData == null) {
    echo <<<HTML
    <div class="container-scores border-GR">
        <div class="title-calc">
            <img src="assets/icons/car.svg">
            <p>Voiture</p>
        </div>

        <div class="stats-calc">
            Aucun itinéraire trouvé
        </div>
    </div>
    HTML;
    }
else {
    echo <<<HTML
    <div class="container-scores border-GR">
        <div class="title-calc">
            <img src="assets/icons/car.svg">
            <p>Voiture</p>
        </div>

        <div class="stats-calc">
            <div>
                <h3>Empreinte Carbone</h3>
                <p>{$cb(sprintf("%02.2f", $travelData["emissions"]))} kg CO2e</p>
            </div>
            <div class="trait-small"></div>
            <div>
                <h3>Prix du trajet</h3>
                <p>{$cb(sprintf("%02.2f", $travelData["price"]))} €</p>
            </div>
            <div class="trait-small"></div>
            <div>
                <h3>Distance parcourue</h3>
                <p>{$cb(intval($travelData["distance"]/1000))} km</p>
            </div>
            <div class="trait-small"></div>
            <div>
                <h3>Durée trajet</h3>
                <p>{$cb(formatTime($travelData["duration"]))}</p>
            </div>
        </div>
    </div>
    HTML;
}
?>