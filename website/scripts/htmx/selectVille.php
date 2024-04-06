<?php

if (!isset($_SERVER["HTTP_HX_REQUEST"])) {
    header("HTTP/1.1 401");
    exit;
}

if (!isset($_GET["id"]) || !isset($_GET["nom"]) || !isset($_GET["id_pays"]) || !isset($_GET["sens"])) {
    header("HTTP/1.1 400");
    exit;
}

require('../../functions.php');

$id_ville = $_GET["id"];
$id_pays = $_GET["id_pays"];
$nom = $_GET["nom"];
$sens = $_GET["sens"];

inputVilles($id_pays, $nom, $sens);
emptyOptions("city_options_$sens");

?>