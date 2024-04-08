<?php

if (!isset($_SERVER["HTTP_HX_REQUEST"])) {
    header("HTTP/1.1 401");
    exit;
}

if (!isset($_GET["id_continent"]) || !isset($_GET["page"])) {
    header("HTTP/1.1 400");
    exit;
}

require("../../functions.php");

if (!checkHTMX("comparateur", $_SERVER["HTTP_HX_CURRENT_URL"]) && !checkHTMX("pays", $_SERVER["HTTP_HX_CURRENT_URL"])) {
    header("HTTP/1.1 401");
    exit;
}

$cur = getDB();
$page = $_GET["page"];
$id_continent = $_GET["id_continent"];
$queryPays = "SELECT * FROM pays WHERE id_continent = :id_continent ORDER BY score DESC LIMIT 10";
$resultPays = $cur->prepare($queryPays);
$resultPays->bindParam(":id_continent", $id_continent, PDO::PARAM_INT);
$resultPays->execute();

echo <<<HTML
    <div class='container-continents display' id="cata" hx-swap="swap:0.5s">
HTML;

while ($rsPays = $resultPays->fetch(PDO::FETCH_ASSOC)) {
    $letter = getLetter($rsPays["score"]);
    echo addSlimCountry($rsPays["id"],$rsPays["nom"],$letter,$page);
}

echo <<<HTML
    <div id="break" class="break"></div>
    <div id="more" class="more" hx-get="scripts/htmx/more.php" hx-vals="js:{id_continent:'$id_continent',more:1,page:'$page'}" hx-swap="beforebegin" hx-target="#break">
        <p>Voir plus</p>
    </div>
    </div>
HTML;
?>