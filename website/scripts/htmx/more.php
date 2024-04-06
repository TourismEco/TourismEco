<?php

if (!isset($_SERVER["HTTP_HX_REQUEST"])) {
    header("HTTP/1.1 401");
    exit;
}

if (!isset($_GET["id_continent"]) || !isset($_GET["more"]) || !isset($_GET["page"])) {
    header("HTTP/1.1 400");
    exit;
}

require("../../functions.php");
$cur = getDB();

$id_continent = $_GET["id_continent"];
$more = $_GET["more"];
$page = $_GET["page"];
$offset = 10+6*$more++; 

if ($id_continent == 2) {
    $queryCount = "SELECT COUNT(*) AS Count FROM pays WHERE id_continent = 3 OR id_continent = 2";
    $resultCount = $cur->query($queryCount);
    
    $queryPays = "SELECT * FROM pays WHERE id_continent = 2 OR id_continent = 3 ORDER BY score DESC LIMIT $offset, 6";
    $resultPays = $cur->query($queryPays);
}
else{
    $queryCount = "SELECT COUNT(*) AS Count FROM pays WHERE id_continent = :id_continent";
    $resultCount = $cur->prepare($queryCount);
    $resultCount->bindParam(":id_continent", $id_continent, PDO::PARAM_INT);
    $resultCount->execute();
    
    $queryPays = "SELECT * FROM pays WHERE id_continent = :id_continent ORDER BY score DESC LIMIT $offset, 6";
    $resultPays = $cur->prepare($queryPays);
    $resultPays->bindParam(":id_continent", $id_continent, PDO::PARAM_INT);
    $resultPays->execute();
}

$rsCount = $resultCount->fetch(PDO::FETCH_ASSOC);
$count = $rsCount["Count"];

while ($rsPays = $resultPays->fetch(PDO::FETCH_ASSOC)) {
    $letter = getLetter($rsPays["score"]);
    echo addSlimCountry($rsPays["id"],$rsPays["nom"],$letter,$page);
}

if ($count <= $offset+6) {
    echo <<<HTML
        <div id="more" hx-swap-oob="delete">
        </div>
    HTML;    
} else {
    echo <<<HTML
        <div id="more" class="more" hx-get="scripts/htmx/more.php" hx-vals="js:{id_continent:'$id_continent',more:$more,page:'$page'}" hx-swap="beforebegin" hx-target="#break" hx-swap-oob="outerHTML">
        <img src="assets/icons/plus.svg">
    </div>
    HTML;  
}

?>