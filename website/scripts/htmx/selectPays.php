<?php

if (!isset($_SERVER["HTTP_HX_REQUEST"])) {
    header("HTTP/1.1 401");
    exit;
}

if (!isset($_GET["id"]) || !isset($_GET["nom"]) || !isset($_GET["sens"])) {
    header("HTTP/1.1 400");
    exit;
}

require('../../functions.php');

$id_pays = $_GET["id"];
$nom = $_GET["nom"];
$sens = $_GET["sens"];
$connexion = getDB();

$stmt = $connexion->prepare("SELECT * FROM villes WHERE id_pays = ? ORDER BY population DESC");
$stmt->execute(["$id_pays"]);

$options = $stmt->fetchAll(PDO::FETCH_ASSOC);

inputPays($nom, $sens);
inputVilles($id_pays, "", $sens);
iterOptions($options, "city_options_$sens", $sens, "city");
emptyOptions("country_options_$sens");

?>