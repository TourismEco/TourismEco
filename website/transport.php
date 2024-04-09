<?php
require_once "config.php";

function alreadyInDB($origin, $destination)
{
    //TO-DO:: implement a realistic version of this function once the DB is up to date
    return false;
}

// make a request to the google maps API to get the distance between two points
function distanceMatrixRequestBuilder(
    $origins,
    $destinations,
    $mode,
    $transit_mode = null
) {
    $url =
        "https://maps.googleapis.com/maps/api/distancematrix/json?" .
        "origins=" .
        urlencode($origins["lat"] . "," . $origins["lon"]) .
        "&destinations=" .
        urlencode($destinations["lat"] . "," . $destinations["lon"]) .
        "&key=" .
        MAPS_API_KEY .
        "&mode=" .
        $mode;
    if ($transit_mode != null) {
        $url .= "&transit_mode=" . $transit_mode;
    }
    return $url;
}

abstract class Transport
{
    public $model; // model of the vehicle Renault Twingo, TGV, A320, etc
    public $fuel;
    public $fuelConsumption; // in L/100km
    public $passengers; // number of passengers in the vehicle

    abstract public function fetchDistance($origin, $destination);
    // should return an array with distance, duration, carbon footprint per passenger, fuel consumption, travel cost
    abstract public function getTravel($origin, $destination);

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
}

class Car extends Transport
{
    function __construct($fuel, $fuelConsumption, $passengers)
    {
        $this->fuel = $fuel;
        $this->fuelConsumption = $fuelConsumption; // in L/100km
        $this->passengers = $passengers;
    }

    function fetchDistance($origin, $destination)
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
            "fuelConsumption" => null, // in L
            "carbonFootprintPassenger" => null, // in kg CO2e / passenger
            "travelCost" => null, // in €
        ];
        $jsonAPI = $this->fetchDistance($origin, $destination);
        if ($jsonAPI["rows"][0]["elements"][0]["status"] == "NOT_FOUND") {
            // itineraries not found
            $travel = null;
        } else {
            $travel["distance"] =
                $jsonAPI["rows"][0]["elements"][0]["distance"]["value"];
            $travel["duration"] =
                $jsonAPI["rows"][0]["elements"][0]["duration"]["value"];
            $travel["fuelConsumption"] =
                ($travel["distance"] / 1000) *
                ($this->fuelConsumption / 100);
            // getFuelEmission() returns the CO2 emission factor of the fuel in kg CO2e/L
            $travel["carbonFootprintPassenger"] =
                ($travel["fuelConsumption"] * $this->getFuelEmission()) /
                $this->passengers;
            $travel["travelCost"] =
                $travel["fuelConsumption"] * $this->getFuelPrice();
            //TO-DO: update the database with the data fetched
        }
        return $travel;
    }
}

class Train extends Transport
{
    // TGV, TER, intercités, etc
    // according to the SNCF: https://www.sncf.com/fr/engagements/developpement-durable/engagement-grand-groupe-pour-la-planete/methodologie-calcul-empreinte-carbone
    // TGV: the average CO2 emission is 2.3g CO2e / passenger / km
    // Intercités: the average CO2 emission is 5.8g CO2e / passenger / km
    // Train: the average CO2 emission is 6.6g CO2e / passenger / km
    // TER: the average CO2 emission is 22.9g CO2e / passenger / km
    // we may consider that people usually take the TGV or the Intercités cause we're studying long distance travels
    // so we can consider that the average CO2 emission is 4.05g CO2e / passenger / km

    public static $CO2PerPassengerKm = 0.005; // in kg CO2e / passenger / km
    public static $fuelC = 0; // in L/100km

    // Source for train fuel: https://www.fuellogic.net/what-fuel-do-trains-use/
    // Diesel-Powered Trains: Diesel locomotives are commonly used in many rail systems. On average, a typical freight diesel locomotive can consume 3 to 5 gallons of diesel fuel per mile. Passenger diesel trains might have slightly lower fuel consumption
    // Electric Trains: Electric trains rely on electricity rather than fuel. On average, an electric train can use around 2 to 3 kWh per mile for passenger trains and potentially more for heavy freight trains.
    public $fuel = "LOCOMOTIVE DIESEL";
    public $fuelConsumption = 9.4086; // in L/100km (7.0564 < x < 11.7607)
    // public $passengers = 200; // number of passengers in the vehicle

    function fetchDistance($origin, $destination)
    {
        $url = distanceMatrixRequestBuilder(
            $origin,
            $destination,
            "transit",
            "rail"
        );
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    function getTravel($origin, $destination)
    {
        $travel = [
            "distance" => null, // in m
            "duration" => null, // in seconds
            "fuelConsumption" => null, // in L
            "carbonFootprintPassenger" => null, // in kg CO2e / passenger
            "travelCost" => null, // in €
        ];
        $jsonAPI = $this->fetchDistance($origin, $destination);
        if ($jsonAPI["rows"][0]["elements"][0]["status"] == "NOT_FOUND") {
            // itineraries not found
            $travel = null;
        } else {
            $travel["distance"] =
                $jsonAPI["rows"][0]["elements"][0]["distance"]["value"];
            $travel["duration"] =
                $jsonAPI["rows"][0]["elements"][0]["duration"]["value"];
            $travel["fuelConsumption"] =
                ($travel["distance"] / 1000) *
                ($this->fuelConsumption / 100);
            // getFuelEmission() returns the CO2 emission factor of the fuel in kg CO2e/L
            $travel["carbonFootprintPassenger"] =
                $travel["fuelConsumption"] * $this->getFuelEmission();
            $travel["carbonFootprint"] / $this->passengers;
            $travel["travelCost"] =
                $travel["fuelConsumption"] * $this->getFuelPrice();
        }
        return $travel;
    }
}

class Plane extends Transport
{

    function fetchDistance($origin, $destination)
    {
        return null;
    }

    function getTravel($origin, $destination)
    {
        
        $travel = [
            "distance" => $this->orthodromeDistance($origin, $destination), // in m
            "duration" => null, // in seconds
            "fuelConsumption" => null, // in L
            "carbonFootprintPassenger" => null, // in kg CO2e / passenger
            "travelCost" => null, // in €
        ];
    }

    function orthodromeDistance($origin, $destination)
    {
        $lat1 = deg2rad($origin["lat"]);
        $lon1 = deg2rad($origin["lon"]);
        $lat2 = deg2rad($destination["lat"]);
        $lon2 = deg2rad($destination["lon"]);

        $delta_lon = $lon2 - $lon1;
        $central_angle = acos(
            sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($delta_lon)
        );
        // Earth's radius in kilometers
        $earth_radius = 6371.0;

        return $central_angle * $earth_radius;
    }

}
