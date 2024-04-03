<?php

if (!isset($_SERVER["HTTP_HX_REQUEST"])) {
    header("HTTP/1.1 401");
    exit;
}

if (!isset($_GET["id_pays"])) {
    header("HTTP/1.1 400");
    exit;
}

require("../../functions.php");

if (!checkHTMX("pays", $_SERVER["HTTP_HX_CURRENT_URL"])) {
    header("HTTP/1.1 401");
    exit;
}

$cur = getDB();

$id_pays = $_GET["id_pays"];
$_SESSION["pays"][0] = $id_pays;

if (!isset($_SESSION["historique"]) || !is_array($_SESSION["historique"])) {
    $_SESSION["historique"] = array();
}
if (!in_array($id_pays, $_SESSION["historique"])) {
    // Ajoutez le nouvel élément à l'historique
    $_SESSION["historique"][] = $id_pays;
  
    $maxHistoriqueSize = 3;  // Définir la taille maximale de l'historique
    while (count($_SESSION["historique"]) > $maxHistoriqueSize) {
        array_shift($_SESSION["historique"]);
    }
}

// Nom
$query = "SELECT * FROM pays WHERE id = :id_pays";
$sth = $cur->prepare($query);
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->execute();
$ligne = $sth->fetch();
$nom = $ligne["nom"];
$description = $ligne["description"];
$letter = getLetter($ligne["score"]);

// Capitale
$c = getCities($id_pays, $cur);
$cities = json_encode($c["cities"]);
$capitals = json_encode($c["capitals"]);

$dataLine = dataLine($id_pays, $cur);
$dataLineMean = dataMean($cur);
$dataLine["comp"] = dataCompareLine($dataLine["data"],$dataLineMean);

$dataSpider = json_encode(dataSpider($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataLine = json_encode($dataLine,JSON_NUMERIC_CHECK);
$dataLineMean = json_encode($dataLineMean,JSON_NUMERIC_CHECK);
$dataBar = json_encode(dataBar($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataTab = json_encode(dataTab($id_pays, $cur),JSON_NUMERIC_CHECK);

//Graphique Barre Line
$allBareLine = dataBarreLine($id_pays, $cur);

$dataBarreLine= json_encode($allBareLine['data'],JSON_NUMERIC_CHECK);
$dataBLMinYearPIB= json_encode($allBareLine['minPib']['year'],JSON_NUMERIC_CHECK);
$dataBLMinValPIB= json_encode($allBareLine['minPib']['value'],JSON_NUMERIC_CHECK);
$dataBLMaxYearPIB= json_encode($allBareLine['maxPib']['year'],JSON_NUMERIC_CHECK);
$dataBLMaxValPIB= json_encode($allBareLine['maxPib']['value'],JSON_NUMERIC_CHECK);

$dataBLMinValTourism= json_encode($allBareLine['minTourisme']['value'],JSON_NUMERIC_CHECK);
$dataBLMinYearTourism= json_encode($allBareLine['minTourisme']['year'],JSON_NUMERIC_CHECK);
$dataBLMaxValTourism= json_encode($allBareLine['maxTourisme']['value'],JSON_NUMERIC_CHECK);
$dataBLMaxYearTourism= json_encode($allBareLine['maxTourisme']['year'],JSON_NUMERIC_CHECK);

$dataBLcovidImpactPib= json_encode($allBareLine['covidImpactPib'],JSON_NUMERIC_CHECK);
$dataBLcovidImpactTourisme= json_encode($allBareLine['covidImpactTourisme'],JSON_NUMERIC_CHECK);

//$dataBarPays = json_encode(dataBarPays($id_pays, $cur),JSON_NUMERIC_CHECK);

$id_client = $_SESSION['user']['username'];
$id_pays = $_GET['id_pays'];
$query = "SELECT COUNT(id_client) FROM favoris WHERE id_pays = :id_pays AND id_client = :id_client";
$sth = $cur->prepare($query);
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->bindParam(":id_client", $id_client, PDO::PARAM_STR);
$sth->execute();

if ($sth->fetchColumn() > 0) {
    $favorite = "assets/icons/heart_full.png";
} else {
    $favorite = "assets/icons/heart.png";
}

echo <<<HTML

<div class="container-presentation expand-3" id="bandeau0" hx-swap-oob="outerHTML">
    <div class="bandeau"> 
        <img class="img-side img" src='assets/img/$id_pays.jpg' alt="Bandeau">
            <img class="favorite" id="favorite" src=$favorite hx-get="scripts/htmx/getFavorite.php" hx-trigger="click" hx-swap="outerHTML" hx-vals="js:{id_pays:'$id_pays'}">
        <div class="flag-plus-nom">
            <img class="flag" src='assets/twemoji/$id_pays.svg'>
            <h2 class="nom">$nom</h2>
        </div>
    </div>
</div>

<div class="container-side bg-354F52 g1-1" id="mini0" hx-swap-oob="outerHTML">
    <img class="img-side" src='assets/img/$id_pays.jpg' alt="Bandeau">
    <img class="flag-small" src='assets/twemoji/$id_pays.svg'>
    <h2 class="nom-small">$nom</h2>
</div>

<div class="container-presentation" id="score0" hx-swap-oob="outerHTML">
    <div class="score-box score-$letter">$letter</div>
</div>

<div class="container-presentation expand-3" id="description0" hx-swap-oob="outerHTML">
    <p class="paragraphe">$description</p>
</div>

<p class="name" id="nom0" hx-swap-oob="outerHTML">$nom</p>

<img class="flag-small" id="flag-bot" hx-swap-oob="outerHTML" src='assets/twemoji/$id_pays.svg'>

<script id=orders hx-swap-oob=outerHTML>
    spiderHTMX(0, $dataSpider, $dataTab, "$nom")
    barreLineHTMX($dataBarreLine, "$nom")
    linePaysHTMX($dataLine, $dataLineMean, "$nom")
    // topHTMX($dataBar, "$nom")

    miniMap[0].zoomTo("$id_pays")
    miniMap[0].addCities($cities)
    miniMap[0].addCapitals($capitals)
</script>

HTML;

?>