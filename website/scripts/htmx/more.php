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
$offset = 7*$more++; 

$queryCount = "SELECT COUNT(*) AS Count FROM pays WHERE id_continent = ".$continent;
$resultCount = $cur->query($queryCount);
$rsCount = $resultCount->fetch(PDO::FETCH_ASSOC);
$count = $rsCount["Count"];

$queryPays = "SELECT * FROM pays WHERE id_continent = ".$continent." ORDER BY score DESC LIMIT $offset, 7";
$resultPays = $cur->query($queryPays);

while ($rsPays = $resultPays->fetch(PDO::FETCH_ASSOC)) {
    $letter = getLetter($rsPays["score"]);
    echo addCardCountry($rsPays["id"],$rsPays["nom"],$letter);
}

if ($count >= $offset+7) {
    echo <<<HTML
        <div class="container-mini bg-354F52" hx-get="scripts/htmx/more.php?continent=$continent&more=$more" hx-swap="outerHTML">
            <div class="mini-bandeau"> 
                <h2 class="nom-region">Voir plus</h2>
            </div>
        </div>
    HTML;    
}

?>