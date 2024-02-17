<?php 
require_once "config.php";

function alreadyInDB($origin, $destination){
    //TO-DO:: implement a realistic version of this function once the DB is up to date
    return false;
}

// make a request to the google maps API to get the distance between two points
function distanceMatrixRequestBuilder($destinations, $origins, $mode, $transit_mode=null){
    $url = "https://maps.googleapis.com/maps/api/distancematrix/json?".
    "origins=".urlencode($origins["lat"].",".$origins["lon"]).
    "&destinations=".urlencode($destinations["lat"].",".$destinations["lon"]).
    "&key=".MAPS_API_KEY.
    "&mode=".$mode;
    if ($transit_mode != null){
        $url .= "&transit_mode=".$transit_mode;
    }
    return $url;
}

abstract class Transport {

    public $model;  // model of the vehicle Renault Twingo, TGV, A320, etc
    public $fuel;
    public $fuelConsumption;  // in L/100km
    public $passengers; // number of passengers in the vehicle

    public abstract function fetchDistance($origin, $destination);
    // should return an array with distance, duration, carbon footprint, fuel consumption, travel cost
    public abstract function getTravel($origin, $destination);

    public function getFuelPrice(){
        // return the price of the fuel in €/L 
        // TO-DO: implement a function to fetch the fuel price from the DB or whatsoever
        switch ($this->fuel) {
            case "ESSENCE":
                break;
            case "GAZOLE":
                break;
            case "ELECTRIC":
                break;
            case "ESS+ELEC HNR":
                break;
            case "GAZ+ELEC HNR":
                break;
            case "ELEC+ESSENC HR":
                break;
            case "ESS+G.P.L.":
                break;
            case "SUPERETHANOL":
                break;
            case "GAZ NAT.VEH":
                break;
        }
        return 1.80;
    }

    function getFuelEmission() {
        // returns the CO2 emission factor of the fuel in kg CO2e/L
        // if a car is hybrid we return the value of its non electric fuel
        // the fuel consumption is given by the database
        $val = null;
        switch ($this->fuel) {
            case "ESSENCE":
                $val = 2.5; 
                break;
            case "GAZOLE":
                $val = 2.64;
                break;
            case "ELECTRIC":
                $val = 0;
                break;
            case "G.P.L.";
                $val = 1.6;
                break;
            case "ESS+ELEC HNR":
                $val = 2.5;
                break;
            case "GAZ+ELEC HNR":
                $val = 2.64;
                break;
            case "ELEC+ESSENC HR":
                $val = 2.5;
                break;
            case "ESS+G.P.L.":
                $val = 2.5;
                break;
            case "SUPERETHANOL":
                $val = 1.63;
                break;
            case "GAZ NAT.VEH":
                $val = 1.94;
                break;
            default: // average of gasoline and essence
                $val = 2.6;
        }
        return $val;
    }
}

class Car extends Transport {

    public $body; // BERLINE, SUV, BREAK, etc
 //TO-DO: wait for the form to be up to date to implement a function to fetch the body from the DB

    function __construct($body, $fuel, $fuelConsumption, $passengers) {
        $this->body = $body;
        $this->fuel = $fuel;
        $this->fuelConsumption = $fuelConsumption;
        $this->passengers = $passengers;
    }

    function fetchDistance($origin, $destination) {
        $url = distanceMatrixRequestBuilder($destination, $origin, "DRIVING");
        $response = file_get_contents($url);
        return json_decode($response, true);
    }

    function getTravel($origin, $destination) {
        $travel = array( "distance" => null,        // in m
                        "duration" => null,         // in seconds
                        "fuelConsumption" => null,  // in L
                        "carbonFootprint" => null,  // in kg CO2e / passenger
                        "carbonFootprintPassenger" => null,  // in kg CO2e / passenger
                        "travelCost" => null        // in €
                    );
        if (!alreadyInDB($origin, $destination)){
            $jsonAPI = $this->fetchDistance($origin, $destination);
            if ($jsonAPI["rows"][0]["elements"][0]["status"] == "NOT_FOUND") {
                // itineraries not found
                $travel = null;
            } else {
            $travel["distance"] = $jsonAPI["rows"][0]["elements"][0]["distance"]["value"];
            $travel["duration"] = $jsonAPI["rows"][0]["elements"][0]["duration"]["value"];
            $travel["fuelConsumption"] = $travel["distance"]/1000 * $this->fuelConsumption; 
            $travel["carbonFootprint"] = $travel["fuelConsumption"] * $this->getFuelEmission();
            $travel["carbonFootprintPassenger"] = $travel["carbonFootprint"] / $this->passengers;
            $travel["travelCost"] = $travel["fuelConsumption"] * $this->getFuelPrice();
            }
        }
        else {
            // TO-DO: fetch the data from the DB
        }
        return $travel;
        //TO-DO: update the database with the data fetched
    }

}

class Train extends Transport {
     // TGV

    public $fuel = "ELECTRIC"; // TO-DO: wait for the form to be up to date to implement a function to fetch the fuel from the DB 

    function fetchDistance($origin, $destination) {
        $url = distanceMatrixRequestBuilder($origin, $destination, "TRANSIT", "TRAIN");
        $response = file_get_contents($url);
        return json_decode($response, true);
    }


    public function getTravel($origin, $destination){
        $travel = array( "distance" => null,        // in m
                        "duration" => null,         // in seconds
                        "fuelConsumption" => null,  // in L
                        "carbonFootprint" => null,  // in kg CO2e / passenger
                        "carbonFootprintPassenger" => null,  // in kg CO2e / passenger
                        "travelCost" => null        // in €
                    );
        if (!alreadyInDB($origin, $destination)){
            $jsonAPI = $this->fetchDistance($origin, $destination);
            $travel["distance"] = $jsonAPI["rows"][0]["elements"][0]["distance"]["value"];
            $travel["duration"] = $jsonAPI["rows"][0]["elements"][0]["duration"]["value"];
            $travel["carbonFootprint"] = $travel["distance"]/1000 * $this->getFuelEmission();
            $travel["carbonFootprintPassenger"] = $travel["carbonFootprint"] / $this->passengers;
            $travel["travelCost"] = 0; // TO-DO: implement a function to fetch the price of the ticket
        }
        else {
            // TO-DO: fetch the data from the DB
        }
    }
}

class Plane extends Transport {

    public $model; // A81, A320, etc
    function __construct($model, $fuel, $fuelConsumption, $passengers){
        $this->model = $model;
        $this->fuel = $fuel;
        $this->fuelConsumption = $fuelConsumption;
        $this->passengers = $passengers;
    }

    function fetchDistance($origin, $destination) {
        return null;
    }


    function getTravel($origin, $destination){
        $travel = array( "distance" => null,        // in m
                        "duration" => null,         // in seconds
                        "fuelConsumption" => null,  // in L
                        "carbonFootprint" => null,  // in kg CO2e
                        "carbonFootprintPassenger" => null,  // in kg CO2e / passenger
                        "travelCost" => null        // in €
                    );
    }

    function haversineDistance($origin, $destination) {
        $lat1 = $origin["lat"];
        $lon1 = $origin["lng"];
        $lat2 = $destination["lat"];
        $lon2 = $destination["lng"];
        $R = 6371; // Radius of the earth in km
        $dLat = degrees_to_radians($lat2-$lat1);
        $dLon = degrees_to_radians($lon2-$lon1); 
        $a = 
          sin($dLat/2) * sin($dLat/2) +
          cos(degrees_to_radians($lat1)) * cos(degrees_to_radians($lat2)) * 
          sin($dLon/2) * sin($dLon/2)
          ; 
        $c = 2 * atan2(sqrt($a), sqrt(1-$a)); 
        $d = $R * $c; // Distance in km
        return $d;
    }
}