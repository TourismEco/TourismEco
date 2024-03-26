<?php

if (!isset($_SERVER["HTTP_HX_REQUEST"])) {
    header("HTTP/1.1 401");
    exit;
}

if (!isset($_GET["id_pays"]) || !isset($_GET["incr"])) {
    header("HTTP/1.1 400");
    exit;
}

require("../../functions.php");

if (!checkHTMX("comparateur", $_SERVER["HTTP_HX_CURRENT_URL"])) {
    header("HTTP/1.1 401");
    exit;
}

$cur = getDB();

$id_pays = $_GET["id_pays"];
$incr = $_GET["incr"];

$old = "00";
if (!in_array($id_pays,$_SESSION["pays"])) {
    if (count($_SESSION["pays"]) != $incr) {
        $old = $_SESSION["pays"][$incr];
    }
    $_SESSION["pays"][$incr] = $id_pays;
}

// Nom
$query = "SELECT * FROM pays WHERE id = :id_pays";
$sth = $cur->prepare($query);
$sth->bindParam(":id_pays", $id_pays, PDO::PARAM_STR);
$sth->execute();
$ligne = $sth->fetch();
$nom = $ligne["nom"];
$sv1 = explode(" : ",htmlspecialchars($ligne["sv1"]));
$sv2 = explode(" : ",htmlspecialchars($ligne["sv2"]));
$sv3 = explode(" : ",htmlspecialchars($ligne["sv3"]));
$letter = getLetter($ligne["score"]);

$dataLine = json_encode(dataLine($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataSpider = json_encode(dataSpider($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataBar = json_encode(dataBar($id_pays, $cur),JSON_NUMERIC_CHECK);
$dataTab = json_encode(dataTab($id_pays, $cur),JSON_NUMERIC_CHECK);

$c = getCities($id_pays, $cur);
$cities = json_encode($c["cities"]);
$capitals = json_encode($c["capitals"]);

$incrP = $incr+1;

echo <<<HTML

<div class="container-presentation expand-2" id="bandeau$incr" hx-swap-oob="outerHTML">
    <div class="bandeau"> 
        <img class="img-side img" src='assets/img/$id_pays.jpg' alt="Bandeau">
        <div class="flag-plus-nom">
            <img class="flag" src='assets/twemoji/$id_pays.svg'>
            <h2 class="nom">$nom</h2>
        </div>
    </div>
</div>

<div class="container-presentation" id="score$incr" hx-swap-oob="outerHTML">
    <div class="score-box score-$letter">$letter</div>
</div>

<div class="container-side g$incrP-1" id="mini$incr" hx-swap-oob="outerHTML">
    <img class="img img-side" src='assets/img/$id_pays.jpg' alt="Bandeau">
    <img class="flag-small" src='assets/twemoji/$id_pays.svg'>
    <h2 class="nom-small">$nom</h2>
</div>

<p class="name" id="nom$incr" hx-swap-oob="outerHTML">$nom</p>
<img class="flag-tiny" src="assets/twemoji/$id_pays.svg" id="flag$incr" hx-swap-oob="outerHTML">

<script id="orders" hx-swap-oob="outerHTML">
    spiderHTMX($incr, $dataSpider, $dataTab, "$nom")
    lineHTMX($incr, $dataLine, "$nom")
    barHTMX($incr, $dataBar, "$nom")

    miniMap[$incr].addCities($cities)
    miniMap[$incr].addCapitals($capitals)
    miniMap[$incr].zoomTo("$id_pays")
</script>

HTML;

?>