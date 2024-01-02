<?php

if (!isset($_SERVER["HTTP_HX_REQUEST"])) {
    echo "Bien vu ça hein";
    exit;
}

if (!isset($_GET["search"])) {
    echo "Mauvais arguments.";
    exit;
}

require("functions.php");
$cur = getDB();

$search = $_GET["search"];

if (strlen($search) == 0) {
    echo <<<HTML
        <div id=search class="container-catalogue"></div>
    HTML;
    exit;
}

$queryPays = "SELECT pays.id AS idp, pays.nom AS p, continents.nom AS c, score FROM pays, continents WHERE id_continent = continents.id AND (pays.nom LIKE '%".$search."%' OR continents.nom LIKE '%".$search."%') ORDER BY score DESC LIMIT 20";
$resultPays = $cur->query($queryPays);

echo <<<HTML
    <div id=search class="container-catalogue">
HTML;

while ($rsPays = $resultPays->fetch(PDO::FETCH_ASSOC)) {
    $letter = getLetter($rsPays["score"]);
    echo addCardCountry($rsPays["id"],$rsPays["nom"],$letter);
}


echo <<<HTML
    </div>
HTML;    


?>