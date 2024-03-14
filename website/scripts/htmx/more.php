<?php

if (!isset($_SERVER["HTTP_HX_REQUEST"])) {
    echo "Bien vu ça hein";
    exit;
}

if (!isset($_GET["continent"]) || !isset($_GET["more"])) {
    echo "Mauvais arguments.";
    exit;
}

require("../../functions.php");
$cur = getDB();

$continent = $_GET["continent"];
$more = $_GET["more"];
$page = $_GET["page"];
$offset = 12*$more++; 

if ($continent == 2) {
    $queryCount = "SELECT COUNT(*) AS Count FROM pays WHERE id_continent = 3 OR id_continent=".$continent;
    $resultCount = $cur->query($queryCount);
    $rsCount = $resultCount->fetch(PDO::FETCH_ASSOC);
    $count = $rsCount["Count"];
    
    $queryPays = "SELECT * FROM pays WHERE id_continent = 2 OR id_continent = 3 ORDER BY score DESC LIMIT $offset, 12";
    $resultPays = $cur->query($queryPays);
}
else{
    $queryCount = "SELECT COUNT(*) AS Count FROM pays WHERE id_continent = ".$continent;
    $resultCount = $cur->query($queryCount);
    $rsCount = $resultCount->fetch(PDO::FETCH_ASSOC);
    $count = $rsCount["Count"];
    
    $queryPays = "SELECT * FROM pays WHERE id_continent = ".$continent." ORDER BY score DESC LIMIT $offset, 12";
    $resultPays = $cur->query($queryPays);
}

while ($rsPays = $resultPays->fetch(PDO::FETCH_ASSOC)) {
    $letter = getLetter($rsPays["score"]);
    echo addSlimCountry($rsPays["id"],$rsPays["nom"],$letter,$page);
}

if ($count > $offset+4) {
    echo <<<HTML
        <div class="container-slim bg-52796F cursor" hx-get="scripts/htmx/more.php" hx-vals="js:{continent:'$continent', more:$more, page:'$page'}"hx-swap="outerHTML">
            <div class="bandeau-slim"> 
                <h2 class="nom-region">Voir plus</h2>
            </div>
        </div>
    HTML;    
}

?>