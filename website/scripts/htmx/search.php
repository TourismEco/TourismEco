<?php

if (!isset($_SERVER["HTTP_HX_REQUEST"])) {
    echo "Bien vu ça hein";
    exit;
}

if (!isset($_GET["search"])) {
    echo "Mauvais arguments.";
    exit;
}

require("../../functions.php");
$cur = getDB();

$search = $_GET["search"];
$page = $_GET["page"];
$continent = $_GET["id_continent"];

if (strlen($search) == 0) {
    echo <<<HTML
        <div id=search class="container-catalogue"></div>
    HTML;
    exit;
}
if ($continent == 2) {
    $queryPays = "SELECT pays.id AS idp, pays.nom AS p, score FROM pays WHERE (id_continent = 3 OR id_continent = $continent ) AND (pays.nom LIKE '%".$search."%') ORDER BY score DESC LIMIT 20";
    $resultPays = $cur->query($queryPays);
}
else{
    $queryPays = "SELECT pays.id AS idp, pays.nom AS p, score FROM pays WHERE id_continent = $continent AND (pays.nom LIKE '%".$search."%') ORDER BY score DESC LIMIT 20";
    $resultPays = $cur->query($queryPays);
}


echo <<<HTML
    <div id=search class="container-catalogue">
HTML;

while ($rsPays = $resultPays->fetch(PDO::FETCH_ASSOC)) {
    $letter = getLetter($rsPays["score"]);
    echo addSlimCountry($rsPays["idp"],$rsPays["p"],$letter,$page);
}

echo <<<HTML
    </div>
HTML;    


?>