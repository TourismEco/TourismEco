<?php
// tests for calcul.php


require_once "../functions.php";
require_once "../transport.php";

var_dump($_GET);
echo "<br>";

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


// test getCountryID
echo "<br>getCountryID src<br>";
echo "par1: (".gettype($country_src).") $country_src<br>";
echo getCountryId($country_src);

echo "<br>getCountryID dst<br>";
echo "par1: (".gettype($country_dst).") $country_dst<br>";
echo getCountryId($country_dst);


// test getAirportCoordinates
echo "<br>getAirportCoordinates src<br>";
echo "par1: (".gettype($airport_src).") $airport_src<br>";
var_dump(getAirportCoordinates($airport_src));
echo "<br>";

echo "<br>getAirportCoordinates dst<br>";
echo "par1: (".gettype($airport_dst).") $airport_dst<br>";
var_dump(getAirportCoordinates($airport_dst));
echo "<br>";

// test getCityCoordinates
echo "<br>getCityCoordinates src<br>";
echo "par1: (".gettype($country_src).") $country_src, par2: (".gettype($city_src).") $city_src<br>";
var_dump(getCityCoordinates($country_src, $city_src));
echo "<br>";

echo "<br>getCityCoordinates dst<br>";
echo "par1: (".gettype($country_dst).") $country_dst, par2: (".gettype($city_dst).") $city_dst<br>";
var_dump(getCityCoordinates($country_dst, $city_dst));
echo "<br>";

// test getCoordinates
echo "<br>getCoordinates src<br>";
echo "par1: (".gettype($mode).") $mode, par2: (".gettype($country_src).") $country_src, par3: (".gettype($city_src).") $city_src, par4: (".gettype($airport_src).") $airport_src<br>";
var_dump(getCoordinates($mode, $country_src, $city_src, $airport_src));
echo "<br>";

echo "<br>getCoordinates dst<br>";
echo "par1: (".gettype($mode).") $mode, par2: (".gettype($country_dst).") $country_dst, par3: (".gettype($city_dst).") $city_dst, par4: (".gettype($airport_dst).") $airport_dst<br>";
var_dump(getCoordinates($mode, $country_dst, $city_dst, $airport_dst));
echo "<br>";

// distanceMatrixQueryBuilder works

// Testing transport.php
echo "<br>class Car<br>";
$car = new Car("BERLINE", "GAZOLE", 6.2, 3);
var_dump($car);
echo "<br>getTravel<br>";
var_dump($car->getTravel(getCoordinates($mode, $country_src, $city_src, $airport_src), getCoordinates($mode, $country_dst, $city_dst, $airport_dst)));

